<h3>ตกลงเข้าทำงาน</h3>
  
<div class="secondary-message-box info space-bottom-20">
  <div class="secondary-message-box-inner">
    <h4>ต้องการตอบตกลงเข้าทำงานใช่หรือไม่?</h4>
  </div>
</div>

<div class="line space-bottom-10"></div>
<?php 
  echo Form::open([
    'url' => $jobPositionAcceptUrl,
    'id' => 'job_applying_accept_form',
    'method' => 'post', 
    'enctype' => 'multipart/form-data'
  ]);
?>
<div class="form-row">
  <?php 
    echo Form::label('message', 'คำอธิบายหรือกล่าวอะไรบ้างอย่างถึงบรืษัทหรือผู้ที่เกี่ยวข้อง');
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

    const form = new Form('#job_applying_accept_form');
    form.load();

  });

</script>