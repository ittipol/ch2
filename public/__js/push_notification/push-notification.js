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
			_this.createNotification();
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

	createNotification() {

		// <div class="notification-list-table-row clearfix">

		//               <div class="notification-image pull-left">
		//                 <a href="http://ch.local/account/order/37">
		//                   <img src="/images/icons/bag-white.png">
		//                 </a>
		//               </div>

		//               <div class="notification-info pull-left">
		//                 <a href="http://ch.local/account/order/37">
		//                   <h4 class="notification-title">การสั่งซื้อจากลูกค้า เลขที่ 31</h4>
		//                 </a>
		//                 <div class="notification-message"></div>
		//                 <div class="notification-date">31 มีนาคม 2560</div>
		//               </div>
		//             </div>

	}

	// getImage() {

	// 	switch() {
	// 		case 'Order':
	// 		    image = '/images/icons/bag-white.png';
	// 		  break;
	// 	}

	// }

}