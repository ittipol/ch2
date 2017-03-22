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

  $(document).ready(function(){

    const form = new Form();
    form.load();

  });

</script>

@stop