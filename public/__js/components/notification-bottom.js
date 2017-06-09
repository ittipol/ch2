class NotificationBottom {
  
  constructor(title = '',desc = '',type = 'info',size = 'small',alwaysVisible = false,allowedClose = true) {
      
      if(!NotificationBottom.instance){
        this.title = title;
        this.desc = desc;
        this.type = type;
        this.size = size;
        this.handle = null;
        this.delay = 5000;
        this.alwaysVisible = alwaysVisible;
        this.allowedClose = allowedClose;
        NotificationBottom.instance = this;
      }

      return NotificationBottom.instance;
  }

  load() {
    this.init();
    this.bind();
  }

  init() {
    this.displayNotification()
  }

  bind() {

    let _this = this;

    $('#notification_bottom_close').on('click', function(){
      $('#notification_bottom').stop().fadeOut(220)
    });

    $('#global_nav_trigger').on('click',function(){
      _this.hideNotificationBox(this);
    });

    $('#account_panel_trigger').on('click',function(){
      _this.hideNotificationBox(this);
    });

    $('#search_panel_trigger').on('click',function(){
      _this.hideNotificationBox(this);
    });

    $('#cart_panel_trigger').on('click',function(){
      _this.hideNotificationBox(this);
    });
    
    $('#notification_panel_trigger').on('click',function(){
      _this.hideNotificationBox(this);
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

    if(this.alwaysVisible){
      $('#notification_bottom').animate({bottom:50,right:50,opacity:1},500,'swing');
    }else{

      $('#notification_bottom').animate({bottom:50,right:50,opacity:1},500,'swing');

      clearTimeout(this.handle);

      this.handle = setTimeout(function(){
        $('#notification_bottom').fadeOut(220);
      },this.delay);
 
    }
    
  }

  hideNotificationBox(obj) {

    if($(obj).is(':checked')) {
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

  }

  setDelay(delay) {
    this.delay = delay;
  }

  setVisible(visible) {
    this.alwaysVisible = visible;
  }

}