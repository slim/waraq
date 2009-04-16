sqlReplicator = {
	slave_db: null,
	masters: [],
	master_index: 0,
	chunk_size: 100,

	current_master: function () {
		return this.masters[this.master_index];
	},
	next_master: function () {
		this.master_index++;
		return this.current_master();
	},
	set_master: function(url, onComplete) {
		this.masters[this.masters.length] = {
			url: url,
			pos: 0,
			onComplete: onComplete
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
					if (sqlReplicator.next_master()) {
						sqlReplicator.pull();
					}
					if (sqlReplicator.current_master().onComplete) {
						sqlReplicator.current_master().onComplete();
					}
					else {
						return;
					}
				}
				else {
					sqlReplicator.current_master().pos += sqlReplicator.chunk_size;
					sqlReplicator.pull();
				}
			}
		});
		return this;
	}
}
