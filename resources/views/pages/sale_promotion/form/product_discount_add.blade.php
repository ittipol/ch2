@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}product_sale_promotion/{{request()->product_id}}" class="btn btn-secondary">กลับไปหน้าโปรโมชั่นการขาย</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}manage/product/{{request()->product_id}}">ไปยังหน้าจัดการสินค้า</a>
              <a href="{{request()->get('shopUrl')}}manage/product">ไปยังหน้าหลักจัดการสินค้า</a>
            </div>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <h2>เพิ่มโปรโมชั่นลดราคา</h2>
    </div>
  </div>
</div>

<div class="container">

  @include('components.form_error') 

  <?php 
    echo Form::open(['id' => 'main_form','method' => 'post', 'enctype' => 'multipart/form-data']);
  ?>

  <?php
    echo Form::hidden('_model', $_formModel['modelName']);
  ?>

  <div class="form-section">

    <div class="title">
      รายละเอียดโปรโมชั่นลดราคา
    </div>

    <div class="form-row">
      <div class="message-box">
        <h4>
          ราคาสินค้าปกติ: {{$priceWithFormat}}
        </h4>

        <h4>
          จำนวนราคาที่ลด: <span id="reduced_amount">-</span>
        </h4>

        <h4>
          ราคาสินค้าหลังลดราคา: <span id="reduced_price">-</span>
        </h4>
      </div>

      <label class="choice-box">
        <?php
          echo Form::radio('input_type', 1, true, array(
            'id' => 'price_chkbox'
          ));
        ?>
        <div class="inner">กรอกจำนวนราคาที่ต้องการลด</div>
      </label>

      <div>
        <?php
          echo Form::text('reduced_price_input', null, array(
            'id' => 'price_text',
            'placeholder' => 'จำนวนราคาที่ต้องการลด',
            'autocomplete' => 'off',
            'role' => 'number'
          ));
        ?>
      </div>

      <label class="choice-box">
        <?php
          echo Form::radio('input_type', 2, null, array(
            'id' => 'percent_chkbox'
          ));
        ?>
        <div class="inner">กรอก % เพื่อคำนวณราคาลด</div>
      </label>

      <div class="input-addon">
        <?php
          echo Form::text('reduced_percent_input', null, array(
            'id' => 'percent_text',
            'placeholder' => '%',
            'autocomplete' => 'off',
            'role' => 'number'
          ));
        ?>
        <span>%</span>
      </div>

    </div>

  </div>

  <div class="form-section">

    <div class="title">
      ระยะเวลาโปรโมชั่นลดราคา
    </div>

    <div class="alert alert-danger space-top-20" role="alert">
      <h4>โปรดอ่านก่อนการกำหนดระยะเวลาโปรโมขั่น</h4>
      <p class="error-message">* โปรโมชั่นจะถูกใช้งานทันทีเมื่อถึงระยะที่ได้กำหนดไว้</p>
      <p class="error-message">* ไม่สามารถเพิ่มโปรโมชั่นในระยะเวลาที่ได้เคยกำหนดไว้แล้วได้</p>
      <p class="error-message">* เมื่อโปรโมชั่นถูกใช้งานจะไม่สามารถ <strong>แก้ไข</strong> หรือ <strong>ลบ</strong> โปรโมชั่นนั้นได้</p>
    </div>

    <div class="form-row">
      <div class="select-group">
        <?php 
          echo Form::label('', 'วันที่เริ่มต้นโปรโมชั่น');
          echo Form::select('promotion_start_day', $day, $currentDay, array(
            'id' => 'promotion_start_day'
          ));
          echo Form::select('promotion_start_month', $month, $currentMonth, array(
            'id' => 'promotion_start_month'
          ));
          echo Form::select('promotion_start_year', $year, $currentYear, array(
            'id' => 'promotion_start_year'
          ));
        ?>
      </div>

      <div class="select-group">
        <?php 
          echo Form::label('', 'เวลา');
          echo Form::select('promotion_start_hour', $hours, null, array(
            'id' => 'promotion_start_hour'
          ));
          echo Form::select('promotion_start_min', $mins, null, array(
            'id' => 'promotion_start_min'
          ));
        ?>
      </div>
    </div>

    <div class="form-row">
      <div class="select-group">
        <?php 
          echo Form::label('', 'ถึงวันที่ (วันสุดท้ายของโปรโมชั่น)');
          echo Form::select('promotion_end_day', $day, $currentDay, array(
            'id' => 'promotion_end_day'
          ));
          echo Form::select('promotion_end_month', $month, $currentMonth, array(
            'id' => 'promotion_end_month'
          ));
          echo Form::select('promotion_end_year', $year, $currentYear, array(
            'id' => 'promotion_end_year'
          ));
        ?>
      </div>

      <div class="select-group">
        <?php 
          echo Form::label('', 'เวลา');
          echo Form::select('promotion_end_hour', $hours, 23, array(
            'id' => 'promotion_end_hour'
          ));
          echo Form::select('promotion_end_min', $mins, 59, array(
            'id' => 'promotion_end_min'
          ));
        ?>
      </div>
    </div>

  </div>

  <?php
    echo Form::submit('เพิ่มโปรโมชั่น' , array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const productDiscount = new ProductDiscount({{$price}});
    productDiscount.load();

  });

</script>

@stop