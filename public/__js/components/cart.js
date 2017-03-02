class Cart {
	
	constructor(token,productId,minimumOrder) {
		this.token = token;
		this.productId = productId;
		this.minimumOrder = minimumOrder;
		this.allowedClick = true;
	}

	load() {
		this.bind();
	}

	bind() {

		let _this = this;

		$('#add_to_cart_button').on('click',function(){

			let quantity = $('#product_quantity').val();

			if(_this.minimumOrder > quantity) {

				const notificationBottom = new NotificationBottom('ไม่สามารถสั่งซื้อได้','จำนวนการสั่งซื้อของคุณน้อยกว่าจำนวนการสั่งซื้อขั้นต่ำของสินค้านี้','error');
				notificationBottom.setDelay(5000);
				notificationBottom.load();

			}else{
				_this.addToCart(quantity);
			}

		});
	
	}

	createForm(quantity) {

		let formData = new FormData();
		formData.append('_token', this.token);
		formData.append('productId', this.productId);
		formData.append('quantity', quantity); 

		return formData;

	}

	addToCart(quantity) {

		if(!this.allowedClick) {
      return false;
    }

    this.allowedClick = false;

		let _this = this;

		let request = $.ajax({
	    url: "/add_to_cart",
	    type: "POST",
	    data: this.createForm(quantity),
	    dataType: 'json',
	    contentType: false,
	    cache: false,
	    processData:false,
	    beforeSend: function( xhr ) {
	    	$('#loading_icon').addClass('display');
	    	$('.global-overlay').addClass('isvisible');
	    }
	  });

		request.done(function (response, textStatus, jqXHR){

		  if(response.success) {

		  	// update item count
		  	_this.productCount();
		  	// update item in global cart
		  	_this.cartUpdate();

		  	setTimeout(function(){

		  		const notificationBottom = new NotificationBottom('สินค้า '+quantity+' ชิ้น ได้ถูกเพิ่มเข้าไปยังตะกร้าสินค้าของคุณ','','success');
		  		notificationBottom.setDelay(5000);
		  		notificationBottom.load();

		  		$('#loading_icon').removeClass('display');
		  	  $('.global-overlay').removeClass('isvisible');
		  	},2000);
		  	
		  }

		  setTimeout(function(){
		    _this.allowedClick = true;
		  },400);

		});

		request.fail(function (jqXHR, textStatus, errorThrown){
		  console.error(
		      "The following error occurred: "+
		      textStatus, errorThrown
		  );
		});

	}

	cartUpdate() {

		let request = $.ajax({
		  url: "/cart_update",
		  type: "get",
		  dataType:'json'
		});

		// Callback handler that will be called on success
		request.done(function (response, textStatus, jqXHR){
			$('#global_cart_panel').html(response.html);
		});

		// Callback handler that will be called on failure
		request.fail(function (jqXHR, textStatus, errorThrown){
		  // Log the error to the console
		  console.error(
		      "The following error occurred: "+
		      textStatus, errorThrown
		  );
		});

	}

	productCount() {

		let request = $.ajax({
		  url: "/product_count",
		  type: "get",
		  dataType:'json'
		});

		// Callback handler that will be called on success
		request.done(function (response, textStatus, jqXHR){
			$('#cart_item_count').text(response.total);
		});

		// Callback handler that will be called on failure
		request.fail(function (jqXHR, textStatus, errorThrown){
		  // Log the error to the console
		  console.error(
		      "The following error occurred: "+
		      textStatus, errorThrown
		  );
		});

	}

	// setMinimumOrder(minimumOrder) {
	// 	this.minimumOrder = minimumOrder;	
	// }

	// setProductId(productId) {
	// 	this.productId = productId;	
	// }

}