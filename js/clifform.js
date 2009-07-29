CLIFFORM = {
	form: null,
	elements: null,
	keyBinding: function (e) {
		var key = e.keyCode;
		switch (key) {
			case Event.KEY_RETURN:
				$(CLIFFORM.elements.current()).hide();
				if (CLIFFORM.elements.next()) {
					$(CLIFFORM.elements.current()).show();
					CLIFFORM.elements.current().focus();
				}
				else {
					CLIFFORM.form.submit();
				}
			break;
		}
	},
	swallow: function (form) {
		CLIFFORM.form = form;
		CLIFFORM.elements = new ElementList;
		CLIFFORM.elements.select(['label'], form);
		while (CLIFFORM.elements.next() != null) {
			$(CLIFFORM.elements.current()).hide();
		}
		CLIFFORM.elements.first();
		CLIFFORM.enable();
	},
	enable: function () {
		Event.observe(window, 'keypress', CLIFFORM.keyBinding);
		CLIFFORM.elements.current().focus();
	},
	disable: function () {
		Event.stopObserving(window, 'keypress', CLIFFORM.keyBinding);
	}
}
