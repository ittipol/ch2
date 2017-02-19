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
    this.mainNavWidth = document.getElementById('global_navigation').offsetWidth;
  }

  bind() {

    let _this = this;

    $(window).resize(function() {

      let w = window.innerWidth;
      let h = window.innerHeight;

      // $('.global-nav').css({
      //   // width: this.mainNavWidth,
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
        $('.content-wrapper').addClass('is-main-nav-open');
        $('.content-wrapper-overlay').addClass('isvisible');
        $('body').css('overflow-y','hidden');
      }else{
        $('.global-nav').removeClass('is-main-nav-open');
        $('.content-wrapper').removeClass('is-main-nav-open');
        $('.content-wrapper-overlay').removeClass('isvisible');
        $('body').css('overflow-y','auto');
      }
    });

    $('#search_panel_trigger').on('click',function(){
      if($(this).is(':checked')) {

        $('#global_search_query_input').focus();

        $('.global-search-panel').addClass('panel-open');
        $('.content-wrapper-overlay').addClass('isvisible');
        $('body').css('overflow-y','hidden');
      }else{

        $('#global_search_query_input').blur().val('');

        $('.global-search-panel').removeClass('panel-open');
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
    });

    $('.search-panel-close-button').on('click',function(){
      if($('#search_panel_trigger').is(':checked')) {
        $('#search_panel_trigger').trigger('click');
      }
    });

    // $('.action-bar-overlay').on('click',function(){
    //   if($('#global_nav_trigger').is(':checked')) {
    //     $('#global_nav_trigger').trigger('click');
    //   }
    // });

    // $(document).scroll(function() {
    // // console.log($(this).scrollTop());
    // });


  }

  setLayout() {
    let w = window.innerWidth;
    let h = window.innerHeight;

    // $('.global-nav').css({
    //   // width: this.mainNavWidth,
    //   height: h
    // });

    // $('.content-wrapper').css({
    //   width: w,
    //   height: h
    // });

  }

  loadMainNav() {}

}