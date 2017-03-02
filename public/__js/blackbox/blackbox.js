class Blackbox {

  constructor() {
    // this.mainNavWidth = 280;
  }

  load(){
    this.init();
    this.bind();
    this.setLayout();
  }

  init() {
    // this.mainNavWidth = document.getElementById('global_navigation').offsetWidth;
  }

  bind() {

    let _this = this;

    $(window).resize(function() {

      let w = window.innerWidth;
      // let h = window.innerHeight;

      // $('.global-nav').css({
      //   height: h
      // });

      // $('.content-wrapper').css({
      //   width: w,
      //   height: h
      // });

      if(w > 1200) {
        if($('#global_nav_trigger').is(':checked')) {
          $('#global_nav_trigger').trigger('click');
        }

      }

    });

    $('#global_nav_trigger').on('click',function(){

      if($(this).is(':checked')) {
        $('.global-nav').addClass('is-main-nav-open');
        $('#global_header').addClass('is-main-nav-open');
        $('.content-wrapper').addClass('is-main-nav-open');
        $('.content-wrapper-overlay').addClass('isvisible');
        $('.shop-top-fixed-top').addClass('is-main-nav-open');
        $('body').css('overflow-y','hidden');
        $('#container').css('overflow-y','hidden');
      }else{
        $('.global-nav').removeClass('is-main-nav-open');
        $('#global_header').removeClass('is-main-nav-open');
        $('.content-wrapper').removeClass('is-main-nav-open');
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('.shop-top-fixed-top').removeClass('is-main-nav-open');
        $('body').css('overflow-y','auto');
        $('#container').css('overflow-y','auto');
      }
    });

    $('#search_panel_trigger').on('click',function(){

      if($(this).is(':checked')) {

        $('#global_search_query_input').focus();

        $('.global-search-panel').addClass('panel-opened');
        $('.content-wrapper-overlay').addClass('isvisible');
        $('body').css('overflow-y','hidden');
        $('#container').css('overflow','auto');
      }else{

        $('#global_search_query_input').blur().val('');

        $('.global-search-panel').removeClass('panel-opened');
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('body').css('overflow-y','auto');
        $('#container').css('overflow','auto');
      }

    });

    $('#cart_panel_trigger').on('click',function(){

      if($(this).is(':checked')) {
        $('.global-cart-panel').addClass('panel-opened');
        $('.content-wrapper-overlay').addClass('isvisible');
        $('body').css('overflow-y','hidden');
        $('#container').css('overflow','auto');
      }else{
        $('.global-cart-panel').removeClass('panel-opened');
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('body').css('overflow-y','auto');
        $('#container').css('overflow','auto');
      }
      
    });

    $('.content-wrapper-overlay').on('click',function(){
      if($('#global_nav_trigger').is(':checked')) {
        $('#global_nav_trigger').trigger('click');
      }

      if($('#search_panel_trigger').is(':checked')) {
        $('#search_panel_trigger').trigger('click');
      }

      if($('#cart_panel_trigger').is(':checked')) {
        $('#cart_panel_trigger').trigger('click');
      }
    });

    $('.search-panel-close-button').on('click',function(){
      if($('#search_panel_trigger').is(':checked')) {
        $('#search_panel_trigger').trigger('click');
      }
    });

    $('.cart-panel-close-button').on('click',function(){
      if($('#cart_panel_trigger').is(':checked')) {
        $('#cart_panel_trigger').trigger('click');
      }
    });

    $(document).scroll(function() {
      // console.log($(this).scrollTop());
    });


  }

  setLayout() {
    
    // let w = window.innerWidth;
    // let h = window.innerHeight;

    // $('.global-nav').css({
    //   height: h
    // });

    // $('.content-wrapper').css({
    //   width: w,
    //   height: h
    // });

    // $('#container').css({
    //   width: (w-17),
    //   height: h
    // });

  }

  loadMainNav() {}

}