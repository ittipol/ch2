@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}payment_method" class="btn btn-secondary">กลับไปหน้าวิธีการชำระเงิน</a>
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
          เพิ่มวิธีโอนเงินผ่านธนาคาร
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

  <div class="form-row">
    <?php 
      echo Form::label('payment_service_provider_id', 'ชื่อธนาคาร', array(
        'class' => 'required'
      ));
      echo Form::select('payment_service_provider_id', $serviceProviders);
    ?>
  </div>

  <div class="form-row">
    <?php 
      echo Form::label('branch_name', 'ชื่อสาขา', array(
        'class' => 'required'
      ));
      echo Form::text('branch_name', null, array(
        'placeholder' => 'ชื่อสาขา',
        'autocomplete' => 'off'
      ));
    ?>
  </div>

  <div class="form-row">
    <?php 
      echo Form::label('account_number', 'เลขที่บัญชี', array(
        'class' => 'required'
      ));
      echo Form::text('account_number', null, array(
        'placeholder' => 'เลขที่บัญชี',
        'autocomplete' => 'off'
      ));
    ?>
  </div>

  <div class="form-row">
    <?php 
      echo Form::label('description', 'รายละเอียดวิธีการชำระเงิน');
      echo Form::textarea('description');
    ?>
  </div>

  <?php
    echo Form::submit('เพิ่มวิธีการชำระเงิน' , array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const form = new Form();
    form.load();

  });

</script>

@stop