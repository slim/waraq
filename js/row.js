function ElementList(elements) {
	this.elements = [];
	this.current_element = null;

	if (elements) {
		this.set_elements(elements);
	}

	this.set_elements = function (elements) {
		for (var r=0, d=0; r < elements.length; r++) {
			if (!elements[r].id) {
				delete elements[r];
				d++;
			}
			else {
				elements[r].element_index = r - d;
				elements[r].element_list = this;
				elements[r].setAsCurrent = function() {
					this.element_list.current_element = this.element_index;
				}
			}
		}
		this.elements = elements;
		return this;
	}

	this.select = function (tagName, rootNode) {
		return this.set_elements(ElementList.select(tagName, rootNode));
	}

	this.first = function() {
		this.current_element = 0;

		return this.elements[this.current_element];
	};
	this.current = function() {
		return this.elements[this.current_element] || this.first();
	};
	this.next = function() {
		if (this.current_element >= this.elements.length - 1) {
			if (this.onLast) {
				this.onLast();
			}
			return null;
		}
		else {
			this.current_element++;
			if (this.onNext) {
				this.onNext();
			}
		}

		return this.elements[this.current_element];
	};
	this.previous = function() {
		if (this.current_element > 0 ) {
			this.current_element--;
		}

		if (this.onPrevious) {
			this.onPrevious();
		}

		return this.elements[this.current_element];
	};

}

ElementList.select = function function (tagName, rootNode) {
	var root = rootNode || document.body;
	var document_elements = [], tag_elements = [];

	for (var i=0; i < tagName.length; i++) {
		tag_elements = root.getElementsByTagName(tagName[i]);
		for (var j=0; j < tag_elements.length; j++) {
			document_elements[document_elements.length] = tag_elements[j];
		}
	}
	return document_elements;
}
