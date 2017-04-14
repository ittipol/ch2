<h3>ตอบรับการสมัคร</h3>
  
<div class="secondary-message-box info space-bottom-20">
  <div class="secondary-message-box-inner">
    <h4>ต้องการตอบรับการสมัครใช่หรือไม่?</h4>
    <div>ตอบรับการสมัครจะเป็นการบอกผู้สมัครให้ทราบว่าบริษัท สถานประกอบการหรือผู้ที่เกี่ยวข้องได้สนใจในตัวผู้สมัครและรับทราบการสมัครแล้ว</div>
  </div>
</div>

<div class="line space-bottom-10"></div>
<?php 
  echo Form::open([
    'url' => $jobPositionDeclineUrl,
    'id' => 'job_applying_accept_form',
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

    const form = new Form('#job_applying_accept_form');
    form.load();

  });

</script>