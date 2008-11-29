function Mode(setup) {
	this.original_enter = setup.enter;
	this.enter = function() {
		if (Mode.current) {
			Mode.current.exit();
		}
		Mode.current = this;
		this.original_enter();
	}

	this.exit = setup.exit;
}
