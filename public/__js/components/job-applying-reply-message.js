class JobApplyingReplyMessage {

  constructor(panel,attachedFileObj) {
    this.panel = panel;
    this.panelOpened = false;
    this.hasUploaded = false;
    this.attachedFileObj = attachedFileObj;
    this.replyMessage = null;
  }

  load() {
    this.bind();
  }

  bind() {

    let _this = this;

    $(document).on('click','[data-right-side-panel-target="'+_this.panel+'"]',function(e){
      _this.replyMessage = $(this).data('reply-message');
      _this.panelOpened = true;
    });

    $(_this.panel).on('click','.right-size-panel-close-button',function(){
      
      if(_this.panelOpened) {
        _this.replyMessage = null;
        _this.panelOpened = false;

        $(_this.attachedFileObj.form).find('textarea').val('');
        
        setTimeout(function(){
          CKEDITOR.instances.message.setData('');
          if(_this.hasUploaded) {
            _this.clearAttachedFile();
          }
        },220);
      }

    });

    $('.content-wrapper-overlay').on('click',function(){

      if(_this.panelOpened) {
        _this.replyMessage = null;
        _this.panelOpened = false;

        $(_this.attachedFileObj.form).find('textarea').val('');

        setTimeout(function(){
          CKEDITOR.instances.message.setData('');
          if(_this.hasUploaded) {
            _this.clearAttachedFile();
          }
        },220);
      }
      
    });

    $('#'+_this.attachedFileObj.panel).on('change', '.attached-file-input', function(){
      _this.hasUploaded = true
    });      

    $(_this.attachedFileObj.form).on('submit',function(){

      if(CKEDITOR.instances.message.getData() == '') {
        $(_this.attachedFileObj.form).find('.message-input-error-message').css('display','block');
        return false;
      }

      if(typeof _this.replyMessage !== 'number') {
        return false;
      }

      let hidden = document.createElement('input');
      hidden.setAttribute('type','hidden');
      hidden.setAttribute('name','id');
      hidden.setAttribute('value',_this.replyMessage);
      $(_this.attachedFileObj.form).append(hidden);

    });

  }

  clearAttachedFile() {

    let _this = this;

    let formData = new FormData();
    formData.append('_token', $(this.attachedFileObj.form).find('input[name="_token"]').val());  
    formData.append('model', $(this.attachedFileObj.form).find('input[name="_model"]').val());
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

  clearAttachedFileItem() {
    this.attachedFileObj.clear();
  }

}