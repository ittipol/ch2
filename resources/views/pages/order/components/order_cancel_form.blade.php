<div id="order_cancel_panel" class="right-size-panel form">
  <div class="right-size-panel-inner">
    
    <h3>ยกเลิกการสั่งซื้อ</h3>
    <div class="line space-bottom-20"></div>
    <?php 
      echo Form::model([], [
        'url' => $orderCancelUrl,
        'method' => 'PATCH',
        'enctype' => 'multipart/form-data'
      ]);
    ?>

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