@extends('layouts.blackbox.main')
@section('content')

@include('pages.product.layouts.fixed_top_nav')

<div class="container">
  
  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          ข้อความและการแจ้งเตือน
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

    <div class="title">
      ข้อความที่แสดงในหน้ารายละเอียดสินค้าเมื่อสินค้าหมด
    </div>

    <div class="form-row">

      <?php
        echo Form::label('message_out_of_order', 'ข้อความที่ต้องการแสดงเมื่อสินค้าหมด');
      ?>
      <p class="error-message">* จะแสดงข้อความ "สินค้าหมด" ถ้าไม่ได้กรอกข้อความนี้</p>
      <?php
        echo Form::text('message_out_of_order', null, array(
          'placeholder' => 'ข้อความที่ต้องการแสดงเมื่อสินค้าหมด',
          'autocomplete' => 'off'
        ));
      ?>

    </div>

    <div class="form-row">
      แสดงจำนวนสินค้าเมื่อสินค้าน้อยกว่า ... ชิ้น
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

  $(document).ready(function(){

    const form = new Form();
    form.load();
    
  });    
</script>

@stop