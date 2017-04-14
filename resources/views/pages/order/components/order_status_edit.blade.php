<h3>เปลี่ยนสถานะการสั่งซื้อ</h3>
<div class="line space-bottom-20"></div>
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
  <h5>ข้อความถึงผู้ซื้อ</h5>
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