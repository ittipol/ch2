<h3>ผ่านการประเมินและรับผู้สมัครเข้าทำงาน</h3>

<div class="secondary-message-box info space-bottom-20">
  <div class="secondary-message-box-inner">
    <h4>ต้องการรับผู้สมัครเข้ามาทำงานใช่หรือไม่?</h4>
    <div>ผู้สมัครที่ผ่านการทดสอบ การสัมภาษณ์ หรือการประเมินต่างๆ และทางบริษัทหรือผู้ที่เกี่ยวข้องได้มีความเห็นว่าสามารถที่จะรับเข้ามาทำงานได้</div>
  </div>
</div>

<div class="line space-bottom-10"></div>
<?php 
  echo Form::open([
    'url' => $jobApplyingPassedUrl,
    'id' => 'job_applying_passed_form',
    'method' => 'post', 
    'enctype' => 'multipart/form-data'
  ]);
?>
<div class="form-row">
  <?php 
    echo Form::label('job_position_description', 'เงินเดือน วันที่เริ่มทำงาน หรือข้อตกลงต่างๆ ของตำแหน่งงานนี้', array(
      'class' => 'required'
    ));
    echo Form::textarea('job_position_description');
  ?>
</div>
<?php 
  echo Form::submit('ตกลง' , array(
    'class' => 'button'
  ));
  echo Form::close();
?>
<script type="text/javascript">

  $(document).ready(function(){

    const form = new Form('#job_applying_passed_form');
    form.load();

  });

</script>