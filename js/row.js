function ElementList(element, root) {
	this.root = root || document.body;
	this.rows = [];
	this.current_row = null;

	var document_tables, document_rows;

	document_rows = this.root.getElementsByTagName(element);
	for (var r=0; r < document_rows.length; r++) {
		if (document_rows[r].id) {
			document_rows[r].ROW_index = this.rows.length;
			document_rows[r].TRlist = this;
			document_rows[r].setAsCurrent = function() {
				this.TRlist.current_row = this.ROW_index;
			}
			this.rows[document_rows[r].ROW_index] = document_rows[r];
		}
	}

	this.first = function() {
		this.current_row = 0;

		return this.rows[this.current_row];
	};
	this.current = function() {
		return this.rows[this.current_row] || this.first();
	};
	this.next = function() {
		if (this.current_row < this.rows.length - 1) {
			this.current_row++;
		}

		if (this.onNext) {
			this.onNext();
		}

		return this.rows[this.current_row];
	};
	this.previous = function() {
		if (this.current_row > 0 ) {
			this.current_row--;
		}

		if (this.onPrevious) {
			this.onPrevious();
		}

		return this.rows[this.current_row];
	};
}
