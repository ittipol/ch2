@extends('layouts.blackbox.main')
@section('content')

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-lg-12">
        <div class="title">
          เพิ่มวิธีการจัดส่งสินค้า
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
        echo Form::label('name', 'ชื่อวิธีการจัดส่งสินค้า', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อวิธีการจัดส่งสินค้า',
          'autocomplete' => 'off'
        ));
      ?>
      <p class="notice info">เช่น พัสดุธรรมดา, พัสดุส่งพิเศษ</p>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('name', 'ผู้ให้บริการการจัดส่ง', array(
          'class' => 'required'
        ));
        echo Form::select('shipping_service_id', $_fieldData['shippingServices']);
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('name', 'รูปแบบการคิดค่าจัดส่งของวิธีการจัดส่งสินค้านี้', array(
          'class' => 'required'
        ));
      ?>

      <div>
        <label class="choice-box">
          <?php
            echo Form::radio('shipping_service_cost_type_id', 1, true, array(
              'class' => 'shipping-service-cost-type'
            ));
          ?> 
          <div class="inner">{{$_fieldData['shippingServiceCostTypes'][1]}}</div>
        </label>
      </div>

      <div>
        <label class="choice-box">
          <?php
            echo Form::radio('shipping_service_cost_type_id', 2, null, array(
              'class' => 'shipping-service-cost-type'
            ));
          ?> 
          <div class="inner">{{$_fieldData['shippingServiceCostTypes'][2]}}</div>
        </label>
      </div>

      <?php 
        echo Form::text('service_cost', null, array(
          'class' => 'service-cost',
          'placeholder' => 'จำนวนค่าบริการ',
          'autocomplete' => 'off'
        ));
      ?>

      <div>
        <label class="choice-box">
          <?php
            echo Form::radio('shipping_service_cost_type_id', 3, null, array(
              'class' => 'shipping-service-cost-type'
            ));
          ?> 
          <div class="inner">{{$_fieldData['shippingServiceCostTypes'][3]}}</div>
        </label>
      </div>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('shipping_time', 'ระยะเวลาจัดส่ง');
        echo Form::text('shipping_time', null, array(
          'placeholder' => 'ระยะเวลาจัดส่ง',
          'autocomplete' => 'off'
        ));
      ?>
      <p class="notice info">เช่น 1-3 วันทำการ</p>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'รายละเอียดเพิ่มเติม');
        echo Form::textarea('description', null, array(
          'class' => 'ckeditor'
        ));
      ?>
    </div>

  </div>

  <div class="secondary-message-box info space-bottom-20">
    <p>*** เมื่อเพิ่มวิธีการจัดส่งสินค้าแล้ว วิธีการจัดส่งสินค้าจะถูกแสดงเป็นตัวเลือกให้ลูกค้าเลือกในหน้าสั่งซื้อสินค้า</p>
  </div>

  <?php
    echo Form::submit('เพิ่มวิธีการจัดส่งสินค้า' , array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  class ServiceCost {

    constructor() {}

    load() {

      if($('.shipping-service-cost-type:checked').val() != 2) {
        $('.service-cost').prop('disabled',true);
      }

      this.bind();
    }

    bind() {

      $('.shipping-service-cost-type').on('change',function(){
     
        if($(this).val() == 2) {
          $('.service-cost').prop('disabled',false);
        }else{
          $('.service-cost').prop('disabled',true);
        }

      });

    }

  }

  $(document).ready(function(){

    const serviceCost = new ServiceCost();
    serviceCost.load();

    const form = new Form();
    form.load();

  });

</script>

@stop