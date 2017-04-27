@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}manage/job" class="btn btn-secondary">กลับไปหน้าหลักจัดการประกาศงาน</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}manage">ไปยังหน้าจัดการหลัก</a>
            </div>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="container">
  
  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          ลงประกาศงาน
        </div>
      </div>
    </div>
  </div>

  @include('components.form_error') 

  <?php 
    echo Form::open(['id' => 'main_form','method' => 'post', 'enctype' => 'multipart/form-data']);
  ?>

  <?php
    echo Form::hidden('_model', $_formModel['modelName']);
  ?>

  <div class="form-section">

    @if(!empty($_fieldData['branches']))
    <div class="form-row">
      <?php 
        echo Form::label('branch', 'เลือกสาขาที่เปิดรับสมัครงานนี้ (เว้นว่างได้)');
      ?>
      <div class="form-item-group">
        <div class="form-item-group-inner">
          <div class="row">
              @foreach ($_fieldData['branches'] as $id => $branch)
              <div class="col-lg-4 col-sm-6 col-sm-12">
                <label class="choice-box">
                  <?php
                    echo Form::checkbox('RelateToBranch[branch_id][]', $id);
                  ?>
                  <div class="inner"><?php echo $branch; ?></div>
                </label>
              </div>
              @endforeach
          </div>
        </div>
      </div>
    </div>
    @endif

    <div class="form-row">
      <?php 
        echo Form::label('name', 'ชื่อตำแหน่งงาน', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อตำแหน่งงาน',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('wage', 'อัตราค่าจ้าง (บาท)', array(
          'class' => 'required'
        ));
        echo Form::text('wage', null, array(
          'placeholder' => 'อัตราค่าจ้าง',
          'autocomplete' => 'off'
        ));
      ?>
      <p class="notice info">สามารถกรอกเป็นประโยคได้ เช่น 10000 - 20000 บาท, ตามประสบการณ์ หรือ สามารถต่อรองได้</p>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('wage', 'ประเภทอัตราค่าจ้าง');
        echo Form::select('wage_type_id', $_fieldData['wageTypes']);
      ?>
    </div>

    <div class="form-row">
      <?php
        echo Form::label('employment_type_id', 'รูปแบบการจ้างงาน', array(
          'class' => 'required'
        ));
      ?>
      @foreach ($_fieldData['employmentTypes'] as $id => $employmentType)
        <label class="choice-box">
          <?php
            echo Form::radio('employment_type_id', $id);
          ?>
          <div class="inner">{{$employmentType}}</div>
        </label>
      @endforeach
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('qualification', 'คุณสมบัติผู้สมัคร');
        echo Form::textarea('qualification');
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'รายละเอียดงาน');
        echo Form::textarea('description');
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('benefit', 'สวัสดิการ');
        echo Form::textarea('benefit');
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('_tags', 'แท๊กที่เกี่ยวข้องกับงานนี้');
      ?>
      <div id="_tags" class="tag"></div>
    </div>

  </div>

  <div class="form-section">

    <div class="title">
      รูปภาพ
    </div>

    <div class="form-row">
      <div id="_image_group"></div>
    </div>

  </div>

  <div class="form-section">

    <div class="title">
      วิธีการสมัครงาน
    </div>

    <div class="form-row">

      <label class="choice-box">
        <input type="checkbox" checked disabled >
        <div class="inner">รับสมัครผ่านชุมชน CHONBURI SQUARE</div>
      </label>
      <br>
      <label class="choice-box">
        <?php
          echo Form::checkbox('recruitment_custom', 1, null, array(
            'id' => 'recruitment_custom'
          ));
        ?>
        <div class="inner">เพิ่มวิธีการสมัครงานนี้</div>
      </label>

      <?php 
        echo Form::label('recruitment_custom_detail', 'รายละเอียดการสมัครงานนี้');
        echo Form::textarea('recruitment_custom_detail', null, array(
          'class' => 'recruitment-custom-input'
        ));
      ?>
    </div>

  </div>

  <?php
    echo Form::submit('ลงประกาศงาน', array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const images = new Images('_image_group','photo',10,'description');
    images.load();

    const tagging = new Tagging();
    tagging.load();
    @if(!empty($_oldInput['Tagging']))
      tagging.setTags('{!!$_oldInput['Tagging']!!}');
    @endif

    const job = new Job();
    job.load();
    
    const form = new Form();
    form.load();
    
  });    
</script>

@stop