SelectOption = function (data) {
	this.value = data[SelectOption.mapping.value];
	this.display = data[SelectOption.mapping.display];
	this.toDom = function () {
		return buildom(['option', {value: this.value}, this.display]);
	}
}
SelectOption.mapping = {};
function Loti(input, json_resource) {
	var search_url = json_resource.url;
	SelectOption.mapping = {display: json_resource.display, value: json_resource.value};
	this.input = input;
	this.input.loti = this;
	this.select = buildom(['select', {name: input.name}]);
	this.input.parentNode.appendChild(this.select);
	this.select.loti = this;
	$(this.select).hide();
	this.onSelect = null;
	this.asSelect = function () {
		$(this.select).show();
		$(this.input).hide();
		this.input.disabled='disabled';
		this.select.disabled='';
		this.select.focus();
		if (this.onSelect) this.onSelect();
	}
	this.asInput = function () {
		$(this.input).show();
		$(this.select).hide();
		this.input.disabled='';
		this.select.disabled='disabled';
		this.input.focus();
	}
	this.search = function () {
		new Ajax.Request(search_url, { 
			method: 'get', 
			parameters: {q: this.input.value}, 
			onSuccess: (function (transport) {
				this.select.innerHTML="";
				var data = transport.responseText.evalJSON();
				for (var i=0; i < data.length ; i++) {
					var so = new SelectOption(data[i]);
					$(this.select).insert(so.toDom());
				}
		 	}).bind(this)
		});
	}
	Event.observe(this.input, 'focus', function (e) { e.element().loti.enable(); });
	Event.observe(this.input, 'blur', function (e) { e.element().loti.disable();  });
	Event.observe(this.select, 'focus', function (e) { e.element().loti.enable();});
	Event.observe(this.select, 'blur', function (e) { e.element().loti.disable(); });
	this.keyBinding = function (e) {
		var key = e.charCode || e.keyCode; 
		switch (key) {
			case Loti.KEY_SEARCH:
				Loti.current.search();
				Loti.current.asSelect();
			break;
			case Loti.KEY_INPUT:
				Loti.current.asInput();
			break;
		}
	}
	this.enable = function () {
		Loti.current = this;
		Event.observe(window, 'keypress', this.keyBinding);
	}
	this.disable = function () {
		Event.stopObserving(window, 'keypress', this.keyBinding);
	}
}
Loti.KEY_SEARCH = Event.KEY_RETURN;
Loti.KEY_INPUT = Event.KEY_ESC;
