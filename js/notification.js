function Notification(message, style) {
	if (!Notification.area) 
		Notification.area = document.body;
	if (!Notification.default_timeout) 
		Notification.default_timeout = 5000;
	if (!Notification.default_style) 
		Notification.default_style = 'message_notification';
	if (!Notification.lastId) 
		Notification.lastId = 0;

	if (!style) style = Notification.default_style;
	this.id = "notification_"+Notification.lastId++;
	this.node = new Element('div', {id: this.id, class: style}).update(message);
	Notification.area.insert(this.node);
	this.donthide = function () {
		clearTimeout(this.timer);
		return this;
	}
	this.hide = function (time) {
		if (this.timer)
			clearTimeout(this.timer);
		if (!time)
			time = 0;
		this.timer = setTimeout('$("'+this.id+'").hide()', time);
		return this;
	}
	this.timer = setTimeout('$("'+this.id+'").hide()', Notification.default_timeout);
}
