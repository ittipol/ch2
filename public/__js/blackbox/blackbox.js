class Blackbox {

  constructor() {}

  load(){
    // this.init();
    this.bind();
    // this.setLayout();
  }

  init() {}

  bind() {

    let _this = this;

    $(window).resize(function() {

      let w = window.innerWidth;
      // let h = window.innerHeight;

      if(w > 1200) {
        if($('#global_nav_trigger').is(':checked')) {
          $('#global_nav_trigger').trigger('click');
        }

      }

    });

    $('#global_nav_trigger').on('click',function(){

      if($(this).is(':checked')) {
        $('.global-nav').addClass('is-main-nav-open');
        // $('#global_header').addClass('is-main-nav-open');
        // $('.shop-top-fixed-top').addClass('is-main-nav-open');
        // $('#content_wrapper').addClass('is-main-nav-open');

        $('.content-wrapper-overlay').addClass('isvisible');
        $('body').css('overflow-y','hidden');
        // $('#container').css('overflow-y','hidden');
        // $('#container').css('overflow-x','hidden');
      }else{
        $('.global-nav').removeClass('is-main-nav-open');
        // $('#global_header').removeClass('is-main-nav-open');
        // $('.shop-top-fixed-top').removeClass('is-main-nav-open');
        // $('#content_wrapper').removeClass('is-main-nav-open');
        
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('body').css('overflow-y','auto');
        // $('#container').css('overflow-y','visible');
        // $('#container').css('overflow-x','visible');
      }
    });

    $('#search_panel_trigger').on('click',function(){

      if($(this).is(':checked')) {

        $('#global_search_query_input').focus();

        $('.global-search-panel').addClass('panel-opened');
        $('.content-wrapper-overlay').addClass('isvisible');
        $('body').css('overflow-y','hidden');
      }else{

        $('#global_search_query_input').blur().val('');

        $('.global-search-panel').removeClass('panel-opened');
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('body').css('overflow-y','auto');
      }

    });

    $('#cart_panel_trigger').on('click',function(){

      if($(this).is(':checked')) {
        $('.global-cart-panel').addClass('panel-opened');
        $('.content-wrapper-overlay').addClass('isvisible');
        $('body').css('overflow-y','hidden');
      }else{
        $('.global-cart-panel').removeClass('panel-opened');
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('body').css('overflow-y','auto');
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

    // $(document).scroll(function() {
    //   // console.log($(this).scrollTop());
    // });

  }

}