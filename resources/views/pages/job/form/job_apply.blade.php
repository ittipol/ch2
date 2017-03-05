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
      <h4>งาน</h4>
      <div>{{$jobName}}</div>
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
      <p class="notice info">แนะนำตัวคุณหรือกล่าวอะไรบ้างอย่างถึงผู้รับสมัครงานนี้</p>
      <?php
        echo Form::textarea('message', null, array(
          'class' => 'ckeditor'
        ));
      ?>
      <div class="space-top-10">
        <p class="error-message">* ข้อความนี้จะแสดงอยู่ส่วนแรกของรายละเอียดการสมัครงานของคุณ</p>
        <p class="error-message">* สามารถเว้นว่างข้อความนี้ได้</p>
      </div>
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

@stop