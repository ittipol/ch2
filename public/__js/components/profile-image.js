class ProfileImage {

	constructor(token,target,id,type) {
		this.token = token;
		this.target = target;
		this.id = id;
		this.type = type;
		this.code;
		this.oldImageFile = '';
		this.file;
		this.proceed = false;
		this.profileImage;
		this.cover;
	}

	load() {
		this.init();
		this.bind();
	}

	init() {
		let token = new Token();
		this.code = token.generateToken(7);
	}

	bind() {

			let _this = this;

			$(document).on('change','#'+this.code+'_image_button .input-profile-image',function(){
				_this.preview(this);
			});

			$(document).on('click', '.'+this.code+'-accept-btn', function(){
				_this.uploadImage();
			});

			$(document).on('click', '.'+this.code+'-cancel-btn', function(){
				_this.removePreview();
			});

	}

	setElem(buttonId,PanelId,acceptBtn,cancelBtn) {

		// document.getElementById(PanelId).style.backgroundColor = '#fff';
		// document.getElementById(PanelId).setAttribute('class','preview-image-panel');
		document.getElementById(PanelId).setAttribute('id',this.code+'_image_panel');

		document.getElementById(buttonId).append(this.createInputFile(this.code));
		document.getElementById(buttonId).setAttribute('id',this.code+'_image_button');

		let _class = document.getElementById(acceptBtn).getAttribute('class');
		document.getElementById(acceptBtn).setAttribute('class',_class+' '+this.code+'-accept-btn');
		document.getElementById(acceptBtn).setAttribute('id',this.code+'_accept_button');

		_class = document.getElementById(cancelBtn).getAttribute('class');
		document.getElementById(cancelBtn).setAttribute('class',_class+' '+this.code+'-cancel-btn');
		document.getElementById(cancelBtn).setAttribute('id',this.code+'_cancel_button');


	}

	preview(input){

		if (input.files && input.files[0]) {

			let _this = this;

			let proceed = true;

			if(!window.File && window.FileReader && window.FileList && window.Blob){ //if browser doesn't supports File API
			  alert("Your browser does not support new File API! Please upgrade.");
				proceed = false;
			}else{
			  // let fileSize = input.files[0].size;
			  // let mimeType = input.files[0].type;

			  let reader = new FileReader();

			  reader.onload = function (e) {
			  	$('#'+_this.code+'_image_panel').css('background-image', 'url(' + e.target.result + ')');
			  	$('#'+_this.code+'_image_panel').addClass('display-preview');
			  }

			  this.file = input.files[0];

			  reader.readAsDataURL(input.files[0]);

			}

			this.proceed = proceed;

			if(proceed) {
				$('.shop-cover-edit-button').fadeOut(180);
				$('.shop-profile-image-edit-button').fadeOut(180);

				setTimeout(function(){
					$('.'+_this.code+'-accept-btn').fadeIn(280);
					$('.'+_this.code+'-cancel-btn').fadeIn(280);
				},180);
			}

		}

	}

	removePreview() {

		this.proceed = false;

		$('#'+this.code+'_image_panel').removeClass('display-preview');
		$('#'+this.code+'_image_panel').css('background-image', '');

		$('.'+this.code+'-accept-btn').fadeOut(180);
		$('.'+this.code+'-cancel-btn').fadeOut(180);

		setTimeout(function(){
			$('.shop-cover-edit-button').fadeIn(280);
			$('.shop-profile-image-edit-button').fadeIn(280);
		},180);
		
	}

	createForm() {

		let formData = new FormData();
		formData.append('_token', this.token);  
		formData.append('model', this.target);
		formData.append('model_id', this.id);
		formData.append('imageToken', this.code);
		formData.append('imageType', this.type);
		formData.append('image', this.file);

		return formData;

	}

	uploadImage(input,data) {

		if(!this.proceed) {
			return false;
		}

		let _this = this;

		let request = $.ajax({
	    url: "/upload_profile_image",
	    type: "POST",
	    data: this.createForm(),
	    dataType: 'json',
	    contentType: false,
	    cache: false,
	    processData:false,
	    beforeSend: function( xhr ) {

	    	this.proceed = false;

	    	$('.'+_this.code+'-accept-btn').fadeOut(180);
	    	$('.'+_this.code+'-cancel-btn').fadeOut(180);
	    },
	    mimeType:"multipart/form-data",
	  });

	  request.done(function (response, textStatus, jqXHR){

	  	if(response.success){

	  		this.proceed = false;

	  		const notificationBottom = new NotificationBottom('บันทึกเรียบร้อยแล้ว','','success');
	  		notificationBottom.setDelay(3000);
	  		notificationBottom.load();

	  		$('.shop-cover-edit-button').fadeIn(280);
	  		$('.shop-profile-image-edit-button').fadeIn(280);
	  		

	  	}else{

	  		if(typeof response.message == 'object') {
					const notificationBottom = new NotificationBottom(response.message.title,'',response.message.type);	
					notificationBottom.load();
	  		}

	  	}
	  	
	  });

	  request.fail(function (jqXHR, textStatus, errorThrown){

	    console.error(
	        "The following error occurred: "+
	        textStatus, errorThrown
	    );
	  });

	}

	setOldImageFile(oldImageFile) {
		this.oldImageFile = oldImageFile;
	}

	createInputFile(code) {

		let inputFile = document.createElement('input');
		inputFile.setAttribute('type','file');
		inputFile.setAttribute('id',code);
		inputFile.setAttribute('class','input-profile-image input-file-hide');

		return inputFile

	}

}