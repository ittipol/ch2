var FloatingMenu = {
  displayed: false,
  allowed: true
}

FloatingMenu.load = function() {
  FloatingMenu.bind(); 
}

FloatingMenu.bind = function() {
  $('.floating-button').on('click',function(){

    if(!FloatingMenu.displayed && FloatingMenu.allowed){

      FloatingMenu.allowed = false;

      var top = $(this).position().top - $('.overlay-menu').height() + $(this).height();
      var left = $(this).position().left - $('.overlay-menu').width() + $(this).width();

      $('.overlay-menu').css({
        top:top, 
        left:left,
        opacity:0,
        display:'block'
      }).animate({
        top:top-($(this).height()/1.4), 
        left:left-($(this).width()/1.7),
        opacity:1
      },300,'swing'); 

      setTimeout(function(){
        FloatingMenu.allowed = true;
        FloatingMenu.displayed = true;
      },350);

    }

  });

  $(document).on('click',function(){
    if(FloatingMenu.displayed){

      FloatingMenu.allowed = false;

      $('.overlay-menu').fadeOut(220);

      setTimeout(function(){
        FloatingMenu.allowed = true;
        FloatingMenu.displayed = false;
      },350);
    }
  });
}