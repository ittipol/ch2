class Blackbox {

  constructor() {
    this.mainNavWidth = 280;
  }

  load(){
    this.init();
    this.bind();
    this.setLayout();
  }

  init() {
    this.mainNavWidth = document.getElementById('main_navigation').offsetWidth;
  }

  bind() {

    let _this = this;

    $(window).resize(function() {

      let w = window.innerWidth;
      let h = window.innerHeight;

      $('.main-navigation').css({
        // width: this.mainNavWidth,
        height: h
      });

      $('.main-panel').css({
        width: w,
        height: h
      });

      if(w > 1200) {
        if($('#main_nav_trigger').is(':checked')) {
          $('#main_nav_trigger').trigger('click');
        }

      }

    });

    $('#main_nav_trigger').on('click',function(){

      if($('#filter_panel_trigger').is(':checked')) {
        $('#filter_panel_trigger').trigger('click');
      }

      if($(this).is(':checked')) {
        $('.main-navigation').addClass('is-main-nav-open');
        $('.main-panel').addClass('is-main-nav-open');
        $('.action-bar').addClass('is-main-nav-open');
        $('.main-panel-overlay').addClass('isvisible');
        $('.action-bar-overlay').addClass('isvisible');
      }else{
        $('.main-navigation').removeClass('is-main-nav-open');
        $('.main-panel').removeClass('is-main-nav-open');
        $('.action-bar').removeClass('is-main-nav-open');
        $('.main-panel-overlay').removeClass('isvisible');
        $('.action-bar-overlay').removeClass('isvisible');
      }
    });

    $('.main-panel-overlay').on('click',function(){
      if($('#main_nav_trigger').is(':checked')) {
        $('#main_nav_trigger').trigger('click');
      }
    });

    $('.action-bar-overlay').on('click',function(){
      if($('#main_nav_trigger').is(':checked')) {
        $('#main_nav_trigger').trigger('click');
      }
    });
  }

  setLayout() {
    let w = window.innerWidth;
    let h = window.innerHeight;

    $('.main-navigation').css({
      // width: this.mainNavWidth,
      height: h
    });

    $('.main-panel').css({
      width: w,
      height: h
    });
  }

  loadMainNav() {}

}