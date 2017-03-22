class GlobalCart {

	constructor(token = null) {
		this.token = token;
		this.allowedInput = true;
		this.requestUpdateQuantity = false;
		this.tempQuantity;
	}

	load() {
		this.bind();
	}

	bind() {

		let _this = this;

		// For global cart panel & cart page
		$(document).on('click','.delete-product-button',function(){
		  _this.cartDelete($(this).data('id'),this);
		});

		$(document).on('keydown','.cart .cart-summary-quantity-input',function(){

			if(!_this.requestUpdateQuantity) {
				_this.tempQuantity = $(this).val();
			}

			_this.requestUpdateQuantity = true;

		})

		$(document).on('blur','.cart .cart-summary-quantity-input',function(){
			if(_this.requestUpdateQuantity) {
				_this.requestUpdateQuantity = false;

				let quantity = $(this).val();
				let minimum = $(this).data('minimum');

				if((quantity == '') || (quantity == 0)) {
					$(this).val(_this.tempQuantity);
				}else if(minimum > quantity){

					$(this).val(_this.tempQuantity);

					const notificationBottom = new NotificationBottom('ไม่สามารถสั่งซื้อได้','จำนวนการสั่งซื้อของคุณน้อยกว่าจำนวนการสั่งซื้อขั้นต่ำของสินค้านี้','error');
					notificationBottom.setDelay(5000);
					notificationBottom.load();

				}else{
					_this.updateQuantity($(this).data('id'),quantity);
				}

			}
		})

	}

	updateQuantity(productId,quantity) {

		let _this = this;

		let request = $.ajax({
	    url: "/update_quantity",
	    type: "POST",
	    data: this.createAddForm(productId,quantity),
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

	  	if(response.hasError) {

	  		setTimeout(function(){
	  			const notificationBottom = new NotificationBottom(response.errorMessage,'','error');
	  			notificationBottom.setDelay(5000);
	  			notificationBottom.load();

	  			$('#loading_icon').removeClass('display');
	  		  $('.global-overlay').removeClass('isvisible');
	  		},1000);

	  	}else if(response.success) {
	    	// update product count
	    	_this.productCountUpdate();
	    	// update product in global cart
	    	_this.cartUpdate();
	    	// update in cart page
	    	$('#_product_'+productId).find('.product-total-amount').text(response.productTotal);
	    	$('#_product_'+productId).find('.product-shipping-cost-amount').text(response.shippingCostTotal);
	    	$('#_product_'+productId).find('.error-message').remove();

	    	let parent = $('#_product_'+productId).parent().parent();

	    	parent.find('.sub-total').find('.amount').text(response.summaries.subTotal.value);
	    	parent.find('.shipping-cost').find('.amount').text(response.summaries.shippingCost.value);
	    	parent.find('.saving-price').find('.amount').text(response.summaries.savingPrice.value);
	    	parent.find('.total-amount').find('.amount').text(response.summaries.total.value);

	    	setTimeout(function(){
	    		const notificationBottom = new NotificationBottom('สินค้า '+quantity+' ชิ้น ได้ถูกบันทึกไปยังตะกร้าสินค้าของคุณ','','success');
	    		notificationBottom.setDelay(5000);
	    		notificationBottom.load();

	    		$('#loading_icon').removeClass('display');
	    	  $('.global-overlay').removeClass('isvisible');
	    	},1000);
	    	
	    }

	  });

	  request.fail(function (jqXHR, textStatus, errorThrown){
	    console.error(
	        "The following error occurred: "+
	        textStatus, errorThrown
	    );
	  });

	}

	cartAdd(productId,quantity) {

    this.allowedInput = false;

		let _this = this;

		let request = $.ajax({
	    url: "/cart_add",
	    type: "POST",
	    data: this.createAddForm(productId,quantity),
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

			if(response.hasError) {

				setTimeout(function(){
					const notificationBottom = new NotificationBottom(response.errorMessage,'','error');
					notificationBottom.setDelay(5000);
					notificationBottom.load();

					$('#loading_icon').removeClass('display');
				  $('.global-overlay').removeClass('isvisible');
				},1000);

			}else if(response.success) {

		  	// update product count
		  	_this.productCountUpdate();
		  	// update product in global cart
		  	_this.cartUpdate();

		  	setTimeout(function(){
		  		const notificationBottom = new NotificationBottom('สินค้า '+quantity+' ชิ้น ได้ถูกเพิ่มเข้าไปยังตะกร้าสินค้าของคุณ','','success');
		  		notificationBottom.setDelay(5000);
		  		notificationBottom.load();

		  		$('#loading_icon').removeClass('display');
		  	  $('.global-overlay').removeClass('isvisible');
		  	},1000);
		  	
		  }

		  setTimeout(function(){
		    _this.allowedInput = true;
		  },400);

		});

		request.fail(function (jqXHR, textStatus, errorThrown){
		  console.error(
		      "The following error occurred: "+
		      textStatus, errorThrown
		  );
		});

	}

	cartDelete(productId,obj) {

		this.allowedInput = false;

		let _this = this;

	  let request = $.ajax({
	    url: "/cart_delete",
	    type: "POST",
	    data: this.createDeleteForm(productId),
	    dataType: 'json',
	    contentType: false,
	    cache: false,
	    processData:false,
	    beforeSend: function( xhr ) {
	    	$('#loading_icon').addClass('display');
	    	$('.global-overlay').addClass('isvisible');

	    	$(obj).parent().slideUp(200);

	    	if ($('#_product_'+productId).length) {
					$('#_product_'+productId).slideUp(200);
	    	}
	    }
	  });

	  request.done(function (response, textStatus, jqXHR){

	    if(response.success) {
	    	// update product count
	    	_this.productCountUpdate();

	    	if(response.empty) {

	    		setTimeout(function(){

	    			$('#cart_panel').html(response.html);

	    			_this.cartUpdate();

	    			$('#loading_icon').removeClass('display');
	    			$('.global-overlay').removeClass('isvisible');
	    		},200);

	    	}else if(response.totalShopProductEmpty) {

	    		let parent = $('#'+$('#_product_'+productId).data('id'));

	    		setTimeout(function(){

	    			if(typeof $(obj).data('global-cart') == 'undefined') {
	    				_this.cartUpdate();
	    			}
	    			
	    			parent.remove();
	    			$('#loading_icon').removeClass('display');
	    			$('.global-overlay').removeClass('isvisible');
	    		},200);

	    	}else{

	    		// let parent = $('#_product_'+productId).parent().parent();
	    		let parent = $('#'+$('#_product_'+productId).data('id'));
	    
	    		parent.find('.sub-total').find('.amount').text(response.summaries.subTotal.value);
	    		parent.find('.shipping-cost').find('.amount').text(response.summaries.shippingCost.value);
	    		parent.find('.saving-price').find('.amount').text(response.summaries.savingPrice.value);
	    		parent.find('.total-amount').find('.amount').text(response.summaries.total.value);

	    		setTimeout(function(){
	    			$(obj).parent().remove();	
	    			$('#loading_icon').removeClass('display');
	    			$('.global-overlay').removeClass('isvisible');
	    		},200);
	    		
	    	}

	    }

	    setTimeout(function(){
	      _this.allowedInput = true;
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
		  dataType:'json',
		  beforeSend: function( xhr ) {
		  	$('#global_cart_panel').fadeOut(220);
		  }
		});

		request.done(function (response, textStatus, jqXHR){
			setTimeout(function(){
				$('#global_cart_panel').html(response.html).fadeIn(220);
			},220);
		});

		request.fail(function (jqXHR, textStatus, errorThrown){
		  console.error(
		      "The following error occurred: "+
		      textStatus, errorThrown
		  );
		});

	}

	productCountUpdate() {

		let request = $.ajax({
		  url: "/product_count",
		  type: "get",
		  dataType:'json'
		});

		// Callback handler that will be called on success
		request.done(function (response, textStatus, jqXHR){
			$('#cart_product_count').text(response.total);
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

	createAddForm(productId,quantity) {

		let formData = new FormData();
		formData.append('_token', this.token);
		formData.append('productId', productId);
		formData.append('quantity', quantity); 

		return formData;

	}

	createDeleteForm(productId) {

	  let formData = new FormData();
	  formData.append('_token', this.token);
	  formData.append('productId', productId);

	  return formData;

	}

}