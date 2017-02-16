@extends('layouts.blackbox.main')
@section('content')

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-lg-7 col-sm-12">
        <div class="title">
          การติดต่อ
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

    const phoneNumberInput = new TextInputStack('phone_number_input','phone_number','หมายเลขโทรศัพท์');
    phoneNumberInput.enableCheckingEmpty();
    phoneNumberInput.setDataInputAtLease(1);
    phoneNumberInput.dataCheck('tel');
    @if(!empty($_formData['phone_number']))
      phoneNumberInput.load({!!$_formData['phone_number']!!});
    @else
      phoneNumberInput.load();
    @endif

    const faxInput = new TextInputStack('fax_input','fax','แฟกซ์');
    @if(!empty($_formData['fax']))
      faxInput.load({!!$_formData['fax']!!});
    @else
      faxInput.load();
    @endif

    const emailInput = new TextInputStack('email_input','email','อีเมล');
    @if(!empty($_formData['email']))
      emailInput.load({!!$_formData['email']!!});
    @else
      emailInput.load();
    @endif

    const websiteInput = new TextInputStack('website_input','website','เว็บไซต์');
    @if(!empty($_oldInput['website']))
      websiteInput.load({!!$_oldInput['website']!!});
    @else
      websiteInput.load();
    @endif

    const lindIdInput = new TextInputStack('line_id_input','line','Line ID');
    @if(!empty($_oldInput['line']))
      lindIdInput.load({!!$_oldInput['line']!!});
    @else
      lindIdInput.load();
    @endif

  });

</script>

@stop