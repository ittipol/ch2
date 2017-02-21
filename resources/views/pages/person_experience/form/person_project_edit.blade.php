@extends('layouts.blackbox.main')
@section('content')

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-lg-12">
        <div class="title">
          โปรเจค
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
        echo Form::label('name', 'ชื่อหรือหัวข้อโปรเจค', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อหรือหัวข้อโปรเจค',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">

      <?php 
        echo Form::label('period_date', 'ระยะเวลา');
      ?>

      <div class="period-panel" id="period_date">

        <label class="choice-box">
          <?php
            echo Form::checkbox('current', 1, false, array(
              'id' => 'chk_current'
            ));
          ?>
          <div class="inner">โปรเจคนี้กำลังดำเนินการอยู่</div>
        </label>

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
        echo Form::label('description', 'รายละเอียดเกี่ยวกับโปรเจค');
        echo Form::textarea('description', null, array(
          'class' => 'ckeditor'
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

    CKEDITOR.instances['description'].config.height = '600px';

    const periodDate = new PeriodDate('period_date',{{$latestYear}},{!!$month!!});
    periodDate.load();
    periodDate.setData({!!$_formData['period']!!});

    const form = new Form();
    form.load();

  });

</script>

@stop