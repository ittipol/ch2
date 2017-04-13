<h3>ผ่านการสมัคร</h3>

<div class="secondary-message-box info space-bottom-20">
  <div class="secondary-message-box-inner">
    <h4>ต้องการให้ผู้สมัครผ่านการสมัครใช่หรือไม่?</h4>
  </div>
</div>

<div class="line space-bottom-10"></div>
<?php 
  echo Form::open([
    'url' => $jobApplyingPassedUrl,
    'id' => 'job_applying_cancel_form',
    'method' => 'post', 
    'enctype' => 'multipart/form-data'
  ]);
?>
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