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
    echo Form::model($_formData, [
      'id' => 'main_form',
      'method' => 'PATCH',
      'enctype' => 'multipart/form-data'
    ]);
  ?>

  <?php
    echo Form::hidden('_model', $_formModel['modelName']);
  ?>

  <div class="form-row">
    <div class="secondary-message-box info space-bottom-20">
      <div class="secondary-message-box-inner">
        <h4>ข้อมูลการสมัครที่คุณได้กรอกไว้</h4>
        <p>*** สามารถแก้ไขข้อมูลของคุณได้ก่อนการสมัครอีกครั้ง</p>
      </div>
    </div>
  </div>

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
      <div class="secondary-message-box info space-bottom-20">
        <div class="secondary-message-box-inner">
          <h4>ประวัติการทำงาน</h4>
          <p>*** เมื่อคุณสมัครงานแล้ว ประวัติการทำงานจะแสดงไปยังเจ้าของบริษัท HR หรือบุคคลที่เกี่ยวข้องกับงาน</p>
          <p>*** ประวัติการทำงานจะถูกเก็บรวบรวมไว้และสามารถถูกค้นหาได้โดยตรงจากเจ้าของบริษัท HR หรือบุคคลที่เกี่ยวข้องที่กำลังต้องการหาพนักงานมาร่วมงาน</p>
        </div>
      </div>
      <a href="{{URL::to('person/experience')}}" class="button">เพิ่มประวัติการทำงาน</a>
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
        echo Form::label('message', 'ข้อความถึงผู้รับสมัครงานนี้');
      ?>

      <div class="secondary-message-box info">
        <div class="secondary-message-box-inner">
          <p>*** ข้อความถึงผู้รับสมัครงานจะแสดงอยู่ส่วนแรกของรายละเอียดการสมัครงานของคุณ</p>
          <p>*** สามารถเว้นว่างข้อความนี้ได้</p>
        </div>
      </div>

      <p class="notice info">แนะนำตัวคุณหรือกล่าวอะไรบ้างอย่างถึงผู้รับสมัครงานนี้</p>
      <?php
        echo Form::textarea('message');
      ?>
      
    </div>

    <div class="form-row">

      <div class="sub-title">แนบไฟล์</div>

      @if(!empty($attachedFiles))

      <div class="form-row">
        <div class="secondary-message-box info space-bottom-20">
          <div class="secondary-message-box-inner">
            <h4>ไฟล์ของคุณ</h4>
            <p>*** หากมีการเพิ่มไฟล์ที่แนบไว้ ไฟล์ที่คุณเคยแนบไว้ก่อนนั้นจะถูกลบและแทนที่ด้วยไฟล์ใหม่</p>
          </div>
        </div>
      </div>

      <div class="message-file-attachment clearfix">

        @foreach($attachedFiles as $attachedFile)

          <a href="{{$attachedFile['downloadUrl']}}" class="attached-file-box clearfix">
            <div class="attached-file-image">
              <img src="/images/icons/document-white.png">
            </div>
            <div class="attached-file-info">
              <div class="attached-file-title">
                <h4>{{$attachedFile['filename']}}</h4>
                <h5>{{$attachedFile['filesize']}}</h5>
              </div>
            </div>
          </a>

        @endforeach
      </div>
      @endif

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