function Loti(input, url) {
	var search_url = url;
	this.input = input;
	this.input.loti = this;
	this.select = buildom(['select', {name: input.name}]);
	this.input.parentNode.appendChild(this.select);
	this.select.loti = this;
	$(this.select).hide();
	this.asSelect = function () {
		$(this.select).show();
		$(this.input).hide();
		this.select.focus();
	}
	this.asInput = function () {
		$(this.input).show();
		$(this.select).hide();
		this.input.focus();
	}
	this.search = function () {
		new Ajax.Updater(this.select, search_url, { method: 'get', parameters: {q: this.input.value}});
	}
	Event.observe(this.input, 'focus', function (e) { e.element().loti.enable(); });
	Event.observe(this.input, 'blur', function (e) { e.element().loti.disable();  });
	Event.observe(this.select, 'focus', function (e) { e.element().loti.enable();});
	Event.observe(this.select, 'blur', function (e) { e.element().loti.disable(); });
	this.keyBinding = function (e) {
		var key = e.keyCode;
		switch (key) {
			case Event.KEY_RETURN:
				Loti.current.search();
				Loti.current.asSelect();
			break;
			case Event.KEY_ESC:
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
