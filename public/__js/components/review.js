class Review {
  constructor(model,modelId) {
  	this.token = Setting.getCsrfToken();
  	this.model = model;
  	this.modelId = modelId;
  	this.form = '#user_review_form';
  	this.page = 1;
  }

  load() {

  	this.getReview();

    this.bind();
  }

  init() {}

  bind() {

  	let _this = this;

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

  }

  getReview() {

  	// let formData = new FormData();
  	// formData.append('review_model', this.model);
  	// formData.append('review_model_id', this.modelId);

		let request = $.ajax({
	    url: "/review_comment",
	    type: "get",
	    data: {
	      model: this.model,
	      model_id: this.modelId,
	      page: this.page
	    },
	    dataType: 'json',
	  });

	  request.done(function (response, textStatus, jqXHR){
	  	console.log('axxx');
	  });

	  request.fail(function (jqXHR, textStatus, errorThrown){
	    console.error(
	        "The following error occurred: "+
	        textStatus, errorThrown
	    );
	  });

  }

}