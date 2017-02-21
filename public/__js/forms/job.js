class Job {

  constructor() {
    this.txtRecruitmentDetail = $('textarea[name="recruitment_custom_detail"]');
  }

  load() {
    this.bind();
    
    if($('#recruitment_custom').is(':checked')) {console.log('xxx');
      $('textarea[name="recruitment_custom_detail"]').prop('disabled',false);
    }else{console.log('yyy');
      $('textarea[name="recruitment_custom_detail"]').prop('disabled',true);
    }

  }

  bind() {
    $('#recruitment_custom').on('click',function(){
      if($(this).is(':checked')) {
        CKEDITOR.instances['recruitment_custom_detail'].setReadOnly(false);
        $('textarea[name="recruitment_custom_detail"]').prop('disabled',false);
      }else{
        CKEDITOR.instances['recruitment_custom_detail'].setReadOnly(true);
        CKEDITOR.instances['recruitment_custom_detail'].setData('');
        $('textarea[name="recruitment_custom_detail"]').prop('disabled',true).val('');
      }
    });
  }

}