class Common {

  constructor() {}

  load(){
    this.bind();
  }

  bind() {

    let _this = this;

    $(window).resize(function() {

      let w = window.innerWidth;

      if(w > 1200) {
        if($('#global_nav_trigger').is(':checked')) {
          $('#global_nav_trigger').trigger('click');
        }

      }

    });

    $('#global_nav_trigger').on('click',function(){

      if($(this).is(':checked')) {
        $('.global-nav').addClass('is-main-nav-open');
        $('.content-wrapper-overlay').addClass('isvisible');
        $('body').css('overflow-y','hidden');
      }else{
        $('.global-nav').removeClass('is-main-nav-open');
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('body').css('overflow-y','auto');
      }
    });

    $('#account_panel_trigger').on('click',function(){

      if($(this).is(':checked')) {

        $('.global-account-panel').addClass('panel-opened');
        $('.content-wrapper-overlay').addClass('isvisible');
        $('body').css('overflow-y','hidden');
      }else{

        $('.global-account-panel').removeClass('panel-opened');
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('body').css('overflow-y','auto');
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
    
    $('#notification_panel_trigger').on('click',function(){

      if($(this).is(':checked')) {
        $('.global-notification-panel').addClass('panel-opened');
        $('.content-wrapper-overlay').addClass('isvisible');
        $('body').css('overflow-y','hidden');
      }else{
        $('.global-notification-panel').removeClass('panel-opened');
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('body').css('overflow-y','auto');
      }

    });

    // Overlay screen
    $('.content-wrapper-overlay').on('click',function(){

      if($('#global_nav_trigger').is(':checked')) {
        $('#global_nav_trigger').trigger('click');
      }else if($('#search_panel_trigger').is(':checked')) {
        $('#search_panel_trigger').trigger('click');
      }else if($('#cart_panel_trigger').is(':checked')) {
        $('#cart_panel_trigger').trigger('click');
      }else if($('#notification_panel_trigger').is(':checked')) {
        $('#notification_panel_trigger').trigger('click');
      }else if($('#account_panel_trigger').is(':checked')) {
        $('#account_panel_trigger').trigger('click');
      }

    });

    $('.account-panel-close-button').on('click',function(){
      if($('#account_panel_trigger').is(':checked')) {
        $('#account_panel_trigger').trigger('click');
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

    $('.notification-panel-close-button').on('click',function(){
      if($('#notification_panel_trigger').is(':checked')) {
        $('#notification_panel_trigger').trigger('click');
      }
    });

    $(document).scroll(function() {
      // $(this).scrollTop();
      if($(this).scrollTop() == 0) {
        $('.header-fixed-top').removeClass('header-shadow');
        $('.sub-header-nav-fixed-top').removeClass('sub-header-shadow');
      }else{
        $('.header-fixed-top').addClass('header-shadow');
        $('.sub-header-nav-fixed-top').addClass('sub-header-shadow');
      }
    });

  }

}