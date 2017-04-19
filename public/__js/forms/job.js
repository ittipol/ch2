class Job {

  constructor() {
    this.txtRecruitmentDetail = $('textarea[name="recruitment_custom_detail"]');
  }

  load() {
    this.bind();
    
    if($('#recruitment_custom').is(':checked')) {
      $('.recruitment-custom-input').prop('disabled',false);
    }else{
      $('.recruitment-custom-input').prop('disabled',true);
    }

  }

  bind() {
    $('#recruitment_custom').on('click',function(){
      if($(this).is(':checked')) {
        $('.recruitment-custom-input').prop('disabled',false);
      }else{
        $('.recruitment-custom-input').prop('disabled',true).val('');
      }
    });
  }

}