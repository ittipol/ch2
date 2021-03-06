@extends('layouts.blackbox.main')
@section('content')

@include('pages.product.layouts.fixed_top_nav')

<div class="container">
  
  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          จำนวนสินค้า
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
      ปรับจำนวนสินค้า
    </div>

    <div class="form-row">

      <h4>
        จำนวนคงเหลือปัจจุบัน: {{$_formData['quantity']}} {{$_formData['product_unit']}}
      </h4>

      <?php
        echo Form::label('name', 'จำนวนสินค้าหลังปรับ', array(
          'class' => 'required'
        ));
        echo Form::text('quantity', '', array(
          'placeholder' => 'จำนวนสินค้า',
          'autocomplete' => 'off',
          'id' => 'quantity_input_box'
        ));
      ?>

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