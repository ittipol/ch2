class AttachedFile {

	constructor(panel,limit = 5) {
		this.panel = panel;
		// this.type = type;
		this.limit = limit;
		this.code = null;
		this.index = 0;
		this.runningNumber = 0;
		this.imagesPlaced = [];
		this.defaultImage = '/images/common/image.svg';
		this.allowedClick = true;
		this.inputDisable = [];
	}

	load() {

		this.init();

		this.createAddButtonAndPanel();

		// if(this.index < this.limit){
		// 	this.index = this.createUploader(this.index);
		// }

		this.bind();

	}

	init(){
		let token = new Token();
		this.code = token.generateToken(7);

  	let hidden = document.createElement('input');
    hidden.setAttribute('type','hidden');
    hidden.setAttribute('name','AttachedFile[token]');
    hidden.setAttribute('value',this.code);
    $('#'+this.panel).append(hidden);
	}

	bind() {

		let _this = this;

		$(document).on('change', '.'+this.code+'-attached-file-input', function(){
			_this.preview(this);
		});

		$(document).on('click','.attached-file-cancel-button',function(){
		
			$(this).parent().remove();
			_this.index--;

		});

	}

	preview(input){

		if (input.files && input.files[0]) {

				// let _this = this;
				// let parent = $(input).parent();

				if(!window.File && window.FileReader && window.FileList && window.Blob){ //if browser doesn't supports File API
				  alert("Your browser does not support new File API! Please upgrade.");
					return false;
				}else{
				  let fileSize = input.files[0].size;
				  let mimeType = input.files[0].type;
				  let filename = input.value.replace(/^.*[\\\/]/, '');

				  if(!this.checkImageType(mimeType) || !this.checkFileSize(fileSize)) {
				  	$('#'+this.code+'_attached_file_error').css('display','block').text('ไม่รองรับไฟล์นี้');
				  }else{

				  	$('#'+this.code+'_attached_file_error').css('display','none');

				  	let formData = new FormData();
				  	formData.append('_token', $('input[name="_token"]').val());  
				  	formData.append('model', $('input[name="_model"]').val());
				  	formData.append('token', this.code);
				  	formData.append('file', input.files[0]);

				  	this.uploadFile(input,formData,filename,fileSize);

				  }

				}

		}

	}

	uploadFile(input,data,filename,fileSize) {

		$('#'+this.code+'_attached_file_input').val('').prop('disabled',true);

		let _this = this;

		let itemId = _this.createAttachedFileItem(this.truncString(filename,20),this.bytesToSize(fileSize));
		let obj = $('#'+itemId);

		let request = $.ajax({
	    url: "/upload_attached_file",
	    type: "POST",
	    data: data,
	    dataType: 'json',
	    contentType: false,
	    cache: false,
	    processData:false,
	    beforeSend: function( xhr ) {
	    	$('#main_form input[type="submit"]').prop('disabled','disabled').addClass('disabled');  
	    	obj.find('.progress-bar').css('display','block');
	    },
	    mimeType:"multipart/form-data",
	    xhr: function(){
	
	    	let xhr = $.ajaxSettings.xhr();
	    	if (xhr.upload) {
	    		xhr.upload.addEventListener('progress', function(event) {
	    			let percent = 0;
	    			let position = event.loaded || event.position;
	    			let total = event.total;
	    			if (event.lengthComputable) {
	    				percent = Math.ceil(position / total * 100);
	    			}

	    			obj.find('.status').css('width',percent +'%');
	    		}, true);
	    	}
	    	return xhr;
	    }
	  });

    request.done(function (response, textStatus, jqXHR){

    	$('#'+_this.code+'_attached_file_input').prop('disabled',false);

    	if(response.success){

    		obj.find('.progress-bar').css('display','none');
    		obj.find('img').prop('src','/images/icons/document-white.png');
    		obj.find('.attached-file-cancel-button').css('display','block');

    		_this.createAttachedFile(obj,response.filename);

    		$('#main_form input[type="submit"]').prop('disabled',false).removeClass('disabled');

    	}else{

    		if(typeof response.message == 'object') {
  				const notificationBottom = new NotificationBottom('เกิดข้อผิดพลาด','','error');
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

	createAttachedFile(obj,filename) {

		let hidden = document.createElement('input');
		hidden.setAttribute('type','hidden');
		hidden.setAttribute('name','AttachedFile[files][][filename]');
		hidden.setAttribute('value',filename);
		obj.append(hidden);
	}

	createAddButtonAndPanel() {

		let html = '';
		html += '<label class="attached-file-box clearfix">';
		html += '<input id="'+this.code+'_attached_file_input" class="'+this.code+'-attached-file-input" type="file">';
		html += '<div class="attached-file-image">';
		html += '<img src="/images/icons/plus-white.png">';
		html += '</div>';
		html += '<div class="attached-file-info">';
		html += '<div class="attached-file-text">เพิ่มไฟล์</div>';
		html += '</div>';
		html += '</label>';

		html += '<div id="'+this.code+'_attached_file_error" class="attached-file-error"></div>';

		html += '<div class="line grey space-top-bottom-10"></div>';

		html += '<div id="'+this.code+'_attached_file_panel clearfix"></div>';

		$('#'+this.panel).append(html);

	}

	createAttachedFileItem(filename,filesize) {

		let itemId = this.code+'_attached_file_item_'+this.runningNumber;

		let html = '';
		html += '<div id="'+itemId+'" class="attached-file-box clearfix">';
		html += '<div class="attached-file-image">';
		html += '<img src="/images/icons/upload-white.png">';
		html += '</div>';
		html += '<div class="attached-file-info">';
		html += '<div class="attached-file-title">';
		html += '<h4>'+filename+'</h4>'
		html += '<h5>'+filesize+'</h5>'
		html += '</div>';
		html += '</div>';
		html += '<div class="progress-bar"><div class="status"></div></div>';
		html += '<div class="attached-file-cancel-button"></div>';
		html += '</div>';

		++this.index;
		++this.runningNumber;

		$('#'+this.code+'_attached_file_panel').append(html);

		return itemId;


	}

	checkImageType(type){
		let acceptedFileTypes = [
		'image/jpg',
		'image/jpeg',
		'image/png', 
		'image/pjpeg',
		'application/pdf',
		'text/plain',
		// Microsoft Office MIME types for HTTP Content Streaming
		'application/msword',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		// 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
		// 'application/vnd.ms-word.document.macroEnabled.12',
		// 'application/vnd.ms-word.template.macroEnabled.12',
		'application/vnd.ms-excel',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		// 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
		// 'application/vnd.ms-excel.sheet.macroEnabled.12',
		// 'application/vnd.ms-excel.template.macroEnabled.12',
		// 'application/vnd.ms-excel.addin.macroEnabled.12',
		// 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
		'application/vnd.ms-powerpoint',
		'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		// 'application/vnd.openxmlformats-officedocument.presentationml.template',
		// 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		// 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
		// 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
		// 'application/vnd.ms-powerpoint.template.macroEnabled.12',
		// 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
		// 'application/vnd.ms-access',
		];

		let allowed = false;

		for (let i = 0; i < acceptedFileTypes.length; i++) {
			if(type == acceptedFileTypes[i]){
				allowed = true;
				break;						
			}
		};

		return allowed;
	}

	checkFileSize(size) {
		// 2MB
		let maxSize = 2097152;

		// let allowed = false;

		if(size <= maxSize){
			return true;
		}

		return false;
	}

	truncString(string,limit) {

		if (string.length <= limit) {
			return string;
		}

		let subString = string.substr(0, limit-1);

		return subString+'...';

	}

	bytesToSize(bytes) {
   let sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Byte';
   let i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
	};

}