class NotificationBottom {
  
  constructor(title = '',desc = '',type = 'info',size = 'small',alwaysVisible = false,allowedClose = true) {
      this.title = title;
      this.desc = desc;
      this.type = type;
      this.size = size;
      this.delay = 5000;
      this.alwaysVisible = alwaysVisible;
      this.allowedClose = allowedClose;
  }

  load() {
    this.init();
    this.bind();
  }

  init() {
    this.displayNotification()
  }

  bind() {
    $('#notification_bottom_close').on('click', function(){
      $('#notification_bottom').stop().fadeOut(220)
    });

    $('#global_nav_trigger').on('click',function(){
      if($(this).is(':checked')) {
        $('#notification_bottom').stop().css({
          bottom: 0,
          opacity: 0
        });
      }else{
        $('#notification_bottom').stop().css({
          bottom: 50,
          opacity: 1
        });
      }
    });
  }

  createNotification() {

    let html = '';
    html += '<div id="notification_bottom" class="notification-bottom {{type}} {{size}}">';
    html += '<div class="notification-bottom-inner">';
    html += '<div class="message">';
    html += '<div class="title">{{title}}</div>';
    html += '<p class="description">{{desc}}</p>';
    html += '</div>';
    html += '</div>';
    if(this.allowedClose){
      html += '<div id="notification_bottom_close" class="close-btn">×</div>';
    }
    html += '</div>';

    html = html.replace('{{title}}',this.title);
    html = html.replace('{{desc}}',this.desc);
    html = html.replace('{{type}}',this.type);
    html = html.replace('{{size}}',this.size);

    return html;

  }

  displayNotification() {

    $('#notification_bottom').remove(); 
    $('body').append(this.createNotification());

    document.getElementById('notification_bottom').style.opacity = 0;
    document.getElementById('notification_bottom').style.bottom = 0;
    // document.getElementById('notification_bottom').style.right = '50px';

    if(this.alwaysVisible){
      $('#notification_bottom').animate({bottom:80,right:50,opacity:1},500,'swing');
    }else{
      $('#notification_bottom').animate({bottom:80,right:50,opacity:1},500,'swing').delay(this.delay).fadeOut(220);
    }
    
  }

  setDelay(delay) {
    this.delay = delay;
  }

  setVisible(visible) {
    this.alwaysVisible = visible;
  }
}