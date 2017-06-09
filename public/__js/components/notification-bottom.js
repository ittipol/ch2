class NotificationBottom {
  
  constructor() {
    if(!NotificationBottom.instance){
      this.title = '';
      this.desc = '';
      this.type = 'info';
      this.layout = 'small';
      this.handle = null;
      this.delay = 5000;
      this.alwaysVisible = false;
      this.allowedClose = true;
      NotificationBottom.instance = this;
    }

    return NotificationBottom.instance;
  }

  load() {
    this.bind();
  }

  // init() {}

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
    html += '<div id="notification_bottom" class="notification-bottom {{type}} {{layout}}">';
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
    html = html.replace('{{layout}}',this.layout);

    return html;

  }

  display() {

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

  setTitle(title) {
    this.title = title;     
  }

  setDesc(desc) {
    this.desc = desc;     
  }

  setType(type) {
    this.type = type;     
  }

  setDelay(delay) {
    this.delay = delay;
  }

  setLayout(layout) {
    this.layout = layout;
  }

  setVisible(visible) {
    this.alwaysVisible = visible;
  }

}