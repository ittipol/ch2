@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}setting" class="btn btn-secondary">กลับไปหน้าข้อมูลร้านค้า</a>

          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}manage">กลับไปหน้าจัดการหลัก</a>
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
        <div class="title">การติดต่อบริษัทหรือร้านค้า</div>
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
        echo Form::label('Contact[phone_number]', 'หมายเลขโทรศัพท์', array(
          'class' => 'required'
        ));
      ?>
      <p class="error-message">* กรอกอย่างน้อย 1 หมายเลขโทรศัพท์</p>
      <div id="phone_number_input" class="text-group">
        <div class="text-group-panel"></div>
        <a href="javascript:void(0);" class="text-add">เพิ่ม +</a>
      </div>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('Contact[fax]', 'แฟกซ์');
      ?>
      <div id="fax_input" class="text-group">
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

    <div class="form-row">
      <?php 
        echo Form::label('Contact[website]', 'เว็บไซต์');
      ?>
      <div id="website_input" class="text-group">
        <div class="text-group-panel"></div>
        <a href="javascript:void(0);" class="text-add">เพิ่ม +</a>
      </div>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('Contact[line]', 'Line ID');
      ?>
      <div id="line_id_input" class="text-group">
        <div class="text-group-panel"></div>
        <a href="javascript:void(0);" class="text-add">เพิ่ม +</a>
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

    const phoneNumberInput = new TextInputList('phone_number_input','phone_number','หมายเลขโทรศัพท์');
    phoneNumberInput.enableCheckingEmpty();
    phoneNumberInput.setDataInputAtLease(1);
    phoneNumberInput.dataCheck('tel');
    @if(!empty($_formData['phone_number']))
      phoneNumberInput.load({!!$_formData['phone_number']!!});
    @else
      phoneNumberInput.load();
    @endif

    const faxInput = new TextInputList('fax_input','fax','แฟกซ์');
    @if(!empty($_formData['fax']))
      faxInput.load({!!$_formData['fax']!!});
    @else
      faxInput.load();
    @endif

    const emailInput = new TextInputList('email_input','email','อีเมล');
    @if(!empty($_formData['email']))
      emailInput.load({!!$_formData['email']!!});
    @else
      emailInput.load();
    @endif

    const websiteInput = new TextInputList('website_input','website','เว็บไซต์');
    @if(!empty($_formData['website']))
      websiteInput.load({!!$_formData['website']!!});
    @else
      websiteInput.load();
    @endif

    const lindIdInput = new TextInputList('line_id_input','line','Line ID');
    @if(!empty($_formData['line']))
      lindIdInput.load({!!$_formData['line']!!});
    @else
      lindIdInput.load();
    @endif

  });

</script>

@stop