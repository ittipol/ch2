<div id="order_status" class="right-size-panel form">
  <div class="right-size-panel-inner">
    <h3>เปลี่ยนสถานะการสั่งซื้อ</h3>
    <div class="line space-bottom-20"></div>
    <?php 
      echo Form::model([], [
        'url' => $updateOrderStatusUrl,
        'method' => 'PATCH',
        'enctype' => 'multipart/form-data'
      ]);
    ?>

    @if(!$hasOrderPaymentConfirm)

      <div class="secondary-message-box warning space-bottom-30">
        <div class="secondary-message-box-inner">
          <h4>ยังไม่มีการแจ้งการชำระเงินจากลูกค้า</h4>
          <p>โปรดตรวจสอบให้แน่ใจว่าลูกได้แจ้งการชำระเงินแล้วก่อนการเปลี่ยนสถานะการสั่งซื้อ</p>
        </div>
      </div>

    @endif

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
    <div class="right-size-panel-close-button"></div>
  </div>
</div>