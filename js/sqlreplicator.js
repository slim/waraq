sqlReplicator = {
	slave_db: null,
	master_url: null,
	master_pos: 0,
	chunk_size: 100,
	onComplete: null,

	pull: function () {
		new Ajax.Request(this.master_url, {
			parameters: { l: this.master_pos+','+this.chunk_size },
			method: 'get',
			onSuccess: function (transport) {
				var queries = transport.responseText.split(';');
				for(i=0; i < queries.length && i < sqlReplicator.chunk_size; i++) {
					if (queries[i]) {
						sqlReplicator.slave_db.execute(queries[i]);
					}
				}
				if (queries.length < sqlReplicator.chunk_size) {
					if (sqlReplicator.onComplete) {
						sqlReplicator.onComplete();
					}
					else {
						return;
					}
				}
				else {
					sqlReplicator.master_pos += sqlReplicator.chunk_size;
					sqlReplicator.pull();
				}
			}
		});
	}
}
