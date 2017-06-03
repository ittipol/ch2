@if($hasOrderPaymentConfirm)

  <div class="secondary-message-box info space-bottom-30">
    <div class="secondary-message-box-inner">
      <h4>ลูกค้าแจ้งการชำระเงินแล้ว</h4>
    </div>
    <div class="message-box-button-group two-button clearfix">
      <div class="flat-button">
        <a href="{{$paymentDetailUrl}}" class="button green">รายละเอียดแจ้งการชำระเงิน</a>
      </div>
      <div class="flat-button">
        <a class="button" data-right-side-panel="1" data-right-side-panel-target="#payment_confirm_panel">
          ยืนยันการชำระเงิน
        </a>
      </div>
    </div>
  </div>

  @include('pages.order.components.payment_confirm_form')

@else

  <div class="secondary-message-box info space-bottom-30">
    <div class="secondary-message-box-inner">
      <h4>โปรดรอการแจ้งการชำระเงินจากลูกค้า</h4>
    </div>
  </div>

@endif