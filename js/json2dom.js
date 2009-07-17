JSON2DOM = {
	deserialize: function (json) {
		eval("json = "+json);
		return JSON2DOM.build(json);
	},
	type: function (obj) {
		if (obj instanceof Array) {
			return "array";
		}
		return typeof(obj);
	},
	build: function (child, parent) {
		var element;

		switch (JSON2DOM.type(child)) {
		case 'string':
			parent.appendChild(document.createTextNode(child));
			break;
		case 'object':
			for (attribute in child) {
				parent.setAttribute(attribute, child[attribute]);
			}
			break;
		case 'array':
			element = document.createElement(child[0]);
			for (i=1; i < child.length ; i++) {
				JSON2DOM.build(child[i], element);
			}
			if (parent) {
				parent.appendChild(element);
			}
			else {
				parent = element;
			}
			break;
		}
		return parent;
	}
}
