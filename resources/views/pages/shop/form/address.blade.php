@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}setting" class="btn btn-secondary">กลับไปยังหน้าข้อมูลร้านค้า</a>

          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}manage">กลับไปยังหน้าจัดการหลัก</a>
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
      <div class="col-lg-6 col-sm-12">
        <div class="title">ที่อยู่บริษัทหรือร้านค้า</div>
      </div>
    </div>
  </div>
</div>

<div class="container">

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
        echo Form::label('address', 'ที่อยู่');
        echo Form::text('address', null, array(
          'placeholder' => 'ที่อยู่',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('province_id', 'จังหวัด');
        echo Form::select('province_id', $_fieldData['provinces'] ,null, array(
          'id' => 'province'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('district_id', 'อำเภอ');
        echo Form::select('district_id', array() ,null, array(
          'id' => 'district'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('sub_district_id', 'ตำบล');
        echo Form::select('sub_district_id', array() , null, array(
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

  <?php
    echo Form::submit('บันทึก' , array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

<div>

<script type="text/javascript">
  
  const map = new Map();
  map.setInputName('latitude','longitude');
  map.initialize();
  @if(!empty($_geographic))
  map.setLocation({!!$_geographic!!});
  @endif

  const address = new Address();
  address.load();

</script>

@stop