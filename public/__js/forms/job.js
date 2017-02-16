class Job {

  constructor() {
    this.txtRecruitmentDetail = $('textarea[name="recruitment_custom_detail"]');
  }

  load() {
    this.bind();
    
    if($('#recruitment_custom').is(':checked')) {
      $('textarea[name="recruitment_custom_detail"]').prop('disabled',false);
    }else{
      $('textarea[name="recruitment_custom_detail"]').prop('disabled',true);
    }

  }

  bind() {
    $('#recruitment_custom').on('click',function(){
      if($(this).is(':checked')) {
        CKEDITOR.instances['recruitment_custom_detail'].setReadOnly(false);
      }else{
        CKEDITOR.instances['recruitment_custom_detail'].setReadOnly(true);
        CKEDITOR.instances['recruitment_custom_detail'].setData('');
      }
    });
  }

}