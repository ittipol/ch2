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
		// this.updateCount();
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

	// updateCount() {

	// 	this.socket.on('count-notification', function( data ) {
	// 		let currentCount = parseInt($('#notification_count').text());
	// 		$('#notification_count').text(currentCount+data.count);
	// 	});

	// }

	updateNotification() {

		let _this = this;

		this.socket.on('update-notification', function( data ) {

			// console.log(data.updated);

			// _this.createNotification(data.title,data.url);
			// _this.popup(data.title,data.message);

			_this.getData();

		});

	}

	getData() {

		let request = $.ajax({
		  url: "/notification_update",
		  type: "get",
		  dataType:'json'
		});

	  request.done(function (response, textStatus, jqXHR){
	    // $('#district').empty();
	    // $.each(response, function(key,value) {
	    //   let option = $("<option></option>");

  	  //   if(key == _this.districtId){
  	  //     option.prop('selected',true);
  	  //     _this.districtId = null;
  	  //   }

	    //   $('#district').append(option.attr("value", key).text(value));
	    // });

	    // _this.getSubDistrict($('#district').val());
	    
	  });

	}

	popup(title,message) {

		const notificationBottom = new NotificationBottom(title,message);
		notificationBottom.load();

	}

	createNotification(title,url) {

		let html = '';

		html += '<div class="notification-list-table-row clearfix">';
		html += '<div class="notification-image pull-left">';
		html += '<a href="http://ch.local/account/order/37">';
		html += '<img src="/images/icons/bag-white.png">';
		html += '</a>';
		html += '</div>';
		html += '<div class="notification-info pull-left">';
		html += '<a href="http://ch.local/account/order/37">';
		html += '<h4 class="notification-title">'+title+'</h4>';
		html += '</a>';
		html += '<div class="notification-message"></div>';
		html += '<div class="notification-date">31 มีนาคม 2560</div>';
		html += '</div>';
		html += '</div>';

		$('#notification_panel').prepend(html);

	}

}