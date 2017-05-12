@extends('layouts.blackbox.main')
@section('content')

<div class="container">
  
  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          สมัครงาน
        </div>
      </div>
    </div>
  </div>

  <?php 
    echo Form::open(['id' => 'main_form','method' => 'post', 'enctype' => 'multipart/form-data']);
  ?>

  <?php
    echo Form::hidden('_model', $_formModel['modelName']);
  ?>

  <div class="form-section">

    <div class="form-row">
      <h4>ชื่อบริษัท หรือสถานประกอบการ</h4>
      <div>{{$shopName}}</div>
    </div>

    <div class="form-row">
      <h4>ตำแหน่งงาน</h4>
      <div>{{$jobName}}</div>
    </div>

    <div class="form-row">
      <div class="secondary-message-box info space-bottom-10">
        <div class="secondary-message-box-inner">
          <h4>สร้างเรซูเม่ของคุณ</h4>
          <p>*** หากยังไม่มีเรซูเม สามารถสร้าง เรซูเม่ ของคุณลงในเว็บไชต์ได้ทันที</p>
          <p>*** เมือสมัครงานผ่านเว็บไชต์ เรซูเม่ ของคุณจะถูกแสดงไปยังเจ้าของบริษัท HR หรือบุคคลที่เกี่ยวข้องกับงาน</p>
          <p>*** สามารถแก้ไข เปลี่ยนแปลง เรซูเม่ ของคุณได้ตลอดเวลา</p>
        </div>
      </div>
      <a href="{{URL::to('resume')}}" class="button">สร้างเรซูเม่</a>
    </div>

    @if(!empty($branches))
    <div class="form-row">
      <?php 
        echo Form::label('branch', 'เลือกสาขาที่สามารถทำงานได้ (เลือกได้มากกว่า 1 ตัวเลือก)');
      ?>
      <div class="form-item-group">
        <div class="form-item-group-inner">
          <div class="row">
            <?php 
              foreach ($branches as $id => $branch):
            ?>
              <div class="col-lg-4 col-sm-6 col-sm-12">
                <label class="choice-box">
                  <?php
                    echo Form::checkbox('JobApplyToBranch[branch_id][]', $id);
                  ?>
                  <div class="inner"><?php echo $branch; ?></div>
                </label>
              </div>
            <?php
              endforeach;
            ?>
          </div>
        </div>
      </div>
    </div>
    @endif

    <div class="form-row">
      <?php 
        echo Form::label('applicant_message', 'ข้อความถึงผู้รับสมัครงานนี้');
      ?>

      <div class="secondary-message-box info">
    <div class="secondary-message-box-inner">
      <p>*** ข้อความถึงผู้รับสมัครงานจะแสดงอยู่ส่วนแรกของรายละเอียดการสมัครงานของคุณ</p>
      <p>*** สามารถเว้นว่างข้อความนี้ได้</p>
    </div>
  </div>

      <p class="notice info">แนะนำตัวคุณหรือกล่าวอะไรบ้างอย่างถึงผู้รับสมัครงานนี้</p>
      <?php
        echo Form::textarea('applicant_message');
      ?>
      
    </div>

    <div class="form-row">

      <div class="sub-title">แนบไฟล์</div>

      <div class="secondary-message-box info space-bottom-20">
        <div class="secondary-message-box-inner">
          <p>*** ขนาดของไฟล์ต้องไม่เกิน 2 MB</p>
          <p>*** สามารถแนบไฟล์ได้สูงสุด 10 ไฟล์</p>
        </div>
      </div>

      <div id="_file_group"></div>

    </div>

  </div>

  <?php
    echo Form::submit('สมัครงาน', array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const attachedFile = new AttachedFile('_file_group');
    attachedFile.load();

    const form = new Form();
    form.load();

  });

</script>

@stop