<h3>ข้อความ</h3>
<div class="line space-bottom-10"></div>

<?php 
  echo Form::open([
    'url' => $newMessagePostUrl,
    'id' => 'job_applying_new_message_form',
    'method' => 'post', 
    'enctype' => 'multipart/form-data'
  ]);
?>
<?php
  echo Form::hidden('_model', 'Message');
?>
<div class="form-section">

  <div class="form-row">
    <?php 
      echo Form::label('message', 'ข้อความ', array(
        'class' => 'required'
      ));
    ?>
    <p class="message-input-error-message error-message hide-element">ข้อความห้ามว่าง</p>
    <?php
      echo Form::textarea('message', null, array(
        'class' => 'ckeditor'
      ));
    ?>
  </div>

  <div class="form-row">

    <div class="sub-title">แนบไฟล์</div>

    <div class="secondary-message-box info space-bottom-20">
      <div class="secondary-message-box-inner">
        <p>*** ขนาดของไฟล์แต่ละไฟล์ต้องไม่เกิน 2 MB</p>
        <p>*** สามารถแนบไฟล์ได้สูงสุด 10 ไฟล์</p>
      </div>
    </div>

    <div id="_file_group_for_new_message"></div>

  </div>

  @if(!empty($sendAs))
    <div class="form-row">
    @foreach($sendAs as $value)
      <label class="choice-box">
        <?php
          echo Form::radio('send_as', $value['value'], $value['select']);
        ?>
        <div class="inner">{{$value['text']}}</div>
      </label>
    @endforeach
    </div>
  @endif

</div>
<?php 
  echo Form::submit('บันทึก' , array(
    'class' => 'button space-top-20'
  ));
  echo Form::close();
?>
<script type="text/javascript">

  $(document).ready(function(){

    const attachedFile = new AttachedFile('_file_group_for_new_message','#job_applying_new_message_form');
    attachedFile.load();

    const jobApplyingNewMessage = new JobApplyingNewMessage('#job_appying_new_message_panel',attachedFile);
    jobApplyingNewMessage.load();

    const form = new Form('#job_applying_new_message_form');
    form.load();

  });

</script>
