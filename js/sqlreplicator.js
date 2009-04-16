sqlReplicator = {
	slave_db: null,
	masters: [],
	master_index: 0,
	chunk_size: 100,
	onComplete: null,

	current_master: function () {
		return this.masters[this.master_index];
	},
	next_master: function () {
		if (this.master_index >= this.masters.length - 1) {
			return null;
		} else {
			this.master_index++;
			return this.current_master();
		}
	},
	set_master: function(url) {
		this.masters[this.masters.length] = {
			url: url,
			pos: 0
		}
	},
	pull: function () {
		new Ajax.Request(this.current_master().url, {
			parameters: { l: this.current_master().pos+','+this.chunk_size },
			method: 'get',
			onSuccess: function (transport) {
				var queries = transport.responseText.split(';');
				for(i=0; i < queries.length && i < sqlReplicator.chunk_size; i++) {
					if (queries[i].replace(/^\s+/g,'').replace(/\s+$/g,'')) {
						sqlReplicator.slave_db.execute(queries[i]);
					}
				}
				if (queries.length < sqlReplicator.chunk_size) {
					sqlReplicator.current_master().pos += queries.length;
					if (sqlReplicator.next_master()) {
						sqlReplicator.pull();
					} else if (sqlReplicator.onComplete) {
						sqlReplicator.onComplete();
					} else {
						return;
					}
				} else {
					sqlReplicator.current_master().pos += sqlReplicator.chunk_size;
					sqlReplicator.pull();
				}
			}
		});
		return this;
	}
}
