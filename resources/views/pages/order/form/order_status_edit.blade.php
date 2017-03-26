<h3>เปลี่ยนสถานะการสั่งซื้อ</h3>
<h5>สถานะการสั่งซื้อ</h5>
<div class="line space-bottom-10"></div>
<?php 
  echo Form::model([], [
    'url' => $updateOrderStatusUrl,
    'method' => 'PATCH',
    'enctype' => 'multipart/form-data'
  ]);
  echo Form::select('order_status_id', $nextOrderStatuses);
?>
<h5>ข้อความถึงผู้ซื้อ</h5>
<div class="line space-bottom-10"></div>
<?php 
  echo Form::textarea('message', null, array(
    'class' => 'ckeditor'
  ));
  echo Form::submit('บันทึก' , array(
    'class' => 'button space-top-20'
  ));
  echo Form::close();
?>