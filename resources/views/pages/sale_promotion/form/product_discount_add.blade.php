@extends('layouts.blackbox.main')
@section('content')

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
      รายละเอียดโปรโมชั่น
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
          echo Form::text('price_text', null, array(
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
          echo Form::text('percent_text', null, array(
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
      ระยะเวลาโปรโมชั่น
    </div>

    <p class="error-message space-top-20">* โปรโมชั่นจะถูกใช้งานทันทีเมื่อถึงระยะที่ได้กำหนดไว้</p>

    <div class="form-row">
      <div class="select-group">
        <?php 
          echo Form::label('promotion_date_start', 'วันที่เริ่มต้นโปรโมชั่น');
          echo Form::select('', $day, $currentDay, array(
            'id' => 'promotion_start_day'
          ));
          echo Form::select('', $month, $currentMonth, array(
            'id' => 'promotion_start_month'
          ));
          echo Form::select('', $year, $currentYear, array(
            'id' => 'promotion_start_year'
          ));
        ?>
      </div>
    </div>

    <div class="form-row">
      <div class="select-group">
        <?php 
          echo Form::label('promotion_date_end', 'วันที่สิ้นสุดโปรโมชั่น');
          echo Form::select('', $day, $currentDay, array(
            'id' => 'promotion_end_day'
          ));
          echo Form::select('', $month, $currentMonth, array(
            'id' => 'promotion_end_month'
          ));
          echo Form::select('', $year, $currentYear, array(
            'id' => 'promotion_end_year'
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

  class ProductDiscount {

    constructor(price) {
      this.price = price;
      this.handle;
    }

    load() {

      let _this = this;

      if($('#price_chkbox').is(':checked')) {
        $('#price_text').prop('disabled', false);
        $('#percent_text').prop('disabled', true);

        if($('#price_text').val() != '') {
          let discount = _this.calByPrice($('#price_text').val());
          _this.updateDiscount(discount);
        }

      }

      if($('#percent_chkbox').is(':checked')) {
        $('#percent_text').prop('disabled', false);
        $('#price_text').prop('disabled', true);

        if($('#percent_text').val() != '') {
          let discount = _this.calByPercent($('#percent_text').val());
          _this.updateDiscount(discount);
        }
        
      }

      this.bind();
    }

    bind() {

      let _this = this;

      $('#main_form').on('submit',function(){

        var input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", "date_start");
        input.setAttribute("value", $('#promotion_start_year').val()+'-'+$('#promotion_start_month').val()+'-'+$('#promotion_start_day').val());
        this.appendChild(input);

        var input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", "date_end");
        input.setAttribute("value", $('#promotion_end_year').val()+'-'+$('#promotion_end_month').val()+'-'+$('#promotion_end_day').val());
        this.appendChild(input);
      
      });

      $('#price_chkbox').on('change',function(){

        clearTimeout(_this.handle);

        $('#price_text').prop('disabled', false);
        $('#percent_text').prop('disabled', true);

        if($('#price_text').val() != '') {
          let discount = _this.calByPrice($('#price_text').val());
          _this.updateDiscount(discount);
        }else{
          $('#reduced_amount').text('-');
          $('#reduced_price').text('-');
        }

      });

      $('#percent_chkbox').on('change',function(){

        clearTimeout(_this.handle);

        $('#percent_text').prop('disabled', false);
        $('#price_text').prop('disabled', true);

        if($('#percent_text').val() != '') {
          let discount = _this.calByPercent($('#percent_text').val());
          _this.updateDiscount(discount);
        }else{
          $('#reduced_amount').text('-');
          $('#reduced_price').text('-');
        }

      });

      $('#price_text').on('keyup',function(){

        if($(this).val() < 0) {
          $(this).val(0);
        }

        if($(this).val() > _this.price) {
          $(this).val(_this.price);
        }

        if($(this).val() != '') {
          let price = $(this).val();

          clearTimeout(_this.handle);
          _this.handle = setTimeout(function(){
            _this.updateDiscount(_this.calByPrice(price));
          },350);
        }

      });

      $('#percent_text').on('keyup',function(){

        if($(this).val() < 0) {
          $(this).val(0);
        }

        if($(this).val() > 100) {
          $(this).val(100);
        }

        if($(this).val() != '') {
          let percent = $(this).val();

          clearTimeout(_this.handle);
          _this.handle = setTimeout(function(){
            _this.updateDiscount(_this.calByPercent(percent));
          },350);
        }

      });

    }

    calByPrice(price) {
      return Math.round(this.price - price,2);
    }

    calByPercent(percent) {
      return Math.round(this.price - ((this.price * percent) / 100),2);
    }

    updateDiscount(discount) {
      $('#reduced_amount').text('THB '+(this.price - discount));
      $('#reduced_price').text('THB '+discount);
    }

  }

  $(document).ready(function(){

    const productDiscount = new ProductDiscount({{$price}});
    productDiscount.load();

  });

</script>

@stop