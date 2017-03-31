<div class="notification-item clearfix">

  <div class="notification-image pull-left">
    <a href="{{$_notification['url']}}">
      <img src="{{$_notification['image']}}">
    </a>
  </div>

  <div class="notification-info pull-left">
    <a href="{{$_notification['url']}}">
      <h4 class="notification-title">{!!$_notification['title']!!}</h4>
    </a>
    <div class="notification-date">{{$_notification['createdDate']}}</div>
  </div>
</div>