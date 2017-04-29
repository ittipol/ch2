@extends('layouts.blackbox.main')
@section('content')

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          แก้ไขโปรไฟล์
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
        echo Form::label('name', 'รูปภาพโปรไฟล์');
      ?>
      <div id="_profile_image">
      </div>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('name', 'ชื่อ นามสกุล', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อ นามสกุล',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">

      <?php
        echo Form::label('name', 'เพศ');
      ?>

      <label class="choice-box">
        <?php
          echo Form::radio('gender', 'm', true);
        ?>
        <div class="inner">ชาย</div>
      </label>

      <label class="choice-box">
        <?php
          echo Form::radio('gender', 'f');
        ?>
        <div class="inner">หญิง</div>
      </label>

      <label class="choice-box">
        <?php
          echo Form::radio('gender', '0');
        ?>
        <div class="inner">ไม่ระบุ</div>
      </label>

    </div>

    <div class="form-row">
      <div class="select-group">
        <?php 
          echo Form::label('birth_date', 'วันเกิด');
          echo Form::select('birth_day', $day, null, array(
            'id' => 'birth_day'
          ));
          echo Form::select('birth_month', $month, null, array(
            'id' => 'birth_month'
          ));
          echo Form::select('birth_year', $year, null, array(
            'id' => 'birth_year'
          ));
        ?>
      </div>
    </div>

  </div>

  <div class="form-section">

    <div class="title">
      ข้อมูลการติดต่อ
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('Contact[phone_number]', 'หมายเลขโทรศัพท์');
      ?>
      <div id="phone_number_input" class="text-group">
        <div class="text-group-panel"></div>
        <a href="javascript:void(0);" class="text-add">เพิ่ม +</a>
      </div>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('Contact[email]', 'อีเมล');
      ?>
      <div id="email_input" class="text-group">
        <div class="text-group-panel"></div>
        <a href="javascript:void(0);" class="text-add">เพิ่ม +</a>
      </div>
    </div>

  </div>

  <div class="form-section">

    <div class="title">
      ที่อยู่ปัจจุบัน
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('Address[address]', 'ที่อยู่');
        echo Form::text('Address[address]', null, array(
          'placeholder' => 'ที่อยู่',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('Address[province_id]', 'จังหวัด');
        echo Form::select('Address[province_id]', $_fieldData['provinces'] ,null, array(
          'id' => 'province'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('Address[district_id]', 'อำเภอ');
        echo Form::select('Address[district_id]', array() ,null, array(
          'id' => 'district'
        ));
      ?>
    </div>

  </div>

  <?php
    echo Form::submit('บันทึก', array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const address = new Address();

    @if(!empty($_formData['Address']['district_id']))
      address.setDistrictId({{$_formData['Address']['district_id']}});
    @endif

    @if(!empty($_formData['Address']['sub_district_id']))
      address.setSubDistrictId({{$_formData['Address']['sub_district_id']}});
    @endif

    address.load();

    const images = new Images('_profile_image','profile-image',1);
    images.load('{!!$profileImage!!}');

    const phoneNumberInput = new TextInputList('phone_number_input','Contact[phone_number]','หมายเลขโทรศัพท์');
    // phoneNumberInput.disableCreatingInput();
    @if(!empty($_formData['Contact']['phone_number']))
      phoneNumberInput.load({!!$_formData['Contact']['phone_number']!!});
    @else
      phoneNumberInput.load();
    @endif

    const emailInput = new TextInputList('email_input','Contact[email]','อีเมล');
    // emailInput.disableCreatingInput();
    @if(!empty($_formData['Contact']['email']))
      emailInput.load({!!$_formData['Contact']['email']!!});
    @else
      emailInput.load();
    @endif

    const form = new Form();
    form.load();

  });

</script>

@stop