@extends('layouts.blackbox.main')
@section('content')

<div class="container">
  
  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          การคำนวณขนส่งสินค้า
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

      <div class="title">
        วิธีการคำนวณค่าขนส่ง
      </div>

      <label class="choice-box">
        <?php
          echo Form::radio('shipping_calculate_from', 1, null, array(
            'id' => 'shipping_seller'
          ));
        ?>
        <div class="inner">คำนวนค่าส่งสินค้าจากผู้ขาย</div>
      </label>

      <div>จะเป็นการคำนวณค่าส่งสินค้าด้วยตัวผู้ขายเองหลังจากที่ลูกค้าได้สั่งซื้อสินค้าเข้ามาแล้ว</div>

      <label class="choice-box">
        <?php
          echo Form::radio('shipping_calculate_from', 2, null, array(
            'id' => 'shipping_system'
          ));
        ?>
        <div class="inner">คำนวนค่าส่งสินค้าด้วยระบบ</div>
      </label>

      <div>จะเป็นการใช้ระบบคำนวณและแสดงค่าส่งสินค้าทันทีเมื่อเพิ่มสินค้าลงในตระกร้าสินค้า</div>

    </div>

  </div>

  <div class="shipping-form hide-element">

    <div class="form-section">

      <div class="title">
        ข้อมูลสำหรับการคำนวณค่าขนส่งสินค้า
      </div>

      <label class="choice-box">
        <?php
          echo Form::radio('free_shipping', 1, null, array(
            'id' => 'free_shipping'
          ));
        ?>
        <div class="inner">จัดส่งสินค้าฟรี</div>
      </label>

      <label class="choice-box">
        <?php
          echo Form::radio('free_shipping', 0, null, array(
            'id' => 'has_shipping_cost'
          ));
        ?>
        <div class="inner">คิดค่าจัดส่งสินค้า</div>
      </label>

    </div>

    <div class="shipping-cost-data hide-element form-section">

      <div class="form-row">

        <?php
          echo Form::label('shipping_cost', 'ค่าขนส่งสินค้า (บาท)', array(
            'class' => 'required'
          ));
          echo Form::text('shipping_cost', null, array(
            'placeholder' => 'ค่าขนส่งสินค้า',
            'autocomplete' => 'off'
          ));
        ?>

      </div>

      <div class="form-row">

        <?php
          echo Form::label('shipping_cost', 'ประเภทการคิดค่าขนส่งสินค้า', array(
            'class' => 'required'
          ));
          echo Form::select('shipping_amount_condition_id', $_fieldData['shippingAmountConditions']);
        ?>

      </div>

      <div class="form-row">

        <div class="sub-title">เงื่อนไขการส่งสินค้าฟรี</div>

        <div class="sub-form">

          <div class="sub-form-inner">

            <label class="choice-box">
              <?php
                echo Form::checkbox('free_shipping_with_condition', 1, null, array(
                  'id' => 'free_shipping_with_condition'
                ));
              ?>
              <div class="inner">เปิดการใช้งานกำหนดเงื่อนไขการส่งสินค้าฟรี</div>
            </label>

            <div class="form-row">

              <h5>ต้องการคำนวณจาก</h5>
              <p class="error-product-weight-message error-message hide-element">* โปรดตรวจสอบให้แน่ใจว่าได้กรอกน้ำหนักของสินค้าและหน่วยน้ำหนักของสินค้าแล้ว</p>
              <?php
                echo Form::select('shipping_calculate_type_id', $_fieldData['ShippingCalTypes'], null, array(
                  'id' => 'shipping_calculate_type'
                ));
              ?>
              <h5>เงื่อนไข</h5>
              <?php
                echo Form::select('free_shipping_operator_sign', $_fieldData['operatorSigns'], null, array(
                  'id' => 'free_shipping_operator_sign'
                ));
              ?>
              <h5>จำนวน (ชึ้นอยู่กับเงื่อนไขที่เลือก)</h5>
              <div class="alert alert-info" role="alert">
                <strong>เมื่อกำหนดตามน้ำหนักสินค้า</strong>
                <p>ให้กรอกน้ำหนักสินค้า โดยหน่วยของสินค้าขึ้นอยู่กับหน่วยที่คุณเลือกในหน้าข้อมูลจำเพาะ</p>
                <br/>
                <strong>เมื่อกำหนดตามจำนวนสินค้า</strong>
                <p>ให้กรอกจำนวนสินค้า</p>
                <br/>
                <strong>เมื่อกำหนดตามราคาสินค้า</strong>
                <p>ให้กรอกราคาสินค้า (บาท)</p>
              </div>
              <?php
                echo Form::text('free_shipping_amount', null, array(
                  'id' => 'free_shipping_amount',
                  'placeholder' => 'จำนวน',
                  'autocomplete' => 'off'
                ));
              ?>

            </div>

          </div>

        </div>

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

  class Shipping {

    constructor() {}

    load() {

      if($('#shipping_system').is(':checked')) {
        $('.shipping-form').css('display','block');
      }

      if($('#has_shipping_cost').is(':checked')) {
        $('.shipping-cost-data').css('display','block');
      }

      if($('#shipping_calculate_type').val() == 1) {
        $('.error-product-weight-message').css('display','block');
      }

      if(!$('#free_shipping_with_condition').is(':checked')) {
        $('#shipping_calculate_type').prop('disabled', true);
        $('#free_shipping_operator_sign').prop('disabled', true);
        $('#free_shipping_amount').prop('disabled', true);
      }

      this.bind();
    }

    bind() {
      
      $('#shipping_seller').on('change',function(){
        $('.shipping-form').css('display','none');

        $('#free_shipping').prop('checked', true).trigger('change');

      });

      $('#shipping_system').on('change',function(){
        $('.shipping-form').css('display','block');
      });

      $('#free_shipping').on('change',function(){
        $('.shipping-cost-data').css('display','none');
      });

      $('#has_shipping_cost').on('change',function(){
        $('.shipping-cost-data').css('display','block');
      });

      $('#free_shipping_with_condition').on('click',function(){
        if($(this).is(':checked')) {
          $('#shipping_calculate_type').prop('disabled', false);
          $('#free_shipping_operator_sign').prop('disabled', false);
          $('#free_shipping_amount').prop('disabled', false);
        }else{
          $('#shipping_calculate_type').prop('disabled', true);
          $('#free_shipping_operator_sign').prop('disabled', true);
          $('#free_shipping_amount').prop('disabled', true);
        }
      });

      $('#shipping_calculate_type').on('change',function(){
        if($(this).val() == 1) {
          $('.error-product-weight-message').css('display','block');
        }else{
          $('.error-product-weight-message').css('display','none');
        }
      });
      
    }

  }

  $(document).ready(function(){

    const shipping = new Shipping();
    shipping.load();

    const form = new Form();
    form.load();
    
  });    
</script>

@stop