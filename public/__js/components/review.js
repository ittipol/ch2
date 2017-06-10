class Review {
  constructor(model,modelId) {
  	this.token = Setting.getCsrfToken();
  	this.model = model;
  	this.modelId = modelId;
  	this.form = '#user_review_form';
  	this.clickable = true;
  	this.page = 1;
  }

  load() {
    this.bind();
  }

  init() {}

  bind() {

  	let _this = this;

  	let target = (40 * document.body.clientHeight) / 100;

  	$(document).scroll(function() {

  		if($(this).scrollTop() > target) {
  			_this.getReview();
  			$(this).off('scroll');
  		}

  	});

  	$(this.form).on('submit',function(){

  		let formData = new FormData();
  		formData.append('_token', _this.token);
  		formData.append('review_model', _this.model);
  		formData.append('review_model_id', _this.modelId);
  		formData.append('score', $(this).find('input[name="score"]:checked').val());
  		formData.append('title', $(this).find('input[name="title"]').val());
  		formData.append('message', $(this).find('textarea[name="message"]').val());

			let request = $.ajax({
		    url: "/user_review",
		    type: "POST",
		    data: formData,
		    dataType: 'json',
		    contentType: false,
		    cache: false,
		    processData:false,
		    // mimeType:"multipart/form-data",
		    beforeSend: function( xhr ) {
		    	$('#loading_icon').addClass('display');
		    	$('.global-overlay').addClass('isvisible');
		    }
		  });

	    request.done(function (response, textStatus, jqXHR){

	    	if(response.success) {
	    		$('#user_review').html(response.user_review_html);
	    		$('#avg_score').text(response.avgScore);

	    		let len = Object.keys(response.scoreList).length;
	    		for (var i = 1; i <= len; i++) {
	    			$('#score_bar_'+i).find('.rating-bar-box').css('width',response.scoreList[i].percent+'%');
	    			$('#score_bar_'+i).find('.rating-count-text').text(response.scoreList[i].count);
	    		};

	    		setTimeout(function(){
	    			const rightSidePanel = new RightSidePanel();
	    			rightSidePanel.close();

	    			$('#loading_icon').removeClass('display');
	    			$('.global-overlay').removeClass('isvisible');

	    			const notificationBottom = new NotificationBottom();
	    			notificationBottom.setTitle('รีวิวถูกบันทึกแล้ว');
	    			notificationBottom.setType('success');
	    			notificationBottom.display();
	    		},550);

	    	}else{

	    		switch (response.type) {
	    		  case 'html':
	    		    $('#user_review_error').html(response.errorMessage);
	    		    break;
	    		  case 'popup':
	    		    const notificationBottom = new NotificationBottom();
	    		    notificationBottom.setTitle(response.errorMessage.title);
	    		    notificationBottom.setType('error');
	    		    notificationBottom.display();
	    		    break;
	    		}

	    		setTimeout(function(){
	    			$('#loading_icon').removeClass('display');
	    			$('.global-overlay').removeClass('isvisible');
	    		},300);
	    	}
	    	
	    });

	    request.fail(function (jqXHR, textStatus, errorThrown){
	      console.error(
	          "The following error occurred: "+
	          textStatus, errorThrown
	      );
	    });

  		return false;

  	});

		$('body').on('click','#more_review_btn', function(){
			console.log(_this.clickable);
			_this.getReview();
		});

  }

  getReview() {

  	if(!this.clickable) {
  		return false;
  	}

  	let _this = this;

		let request = $.ajax({
	    url: "/review_comment",
	    type: "get",
	    data: {
	      model: this.model,
	      model_id: this.modelId,
	      page: this.page
	    },
	    dataType: 'json',
	    beforeSend: function( xhr ) {

	    	_this.clickable = false;

	    	// $('#review_loading_sign').removeClass('hide-element-imp');
	    	// $('#more_review_btn').addClass('hide-element-imp');

	    	$('#more_review_btn').text('กำลังโหลด...');

	    }
	  });

	  request.done(function (response, textStatus, jqXHR){

	  	if(response.hasData) {
	  		$('#review_comment_wrapper').append(response.html);
	  		++_this.page;
	  	}else if(_this.page == 1) {
	  		$('#review_comment_wrapper').append('<h4 class="text-center space-top-bottom-40">ยังไม่มีรีวิวจากผู้ที่ซื้อสินค้านี้</h4>');
	  	}

	  	if(response.next) {
	  		$('#more_review_btn').text('แสดงเพิ่ม');
	  	}else{
	  		$('#more_review_btn').remove();
	  	}
	  	
	  	_this.clickable = true;

	  });

	  request.fail(function (jqXHR, textStatus, errorThrown){
	    console.error(
	        "The following error occurred: "+
	        textStatus, errorThrown
	    );
	  });

  }

}