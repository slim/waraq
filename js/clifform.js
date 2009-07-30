CLIFFORM = {
	form: null,
	table: null,
	formfields: null,
	tablecolumns: [],
	keyBinding: function (e) {
		var key = e.keyCode;
		switch (key) {
			case Event.KEY_RETURN:
				$(CLIFFORM.formfields.current()).hide();
				if (CLIFFORM.formfields.next()) {
					$(CLIFFORM.formfields.current()).show();
					CLIFFORM.formfields.current().focus();
				}
				else {
					CLIFFORM.form.submit();
					CLIFFORM.form.reset();
					CLIFFORM.formfields.first();
					$(CLIFFORM.formfields.current()).show();
					CLIFFORM.formfields.current().focus();
				}
			break;
		}
	},
	set_input: function (form) {
		CLIFFORM.form = form;
		CLIFFORM.formfields = new ElementList;
		CLIFFORM.formfields.select(['label'], form);
		while (CLIFFORM.formfields.next() != null) {
			$(CLIFFORM.formfields.current()).hide();
		}
		CLIFFORM.formfields.first();
		CLIFFORM.enable();
	},
	set_output: function (table, columns) {
		this.table = table;
		this.tablecolumns = columns;
		return this;
	},
	enable: function () {
		Event.observe(window, 'keypress', CLIFFORM.keyBinding);
		CLIFFORM.formfields.current().focus();
	},
	disable: function () {
		Event.stopObserving(window, 'keypress', CLIFFORM.keyBinding);
	},
	ajaxify: function (form, options) {
		if (!options) {
			var options = {};
		}
		options.parameters = $(form).serialize(true);
		form.submit = function () {
			new Ajax.Request(this.action, options);
		}
	},
	output: function (o) {
		var row = document.createElement('tr');
		for (var i=0; i < this.tablecolumns.length; i++) {
			var property = this.tablecolumns[i];
			var cell = document.createElement('td');
			cell.innerHTML = o[property];
			row.appendChild(cell);
		}
		this.table.tBodies[0].appendChild(row);
		return row;
	}
}
