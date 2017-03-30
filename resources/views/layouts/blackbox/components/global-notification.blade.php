<div class="global-notification-panel">

  <div class="notification-panel-header">
    <div class="notification-panel-header-title">การแจ้งเตือน</div>
    <div class="notification-panel-close-button"></div>
  </div>

  <div class="notification-list-table">
    <div class="notification-list-table-inner">
      <div id="notification_panel">

        @if(!empty($_notifications))

          @foreach($_notifications as $notification)

            <div class="notification-list-table-row">
              <div class="notification-title">{{$notification['title']}}</div>
              <div class="notification-message">{!!$notification['message']!!}</div>
              <div>{{$notification['createdDate']}}</div>
            </div>

          @endforeach

        @else

          <div class="notification-empty text-center">
            <img src="/images/icons/bell-blue.png">
            <h4>ยังไม่มีการแจ้งเตือน</h4>
          </div>

        @endif

      </div>
    </div>
  </div>
  
</div>