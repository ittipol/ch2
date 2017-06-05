@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">
        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}order/{{$orderId}}" class="btn btn-secondary">กลับไปยังหน้ารายการสั้งซื้อ</a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h4 class="sub-title">เลขที่การสั่งซื้อ {{$invoiceNumber}}</h4>
        <h2 class="title">ผู้ขายแจ้งและยืนยันการชำระเงิน</h2>
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

      @if(!empty($paymentMethod))

        <h4>{{$paymentMethod['type']}}</h4>
        <h4>{{$paymentMethod['name']}}</h4>

        <input type="hidden" name="payment_method_id" value="{{$paymentMethod['id']}}">

      @else

        @foreach($paymentMethods as $paymentMethod)

          <h4>{{$paymentMethod['name']}}</h4>

          @foreach($paymentMethod['data'] as $data)

            <label class="choice-box">
              <?php
                echo Form::radio('payment_method_id', $data['id'], $data['checked']);
              ?> 
              <div class="inner">{{$data['name']}}</div>
            </label>
            <br>

          @endforeach

        @endforeach

      @endif

    </div>

    <div class="line"></div>

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
          echo Form::label('', 'เวลา', array(
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
        echo Form::textarea('description');
      ?>
    </div>

    <div class="form-row">

      <?php
        echo Form::label('', 'รูปภาพหลักฐานการชำระเงิน');
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

  class PaymentInform {
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

    const paymentInform = new PaymentInform();
    paymentInform.load();
    
    const images = new Images('_image_group','photo',5);
    images.load();

    const form = new Form();
    form.load();

  });

</script>

@stop