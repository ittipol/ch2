class Images {
	constructor(panel,type,limit = 5,style = 'default') {
		this.panel = panel;
		this.type = type;
		this.limit = limit;
		this.code = null;
		this.index = 0;
		this.runningNumber = 0;
		this.imagesPlaced = [];
		this.defaultImage = '/images/common/image.svg';
		this.allowedClick = true;
		this.inputDisable = [];
		this.style = style; // default, description
	}

	load(images){

		this.init();
		this.bind();

		if((typeof images != 'undefined') && (images != 'null')) {
			if(typeof images == 'string') {
				this.index = this._createUploader(this.index,JSON.parse(images));
			}else{
				for (let i = 0; i < Object.keys(images).length; i++) {
					this.index = this._createUploader(this.index,images[i]);
				}
			}
		}

		if(this.index < this.limit){
			this.index = this.createUploader(this.index);
		}
	}

	init(){
		let token = new Token();
		this.code = token.generateToken(7);

  	let hidden = document.createElement('input');
    hidden.setAttribute('type','hidden');
    hidden.setAttribute('name','Image['+this.type+'][token]');
    hidden.setAttribute('value',this.code);
    $('#'+this.panel).append(hidden);

	}

	bind(){

		let _this = this;

		$(document).on('click', '.'+this.code+'-image', function(e){

			for (var i = 0; i < _this.inputDisable.length; i++) {
				if(_this.inputDisable[i] == this.id) {
					e.preventDefault();
					return false;
				}
			};

		});

		$(document).on('change', '.'+this.code+'-image', function(){
			_this.preview(this);
		});

		$(document).on('click', '.'+this.code+'-remove-btn', function(){
			_this.removePreview(this);
		});
		
	}

	preview(input){

		if (input.files && input.files[0]) {

			let _this = this;

			let parent = $(input).parent();
			// let CSRF_TOKEN = $('input[name="_token"]').val();
			let proceed = true;

			if(!window.File && window.FileReader && window.FileList && window.Blob){ //if browser doesn't supports File API
			  alert("Your browser does not support new File API! Please upgrade.");
				proceed = false;
			}else{
			  let fileSize = input.files[0].size;
			  let mimeType = input.files[0].type;

			  let reader = new FileReader();

			  reader.onload = function (e) {

			  	parent.find('div.preview-image').css('display','none').css('background-image', 'url(' + e.target.result + ')');

			  	if(_this.checkImageType(mimeType) && _this.checkImageSize(fileSize)) {
			  		parent.css('borderColor','#E0E0E0');
			  		parent.find('.error-message').css('display','none').text('');
			  	}else{
			  		parent.css('borderColor','red');
			  		parent.find('.error-message').css('display','block').text('ไม่รองรับไฟล์นี้');
			  		parent.find('input[type="hidden"]').remove();
			  		parent.find('input').val('');
			  	}

			  }

			  reader.readAsDataURL(input.files[0]);

			  if(!this.checkImageType(mimeType) || !this.checkImageSize(fileSize)) {
			  	proceed = false;
			  }
			}

			if(proceed) {

				let formData = new FormData();
				formData.append('_token', $('input[name="_token"]').val());  
				formData.append('model', $('input[name="_model"]').val());
				formData.append('imageToken', this.code);
				formData.append('imageType', this.type);
				formData.append('image', input.files[0]);

				this.uploadImage(parent,input,formData);
			}

		}

	}

	uploadImage(parent,input,data) {

		let _this = this;
		
		let id = input.getAttribute('id');

		let request = $.ajax({
	    url: "/upload_image",
	    type: "POST",
	    data: data,
	    dataType: 'json',
	    contentType: false,
	    cache: false,
	    processData:false,
	    beforeSend: function( xhr ) {

	    	_this.inputDisable.push(input.id);

	    	$(parent).parent().find('.status').css('width','0%');
	    	parent.parent().find('.progress-bar').css('display','block');
	    },
	    mimeType:"multipart/form-data",
	    xhr: function(){
	    	//upload Progress
	    	let xhr = $.ajaxSettings.xhr();
	    	if (xhr.upload) {
	    		xhr.upload.addEventListener('progress', function(event) {
	    			let percent = 0;
	    			let position = event.loaded || event.position;
	    			let total = event.total;
	    			if (event.lengthComputable) {
	    				percent = Math.ceil(position / total * 100);
	    			}
	    			//update progressbar
	    			parent.parent().find('.status').css('width',percent +'%');
	    		}, true);
	    	}
	    	return xhr;
	    }
	  });

	  request.done(function (response, textStatus, jqXHR){

	  	if(response.success){

	  		parent.find('div.preview-image').fadeIn(450);
	  		parent.find('a').css('display','block');
	  		parent.parent().find('.progress-bar').css('display','none');

	  		let key = parent.prop('id').split('_');

	  		_this.createAddedImage(parent,key[0],key[1],response.filename);

	  		setTimeout(function(){
	  			_this.inputDisable.splice(_this.inputDisable.indexOf(input.id),1);
	  		},350);
	  		
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

	createAddedImage(parent,code,index,filename,description='') {

		let hidden = document.createElement('input');
	  hidden.setAttribute('type','hidden');

	  let hiddenInputName = 'Image['+this.type+'][images]['+index+'][filename]';
	  if((this.type == 'profile-image') || (this.type == 'cover')) {
	  	hiddenInputName = 'Image['+this.type+'][image][filename]';
	  }

	  hidden.setAttribute('name',hiddenInputName);
	  hidden.setAttribute('value',filename);
	  parent.append(hidden);

	  if(this.style == 'description'){
	  	document.getElementById(code+'_textarea_'+index).setAttribute('name','Image['+this.type+'][images]['+index+'][description]');
	  }

	  // if((typeof description == 'string') && (description.length > 0)) {
	  // 	document.getElementById(code+'_textarea_'+index).value = description;
	  // }

		if(this.imagesPlaced.indexOf(parent.prop('id')) < 0){
			this.imagesPlaced.push(parent.prop('id'));

			if(this.index < this.limit){
				this.index = this.createUploader(this.index);
			}
		}

	}


	removePreview(input){

		if(this.allowedClick){

			let _this = this;

			this.allowedClick = false;

			let parent = $(input).parent(); 
			parent.fadeOut(220);  

			--this.index;

			if(this.imagesPlaced.length == this.limit){
				this.index = this.createUploader(this.index);
			}

			this.imagesPlaced.splice(this.imagesPlaced.indexOf($(parent).prop('id')),1);

			if(input.getAttribute('data-id') != null) {
				let key = $(parent).prop('id').split('_');
			 	this.createDeletedImage(parent.parent().parent(),key[1],input.getAttribute('data-id'));
			}

			parent.parent().remove();

			setTimeout(function(){
				_this.allowedClick = true;
			},500);

		}
		
	}

	createDeletedImage(parent,index,value) {
		let hidden = document.createElement('input');
	  hidden.setAttribute('type','hidden');
	  hidden.setAttribute('name','Image['+this.type+'][delete]['+index+']');
	  hidden.setAttribute('value',value);
	  parent.append(hidden);
	}

	createUploader(index){

		let html = '';
		html += '<div class="image-panel '+this.style+' clearfix">';
		html += '<label id="'+this.code+'_'+this.runningNumber+'" class="image-label">';
		html += '<input id="'+this.code+'_image_'+this.runningNumber+'" class="'+this.code+'-image" type="file">';
		html +=	'<div class="preview-image" style="background-image:url('+this.defaultImage+')"></div>';
		html += '<a href="javscript:void(0);" class="'+this.code+'-remove-btn">×</a>'
		html += '<p class="error-message"></p>';
		html += '<div class="progress-bar"><div class="status"></div></div>'
		html += '</label>';
		if(this.style == 'description'){
			html += '<textarea id="'+this.code+'_textarea_'+this.runningNumber+'" placeholder="คำอธิบายเี่ยวกับรูปภาพนี้"></textarea>';
		}
		html += '</div>';

		++this.runningNumber;
		$('#'+this.panel).append(html);

		return ++index;

	}

	_createUploader(index,image){

		this.imagesPlaced.push(this.code+'_'+this.runningNumber);

		let html = '';
		html += '<div class="image-panel '+this.style+' clearfix">';
		html += '<input type="hidden" name="Image['+this.type+'][images]['+index+'][id]" value="'+image.id+'" >';
		html += '<label id="'+this.code+'_'+this.runningNumber+'" class="image-label">';
		html += '<input id="'+this.code+'_image_'+this.runningNumber+'" class="'+this.code+'-image" type="file">';
		html +=	'<div class="preview-image" style="background-image:url('+image._url+')"></div>';
		html += '<a href="javscript:void(0);" class="'+this.code+'-remove-btn" data-id="'+image.id+'" style="display:block;">×</a>';
		html += '<p class="error-message"></p>';
		html += '<div class="progress-bar"><div class="status"></div></div>'
		html += '</label>';
		if(this.style == 'description'){
			html += '<textarea id="'+this.code+'_textarea_'+this.runningNumber+'" name="Image['+this.type+'][images]['+index+'][description]" placeholder="คำอธิบายเี่ยวกับรูปภาพนี้">'+image.description+'</textarea>';
		}
		html += '</div>';

		++this.runningNumber;
		$('#'+this.panel).append(html);

		return ++index;

	}

	checkImageType(type){
		let allowedFileTypes = ['image/jpg','image/jpeg','image/png', 'image/pjpeg'];

		let allowed = false;

		for (let i = 0; i < allowedFileTypes.length; i++) {
			if(type == allowedFileTypes[i]){
				allowed = true;
				break;						
			}
		};

		return allowed;
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
