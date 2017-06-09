class PushNotification {

	constructor(person,token) {
		this.person = person;
		this.token = token;
		this.channel = token;
		this.socket = null;
		this.clearCount = true;
	}

	load() {
		// this.socket = io.connect('http://localhost:8080');

		// Use when start node server
		// this.subscribe();
		// this.check();
		// this.updateNotification();
	
		this.bind();
	}

	bind() {

		let _this = this;

		$('#notification_panel_trigger').on('click',function(){
			
			if(_this.clearCount) {

				_this.clearCount = false;

				$('#notification_count').text(0);

				let request = $.ajax({
				  url: "/notification_read",
				  type: "get",
				  dataType:'json'
				});

			}

		});

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

	  		if($('#notification_panel').find('.notification-empty').length == 1) {
	  			$('#notification_panel').find('.notification-empty').remove();
	  		}

	  		$('#notification_panel').prepend(response.html);
	  		_this.popup(response.title);

	  		_this.clearCount = true;

	  	}
	    
	  });

	}

	popup(title) {
		const notificationBottom = new NotificationBottom(title);
		notificationBottom.setTitle(title);
		notificationBottom.setType('info');
		notificationBottom.display();
	}

}