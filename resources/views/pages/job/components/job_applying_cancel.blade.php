<h3>ยกเลิกการสมัคร</h3>
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
    echo Form::label('message', 'คำอธิบายหรือเหตุผลของการยกเลิกการสมัคร');
    echo Form::textarea('message', null, array(
      'class' => 'ckeditor'
    ));
  ?>
</div>
<?php 
  echo Form::submit('บันทึก' , array(
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