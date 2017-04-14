<h3>ไม่ผ่านการประเมิน</h3>

<div class="secondary-message-box info space-bottom-20">
  <div class="secondary-message-box-inner">
    <h4>ต้องการให้ผู้สมัครไม่ผ่านการประเมินใช่หรือไม่?</h4>
    <div>ผู้สมัครที่ไม่ผ่านการทดสอบ การสัมภาษณ์ หรือการประเมินต่างๆ และทางบริษัทหรือผู้ที่เกี่ยวข้องได้มีความเห็นว่าไม่สามารถที่จะรับเข้ามาทำงานได้</div>
  </div>
</div>

<div class="line space-bottom-10"></div>
<?php 
  echo Form::open([
    'url' => $jobApplyingNotPassUrl,
    'id' => 'job_applying_not_pass_form',
    'method' => 'post', 
    'enctype' => 'multipart/form-data'
  ]);
?>
<div class="form-row">
  <?php 
    echo Form::label('message', 'คำอธิบายว่าทำไมถึงไม่ผ่านการประเมิน');
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

    const form = new Form('#job_applying_not_pass_form');
    form.load();

  });

</script>