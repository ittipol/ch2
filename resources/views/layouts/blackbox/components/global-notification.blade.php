<div class="global-notification-panel">

  <div class="notification-panel-header">
    <div class="notification-panel-header-title">การแจ้งเตือน</div>
    <div class="notification-panel-close-button"></div>
  </div>

  <div class="notification-list-table">
    <div class="notification-list-table-inner">
      <div id="notification_panel">

        @if(!empty($_notifications))

          @foreach($_notifications as $_notification)
            @include('layouts.blackbox.components.global-notification-item')
          @endforeach

          <a href="#" class="button wide-button">แสดงทั้งหมด</a>

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