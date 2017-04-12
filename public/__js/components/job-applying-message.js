class JobApplyingMessage {

  constructor(panel,attachedFileObj) {
    this.panel = panel;
    this.panelOpened = false;
    this.hasUploaded = false;
    this.attachedFileObj = attachedFileObj;
    this.replyMessage = null;
    this.formObj;
  }

  load() {
    this.init();
    this.bind();
  }

  init() {
    this.formObj = $(this.attachedFileObj.form);
  }

  bind() {

    let _this = this;

    $(document).on('click','[data-right-side-panel-target="'+_this.panel+'"]',function(e){

      if(typeof $(this).data('reply-message') === 'number') {
        _this.replyMessage = $(this).data('reply-message');
      }

      _this.panelOpened = true;
    });

    $(_this.panel).on('click','.right-size-panel-close-button',function(){
      
      if(_this.panelOpened) {
        setTimeout(_this.reset(),150);
      } 

    });

    $('.content-wrapper-overlay').on('click',function(){

      if(_this.panelOpened) {
        setTimeout(_this.reset(),150);
      }
      
    });

    $('#'+_this.attachedFileObj.panel).on('change', '.attached-file-input', function(){
      _this.hasUploaded = true
    });

    _this.formObj.on('submit',function(){

      if(_this.formObj.find('.message-input').val() == '') {
        _this.formObj.find('.message-input-error-message').css('display','block');
        return false;
      }

      if(typeof _this.replyMessage === 'number') {

        let hidden = document.createElement('input');
        hidden.setAttribute('type','hidden');
        hidden.setAttribute('name','id');
        hidden.setAttribute('value',_this.replyMessage);
        _this.formObj.append(hidden);

      }

      _this.formObj.find('input[type="submit"]').prop('disabled','disabled').addClass('disabled');

    });   

  }

  clearAttachedFile() {

    let _this = this;

    let formData = new FormData();
    formData.append('_token', this.formObj.find('input[name="_token"]').val());  
    formData.append('model', this.formObj.find('input[name="_model"]').val());
    formData.append('token', this.attachedFileObj.code);

    this.clearAttachedFileItem();

    let request = $.ajax({
      url: "/clear_file_attachment",
      type: "POST",
      data: formData,
      dataType: 'json',
      contentType: false,
      cache: false,
      processData:false,
      // beforeSend: function( xhr ) {},
      mimeType:"multipart/form-data",
    });

    request.done(function (response, textStatus, jqXHR){
      if(response.success) {
        _this.hasUploaded = false;
      }
    });

  }

  reset() {
    this.replyMessage = null;
    this.panelOpened = false;

    this.formObj.find('.message-input-error-message').css('display','none');

    this.formObj.find('input[type="submit"]').prop('disabled',false).removeClass('disabled');
  
    this.formObj.find('.message-input').val('');

    if(this.hasUploaded) {
      this.clearAttachedFile();
    }
  }

  clearAttachedFileItem() {
    this.attachedFileObj.clear();
  }

}