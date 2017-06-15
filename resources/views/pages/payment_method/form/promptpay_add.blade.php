@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">
        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}manage/payment_method" class="btn btn-secondary">กลับไปหน้าวิธีการชำระเงิน</a>
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
          เพิ่มวิธีโอนเงินผ่านบริการพร้อมเพย์
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
      echo Form::label('additional_data[promptpay_transfer_number]', 'หมายเลขที่ใช้ในการโอน', array(
        'class' => 'required'
      ));
    ?>
    <label class="choice-box">
      <?php
        echo Form::radio('additional_data[promptpay_transfer_number_type]', 'tel-no', true);
      ?>
      <div class="inner">หมายเลขโทรศัพท์</div>
    </label>
    <label class="choice-box">
      <?php
        echo Form::radio('additional_data[promptpay_transfer_number_type]', 'id-card-no', false);
      ?>
      <div class="inner">เลขบัตรประชาชน</div>
    </label>
    <?php
      echo Form::text('additional_data[promptpay_transfer_number]', null, array(
        'placeholder' => 'หมายเลขที่ใช้ในการโอน',
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