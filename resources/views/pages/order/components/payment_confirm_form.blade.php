<div id="payment_confirm_panel" class="right-size-panel form">
  <div class="right-size-panel-inner">
    
    <h3>ยืนยันการชำระเงินเลขที่การสั่งซื้อ {{$order['invoice_number']}}</h3>
    <div class="line space-bottom-20"></div>
    <?php 
      echo Form::model([], [
        'url' => $paymentConfirmUrl,
        'method' => 'PATCH',
        'enctype' => 'multipart/form-data'
      ]);
    ?>

    <div class="secondary-message-box warning space-bottom-20">
      <div class="secondary-message-box-inner">
        <h4>เมื่อผู้ขายยืนยันการชำระเงินแล้วสถานะการสั่งซื้อจะถูกเปลี่ยนเป็น "จัดเตรียมสินค้า" โดยทันที</h4>
      </div>
    </div>

    <div class="form-row">
      <h5>ข้อความ</h5>
      <?php
        echo Form::textarea('message');
      ?>
    </div>
    <?php 
      echo Form::submit('ยืนยันการชำระเงิน' , array(
        'class' => 'button space-top-20'
      ));
      echo Form::close();
    ?>

    <div class="right-size-panel-close-button"></div>
  </div>
</div>