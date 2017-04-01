class PushNotification {

	constructor(person,token) {
		this.person = person;
		this.token = token;
		this.channel = token;
		this.socket = null;
		this.clearCount = true;
	}

	load() {
		this.socket = io.connect( 'http://localhost:8080' );

		this.subscribe();
		this.check();
		this.updateNotification();
	
		this.bind();
	}

	bind() {

	}

	subscribe() {
		this.socket.emit('subscribe', this.channel);	
	}

	check() {

		let _this = this;

		setInterval(function(){
			_this.socket.emit('check-notification', { room: _this.channel, person: _this.person, token: _this.token });
		},6000);

	}

	updateNotification() {

		let _this = this;

		this.socket.on('update-notification', function( data ) {
			_this.getData();
		});

	}

	getData() {

		let _this = this;

		let request = $.ajax({
		  url: "/notification_update",
		  type: "get",
		  dataType:'json'
		});

	  request.done(function (response, textStatus, jqXHR){

	  	if(response.updated) {
	  		$('#notification_count').text(response.count);
	  		$('#notification_panel').prepend(response.html);
	  		_this.popup(response.title);
	  	}
	    
	  });

	}

	popup(title) {

		const notificationBottom = new NotificationBottom(title);
		notificationBottom.load();

	}

}