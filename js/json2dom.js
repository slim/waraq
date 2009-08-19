function buildom(child, parent) {
	var element;

	function unitype(obj) {
		if (obj instanceof Array) {
			return "array";
		}
		return typeof(obj);
	};

	switch (unitype(child)) {
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
		for (var i=1; i < child.length ; i++) {
			buildom(child[i], element);
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
};
JSON2DOM = {
	deserialize: function (json) {
		eval("json = "+json);
		return buildom(json);
	}
}
