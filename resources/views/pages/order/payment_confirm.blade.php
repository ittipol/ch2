@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h4 class="sub-title">เลขที่การสั่งซื้อ {{$invoiceNumber}}</h4>
        <h2 class="title">ยืนยันการชำระเงิน</h2>
      </div>
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

    <div class="form-row">
      <?php 
        echo Form::label('', 'วิธีที่คุณชำระเงิน', array(
          'class' => 'required'
        ));
        echo Form::select('payment_method_id', $paymentMethods);
      ?>
    </div>

    <div class="form-row">
      <div class="select-group">
        <?php 
          echo Form::label('', 'วันที่ชำระเงิน', array(
            'class' => 'required'
          ));
          echo Form::select('payment_day', $day, $currentDay, array(
            'id' => 'payment_day'
          ));
          echo Form::select('payment_month', $month, $currentMonth, array(
            'id' => 'payment_month'
          ));
          echo Form::select('payment_year', $year, $currentYear, array(
            'id' => 'payment_year'
          ));
        ?>
      </div>
    </div>

    <div class="form-row">
      <div class="select-group">
        <?php 
          echo Form::label('', 'เวลาชำระเงิน', array(
            'class' => 'required'
          ));
          echo Form::select('payment_hour', $hours, null, array(
            'id' => 'payment_hour'
          ));
          echo Form::select('payment_min', $mins, null, array(
            'id' => 'payment_min'
          ));
        ?>
      </div>
    </div>

    <div class="form-row">
      <?php
        echo Form::label('', 'จำนวนเงิน', array(
          'class' => 'required'
        ));
        echo Form::text('payment_amount', null, array(
          'placeholder' => 'จำนวนเงิน',
          'autocomplete' => 'off',
          'role' => 'currency'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'รายละเอียดเพิ่มเติม');
        echo Form::textarea('description', null, array(
          'class' => 'ckeditor'
        ));
      ?>
    </div>

    <div class="form-row">

      <?php
        echo Form::label('', 'รูปภาพ');
      ?>

      <div class="form-row">
        <div id="_image_group">
        </div>
      </div>

    </div>

  </div>

  <?php
    echo Form::submit('ยืนยันการชำระเงิน' , array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  class PaymentConfirm {
    constructor() {}

    load() {
      this.bind();
    }

    bind() {

      $('#main_form').on('submit',function(){

        var input = document.createElement('input');
        input.setAttribute('type', 'hidden');
        input.setAttribute('name', 'payment_date');
        input.setAttribute('value', 
          $('#payment_year').val()+
          '-'+
          $('#payment_month').val()+
          '-'+
          $('#payment_day').val()+
          ' '+
          $('#payment_hour').val()+
          ':'+
          $('#payment_min').val()+
          ':'+
          '00'
        );
        this.appendChild(input);

      });

    }

  }

  $(document).ready(function(){

    const paymentConfirm = new PaymentConfirm();
    paymentConfirm.load();
    
    const images = new Images('_image_group','photo',5);
    images.load();

    const form = new Form();
    form.load();

  });

</script>

@stop