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

			$(document).on('click', '.'+this.code+'-remove-btn', function(){
				_this.deleteImage();
			});

	}

	setElem(buttonId,PanelId,acceptBtn,cancelBtn,removeBtn) {

		document.getElementById(PanelId).setAttribute('id',this.code+'_image_panel');

		document.getElementById(buttonId).append(this.createInputFile(this.code));
		document.getElementById(buttonId).setAttribute('id',this.code+'_image_button');

		let _class = document.getElementById(acceptBtn).getAttribute('class');
		document.getElementById(acceptBtn).setAttribute('class',_class+' '+this.code+'-accept-btn');
		document.getElementById(acceptBtn).setAttribute('id',this.code+'_accept_button');

		_class = document.getElementById(cancelBtn).getAttribute('class');
		document.getElementById(cancelBtn).setAttribute('class',_class+' '+this.code+'-cancel-btn');
		document.getElementById(cancelBtn).setAttribute('id',this.code+'_cancel_button');

		_class = document.getElementById(removeBtn).getAttribute('class');
		document.getElementById(removeBtn).setAttribute('class',_class+' '+this.code+'-remove-btn');
		document.getElementById(removeBtn).setAttribute('id',this.code+'_remove_button');


	}

	preview(input){

		if (input.files && input.files[0]) {

			let _this = this;

			let proceed = true;

			if(!window.File && window.FileReader && window.FileList && window.Blob){ //if browser doesn't supports File API
			  alert("Your browser does not support new File API! Please upgrade.");
				proceed = false;
			}else{
			  let fileSize = input.files[0].size;
			  // let mimeType = input.files[0].type;

			  let reader = new FileReader();

			  reader.onload = function (e) {

			  	if(_this.checkImageSize(fileSize)) {
			  		$('#'+_this.code+'_image_panel').css('background-image', 'url(' + e.target.result + ')');
			  		$('#'+_this.code+'_image_panel').addClass('display-preview');
			  	}else{

			  		const notificationBottom = new NotificationBottom();
			  		notificationBottom.setTitle('ไม่รองรับรูปภาพที่กำลังอัพโหลด');
			  		notificationBottom.setDesc('รูปภาพอาจจะมีขนาดใหญ่กว่าที่รองรับ');
			  		notificationBottom.setType('error');
			  		notificationBottom.setDelay(10000);
			  		notificationBottom.display();

			  		_this.removePreview();
			  	}

			  	
			  }

			  this.file = input.files[0];

			  reader.readAsDataURL(input.files[0]);

			  if(!this.checkImageSize(fileSize)) {
			  	proceed = false;
			  }

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

	createDeleteImageForm() {
		let formData = new FormData();
		formData.append('_token', this.token);  
		formData.append('model', this.target);
		formData.append('model_id', this.id);
		formData.append('imageToken', this.code);
		formData.append('imageType', this.type);
		return formData;
	}

	uploadImage() {

		if(!this.proceed) {
			return false;
		}

		this.proceed = false;

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
	    	$('.'+_this.code+'-accept-btn').fadeOut(180);
	    	$('.'+_this.code+'-cancel-btn').fadeOut(180);
	    },
	    mimeType:"multipart/form-data",
	  });

	  request.done(function (response, textStatus, jqXHR){

	  	if(response.success){

	  		const notificationBottom = new NotificationBottom();
	  		notificationBottom.setTitle('บันทึกเรียบร้อยแล้ว');
	  		notificationBottom.setType('success');
	  		notificationBottom.setDelay(3000);
	  		notificationBottom.display();

	  		$('.shop-cover-edit-button').fadeIn(280);
	  		$('.shop-profile-image-edit-button').fadeIn(280);
	  		

	  	}else{

	  		if(typeof response.message == 'object') {
					const notificationBottom = new NotificationBottom();	
					notificationBottom.setTitle(response.message.title);
	  			notificationBottom.setType(response.message.type);
					notificationBottom.display();
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

	deleteImage() {

		if(this.proceed) {
			return false;
		}

		this.proceed = false;

		let _this = this;

		let request = $.ajax({
	    url: "/delete_profile_image",
	    type: "POST",
	    data: this.createDeleteImageForm(),
	    dataType: 'json',
	    contentType: false,
	    cache: false,
	    processData:false,
	    beforeSend: function( xhr ) {},
	    mimeType:"multipart/form-data",
	  });

	  request.done(function (response, textStatus, jqXHR){

	  	if(response.success){

	  		$('#'+_this.code+'_image_panel').removeClass('display-preview');
	  		$('#'+_this.code+'_image_panel').css('background-image', '');

	  		const notificationBottom = new NotificationBottom();
	  		notificationBottom.setTitle('รูปภาพถูกลบแล้ว');
	  		notificationBottom.setType('success');
	  		notificationBottom.setDelay(5000);
	  		notificationBottom.display();

	  	}else{

	  		if(typeof response.message == 'object') {
					const notificationBottom = new NotificationBottom(response.message.title,'',response.message.type);	
					notificationBottom.setTitle(response.message.title);
					notificationBottom.setType(response.message.type);
					notificationBottom.display();
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

	checkImageSize(size) {
		// 2MB
		let maxSize = 2097152;

		let allowed = false;

		if(size <= maxSize){
			allowed = true;
		}

		return allowed;
	}

}