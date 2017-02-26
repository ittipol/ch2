@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-lg-12">
        <div class="title">
          เพิ่มสาขา
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
        echo Form::label('name', 'ชื่อสาขา', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อสาขา',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'รายละเอียดของสาขา');
        echo Form::textarea('description', null, array(
          'class' => 'ckeditor'
        ));
      ?>
    </div>

    <div class="form-row">

      <div class="sub-title">รูปภาพ</div>

      <div class="form-row">
        <div id="_image_group">
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
        </div>
      </div>

      <div class="form-row">
        <?php 
          echo Form::label('Contact[email]', 'อีเมล');
        ?>
        <div id="email_input" class="text-group">
          <div class="text-group-panel"></div>
        </div>
      </div>

    </div>

    <div class="form-section">

      <div class="title">
       ที่อยู่สาขา
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

      <div class="form-row">
        <?php 
          echo Form::label('Address[sub_district_id]', 'ตำบล');
          echo Form::select('Address[sub_district_id]', array() , null, array(
            'id' => 'sub_district'
          ));
        ?>
      </div>

      <div class="form-row">
        <?php echo Form::label('', 'ระบุตำแหน่บนแผนที่'); ?>
        <input id="pac-input" class="controls" type="text" placeholder="Search Box">
        <div id="map"></div>
      </div>

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

    const images = new Images('_image_group','photo',10,'description');
    images.load({!!$_formData['Image']!!});

    const address = new Address();
    address.setDistrictId({{$_formData['Address']['district_id']}});
    address.setSubDistrictId({{$_formData['Address']['sub_district_id']}});
    address.load();

    const map = new Map();
    map.initialize();
    map.setLocation({!!$_formData['Address']['_geographic']!!});

    const phoneNumberInput = new TextInputList('phone_number_input','Contact[phone_number]','หมายเลขโทรศัพท์');
    phoneNumberInput.disableCreatingInput();
    phoneNumberInput.load({!!$_formData['Contact']['phone_number']!!});

    const emailInput = new TextInputList('email_input','Contact[email]','อีเมล');
    emailInput.disableCreatingInput();
    emailInput.load({!!$_formData['Contact']['email']!!});
    
    const realEstate = new RealEstate();
    realEstate.load();

    const form = new Form();
    form.load();

  });

</script>

@stop