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
          เพิ่มวิธีการชำระเงิน
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

    <div class="form-row">
      <?php 
        echo Form::label('career', 'ประเภทการชำระเงิน', array(
          'class' => 'required'
        ));
        echo Form::select('career_type_id', $paymentMethodTypes);
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('name', 'ชื่อวิธีการชำระเงิน', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อวิธีการชำระเงิน',
          'autocomplete' => 'off'
        ));
      ?>
      <p class="notice info">โปรดกรอกชื่อวิธีการชำระเงินให้เกิดความเข้าใจและเกิดความแตกต่างในแต่ละการชำระเงินที่กำหนด</p>
      <p class="notice info">ชื่อวิธีการชำระเงิน จะถูกแสดงไปยังลูกค้าและเป็นตัวเลือกในหน้าการแจ้งการชำระเงิน</p>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'รายละเอียดวิธีการชำระเงิน', array(
          'class' => 'required'
        ));
        echo Form::textarea('description');
      ?>
    </div>

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