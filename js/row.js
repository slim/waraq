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
		var root = rootNode || document.body;
		var document_elements;

		document_elements = root.getElementsByTagName(tagName);
		return this.set_elements(document_elements);
	}

	this.first = function() {
		this.current_element = 0;

		return this.elements[this.current_element];
	};
	this.current = function() {
		return this.elements[this.current_element] || this.first();
	};
	this.next = function() {
		if (this.current_element < this.elements.length - 1) {
			this.current_element++;
		}

		if (this.onNext) {
			this.onNext();
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
