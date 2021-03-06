<div class="global-panel global-notification-panel">

  <div class="panel-header notification-panel-header">
    <div class="panel-header-title">การแจ้งเตือน</div>
    <div class="panel-close-button notification-panel-close-button"></div>
  </div>

  <div class="notification-header-nav">
    <div class="notification-header-nav-inner">
      <div class="text-right">
        <a href="{{URL::to('account/notification')}}" class="flat-button">แสดงทั้งหมด</a>
      </div>
    </div>
  </div>

  <div class="notification-list-table">
    <div class="notification-list-table-inner">
      <div id="notification_panel">

        @if(!empty($_notifications))

          @foreach($_notifications as $_notification)
            @include('layouts.blackbox.components.global-notification-item')
          @endforeach

        @else

          <div class="list-empty-message  notification-empty text-center">
            <i class="fa fa-bell"></i>
            <h4>ยังไม่มีการแจ้งเตือน</h4>
          </div>

        @endif

      </div>
    </div>
  </div>
  
</div>