ROW = {
	root: document,
	rows: [],
	current_row: null,
	init: function(table) {
		var tables, rows;

		ROW.rows = [];
		if (table) {
			tables = [table];
		} else {
			tables = ROW.root.getElementsByTagName('table');	
		}
		for (var t=0; t < tables.length; t++) {
			rows = tables[t].getElementsByTagName('tr');
			for (var r=0; r < rows.length; r++) {
				if (rows[r].id) {
					rows[r].ROW_index = ROW.rows.length;
					rows[r].setAsCurrent = function() {
						ROW.current_row = this.ROW_index;
					}
					ROW.rows[rows[r].ROW_index] = rows[r];
				}
			}
		}
	},
	first: function() {
		if (ROW.rows.length < 1) {
			ROW.init();
		}
		ROW.current_row = 0;

		return ROW.rows[ROW.current_row];
	},
	current: function() {
		return ROW.rows[ROW.current_row] || ROW.first();
	},
	next: function() {
		if (ROW.current_row < ROW.rows.length - 1) {
			ROW.current_row++;
		}

		if (ROW.onNext) {
			ROW.onNext();
		}

		return ROW.rows[ROW.current_row];
	},
	previous: function() {
		if (ROW.current_row > 0 ) {
			ROW.current_row--;
		}

		if (ROW.onPrevious) {
			ROW.onPrevious();
		}

		return ROW.rows[ROW.current_row];
	}
}
