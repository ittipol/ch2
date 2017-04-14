<h3>ยกเลิกการสมัคร</h3>
  
<div class="secondary-message-box info space-bottom-20">
  <div class="secondary-message-box-inner">
    <h4>ต้องการยกเลิกการสมัครใช่หรือไม่?</h4>
    <div>หากผู้สมัครมีคุณสมบัติไม่ตรงตามต้องการหรือมีการรับพนักงานใหม่เข้าทำงานแล้ว และทำให้จำเป็นต้องยกเลิกการสมัครนี้</div>
  </div>
</div>

<div class="line space-bottom-10"></div>
<?php 
  echo Form::open([
    'url' => $jobApplyingCancelUrl,
    'id' => 'job_applying_cancel_form',
    'method' => 'post', 
    'enctype' => 'multipart/form-data'
  ]);
?>
<div class="form-row">
  <?php 
    echo Form::label('message', 'คำอธิบายว่าทำไมถึงยกเลิกการสมัคร');
    echo Form::textarea('message');
  ?>
</div>
<?php 
  echo Form::submit('ตกลง' , array(
    'class' => 'button space-top-20'
  ));
  echo Form::close();
?>
<script type="text/javascript">

  $(document).ready(function(){

    const form = new Form('#job_applying_cancel_form');
    form.load();

  });

</script>