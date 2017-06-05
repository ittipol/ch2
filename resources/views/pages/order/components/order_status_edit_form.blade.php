<div id="order_status" class="right-size-panel form">
  <div class="right-size-panel-inner">
    <h3>เปลี่ยนสถานะการสั่งซื้อ</h3>
    <div class="line space-bottom-20"></div>

    @if(!$hasOrderPaymentConfirm)

      <div class="secondary-message-box warning space-bottom-30">
        <div class="secondary-message-box-inner">
          <h4>ยังไม่สามารถเปลี่ยนไปยังสถานะถัดไปได้จนกว่าจะการชำระเงินเสร็จสิ้น</h4>
        </div>
      </div>

      <div class="secondary-message-box info space-bottom-30">
        <div class="secondary-message-box-inner">
          <h4>หากเกิดข้อผิดพลาดหรือต้องการแจ้งการชำระเงินแทนผู้ซื้อ ผู้ขายสามารถแจ้งและยืนยันการชำระเงินได้</h4>
          <div class="text-right">
            <a href="{{$sellerPaymentConfirmUrl}}" class="button">ยืนยันการชำระเงิน</a>
          </div>
        </div>
      </div>

    @elseif(!$orderConfirmed)

      <div class="secondary-message-box warning space-bottom-30">
        <div class="secondary-message-box-inner">
          <h4>ยังไม่สามารถเปลี่ยนไปยังสถานะถัดไปได้จนกว่าจะการชำระเงินเสร็จสิ้น</h4>
        </div>
      </div>

      <div class="secondary-message-box info space-bottom-30">
        <div class="secondary-message-box-inner">
          <h4>ลูกค้าแจ้งการชำระเงินแล้ว</h4>
          <div class="text-right">
            <a class="button" data-right-side-panel="1" data-right-side-panel-target="#payment_confirm_panel">
              ยืนยันการชำระเงิน
            </a>
          </div>
        </div>
      </div>

    @else

      <?php 
        echo Form::model([], [
          'url' => $updateOrderStatusUrl,
          'method' => 'PATCH',
          'enctype' => 'multipart/form-data'
        ]);
      ?>

      <div class="form-row">
        <h5>สถานะการสั่งซื้อ</h5>
        <?php
          echo Form::select('order_status_id', $nextOrderStatuses);
        ?>
      </div>

      <div class="form-row">
        <h5>ข้อความ</h5>
        <?php
          echo Form::textarea('message');
        ?>
      </div>
      <?php 
        echo Form::submit('บันทึก' , array(
          'class' => 'button space-top-20'
        ));
        echo Form::close();
      ?>

    @endif

    <div class="right-size-panel-close-button"></div>
  </div>
</div>