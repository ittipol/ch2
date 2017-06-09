class Review {
  constructor() {}

  load() {
    this.bind();
  }

  init() {}

  bind() {

  	$('#user_review_form').on('submit',function(){

  		let formData = new FormData();
  		formData.append('_token', $('input[name="_token"]').val());
  		formData.append('review_model', $(this).find('input[name="review_model"]').val());
  		formData.append('review_model_id', $(this).find('input[name="review_model_id"]').val());
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

	    			const notificationBottom = new NotificationBottom('รีวิวถูกบันทึกแล้ว','','success');
	    			notificationBottom.load();
	    		},550);

	    	}else{
	    		$('#user_review_error').html(response.html);

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

}