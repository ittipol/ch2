class RightSidePanel {

  constructor() {
    this.currentTarget;
  }

  load() {
    this.bind();
  }

  bind() {

    let _this = this;

    $(document).on('click','[data-right-side-panel="1"]',function(e){

      e.preventDefault();

      if(_this.currentTarget) {
        $(_this.currentTarget).removeClass('opened');
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('body').css('overflow-y','auto');

        _this.currentTarget = null;
      }

      _this.currentTarget = $(this).data('right-side-panel-target');

      $(_this.currentTarget).addClass('opened');
      $('.content-wrapper-overlay').addClass('isvisible');
      $('body').css('overflow-y','hidden');

    });

    $('.right-size-panel-close-button').on('click',function(){
      
      if(_this.currentTarget) {
        $(_this.currentTarget).removeClass('opened');
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('body').css('overflow-y','auto');

        _this.currentTarget = null;
      }

    });

    $('.content-wrapper-overlay').on('click',function(){

      if(_this.currentTarget) {
        $(_this.currentTarget).removeClass('opened');
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('body').css('overflow-y','auto');

        _this.currentTarget = null;
      }

    });

  }

}