class PushNotification {

	constructor(person,token) {
		this.person = person;
		this.token = token;
		this.channel = token;
		this.socket;
	}

	load() {
		this.socket = io.connect( 'http://localhost:8080' );

		this.subscribe();
		this.check();
		this.updateNotification();
		this.updateCount();
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

		this.socket.on( 'update-notification', function( data ) {
			_this.popup(data.title,data.message);
		});

	}

	updateCount() {

		this.socket.on( 'count-notification', function( data ) {
			let currentCount = parseInt($('#notification_count').text());
			$('#notification_count').text(currentCount+data.count);
		});

	}

	popup(title,message) {

		const notificationBottom = new NotificationBottom(title,message);
		notificationBottom.load();

	}

}