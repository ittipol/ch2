class Review {
  constructor() {}

  load() {
    // this.init();
    this.bind();


  }

  init() {}

  bind() {

  	$('#user_review_form').on('submit',function(){

  		let formData = new FormData();
  		formData.append('_token', $('input[name="_token"]').val());
  		// formData.append('data', $(this).serialize()); 
  		formData.append('_model', $(this).find('input[name="_model"]').val());
  		formData.append('score', $(this).find('input[name="score"]').val());
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
		    mimeType:"multipart/form-data",
		  });

	    request.done(function (response, textStatus, jqXHR){

	    	console.log('sds');
	    	
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