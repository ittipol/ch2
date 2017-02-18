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

      // $('.content-wrapper').css({
      //   width: w,
      //   height: h
      // });

      if(w > 1200) {
        if($('#main_nav_trigger').is(':checked')) {
          $('#main_nav_trigger').trigger('click');
        }

      }

    });

    $('#main_nav_trigger').on('click',function(){

      // if($('#filter_panel_trigger').is(':checked')) {
      //   $('#filter_panel_trigger').trigger('click');
      // }

      if($(this).is(':checked')) {
        $('.main-navigation').addClass('is-main-nav-open');
        $('.content-wrapper').addClass('is-main-nav-open');
        $('.action-bar').addClass('is-main-nav-open');
        $('.content-wrapper-overlay').addClass('isvisible');
        $('.action-bar-overlay').addClass('isvisible');
        $('body').css('overflow-y','hidden');
      }else{
        $('.main-navigation').removeClass('is-main-nav-open');
        $('.content-wrapper').removeClass('is-main-nav-open');
        $('.action-bar').removeClass('is-main-nav-open');
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('.action-bar-overlay').removeClass('isvisible');
        $('body').css('overflow-y','auto');
      }
    });

    $('.content-wrapper-overlay').on('click',function(){
      if($('#main_nav_trigger').is(':checked')) {
        $('#main_nav_trigger').trigger('click');
      }
    });

    $('.action-bar-overlay').on('click',function(){
      if($('#main_nav_trigger').is(':checked')) {
        $('#main_nav_trigger').trigger('click');
      }
    });

    $(document).scroll(function() {
    // console.log($(this).scrollTop());
      
        if($(this).scrollTop() > 70) {
          $('.header-top').css('display','none');
          $('.header-fix').css('display','block');
        }else{
          $('.header-top').css('display','block');
          $('.header-fix').css('display','none');
        }

        // if($(this).scrollTop() > 120) {
        //   $('.header-wrapper').addClass('active');
        // }else{
        //   $('.header-wrapper').removeClass('active');
        // }
    });


  }

  setLayout() {
    let w = window.innerWidth;
    let h = window.innerHeight;

    $('.main-navigation').css({
      // width: this.mainNavWidth,
      height: h
    });

    // $('.content-wrapper').css({
    //   width: w,
    //   height: h
    // });

  }

  loadMainNav() {}

}