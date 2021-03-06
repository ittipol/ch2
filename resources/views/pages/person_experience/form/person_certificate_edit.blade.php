@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{URL::to('resume/edit')}}" class="btn btn-secondary">กลับไปหน้าเพิ่มประวัติการทำงาน</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{URL::to('resume')}}">ไปยังหน้าภาพรวมประวัติการทำงาน</a>
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
      <div class="col-lg-12">
        <div class="title">
          ประกาศนียบัตรและการฝึกอบรม
        </div>
      </div>
    </div>
  </div>

  @include('components.form_error') 

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

  <div class="form-section">
  
    <div class="form-row">
      <?php 
        echo Form::label('name', 'ชื่อประกาศนียบัตรหรือหัวข้อการฝึกอบรม', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อประกาศนียบัตรหรือหัวข้อการฝึกอบรม',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">

      <?php 
        echo Form::label('period_date', 'ระยะเวลาการฝึกอบรม');
      ?>

      <div class="period-panel" id="period_date">

        <div class="period-controller">
          <span id="start_year">
            <a href="javascript:void(0);">เพิ่มปี</a>
          </span>
          <span id="start_month"></span>
          <span id="start_day"></span>
          <span class="period-separate">ถึง</span>
          <span id="current">ปัจจุบัน</span>
          <span id="end_year">
            <a href="javascript:void(0);">เพิ่มปี</a>
          </span>
          <span id="end_month"></span>
          <span id="end_day"></span>
        </div>
      </div>

    </div>

    <div class="form-row">

      <?php 
        echo Form::label('cerificate', 'รูปภาพประกาศนียบัตร');
      ?>

      <div class="form-row">
        <div id="_image_group"></div>
      </div>

    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'รายละเอียดเกี่ยวกับโปรเจค');
        echo Form::textarea('description', null, array(
          'class' => 'person-experience-textarea'
        ));
      ?>
    </div>

  </div>

  <?php
    echo Form::submit('บันทึก' , array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const periodDate = new PeriodDate('period_date',{{$currentYear}},{!!$month!!});
    periodDate.load();
    periodDate.setData({!!$_formData['period']!!});

    const images = new Images('_image_group','photo',5);
    images.load({!!$_formData['Image']!!});

    const form = new Form();
    form.load();

  });

</script>

@stop