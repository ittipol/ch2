@extends('layouts.blackbox.main')
@section('content')

@include('pages.product.layouts.top_nav')

<div class="container">
  
  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          ข้อมูลจำเพาะของสินค้า
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
      <?php 
        echo Form::label('product_model', 'โมเดล');
        echo Form::text('product_model', null, array(
          'placeholder' => 'โมเดล',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('sku', 'SKU');
        echo Form::text('sku', null, array(
          'placeholder' => 'SKU',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('size', 'ขนาดสินค้า');
      ?>
      <div class="input-field-group">
      <?php
        echo Form::text('width', null, array(
          'placeholder' => 'กว้าง',
          'autocomplete' => 'off'
        ));

        echo Form::text('length', null, array(
          'placeholder' => 'ยาว',
          'autocomplete' => 'off'
        ));

        echo Form::text('height', null, array(
          'placeholder' => 'สูง',
          'autocomplete' => 'off'
        ));
      ?>
      </div>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('length', 'หน่วยของขนาดสินค้า');
        echo Form::select('length_unit_id', $_fieldData['lengthUnits']);
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('weight', 'น้ำหนักสินค้า');
      ?>
      <div class="input-field-group">
      <?php
        echo Form::text('weight', null, array(
          'placeholder' => 'น้ำหนักสินค้า',
          'autocomplete' => 'off'
        ));
        echo Form::select('weight_unit_id', $_fieldData['weightUnits']);
      ?>
      </div>
    </div>

  </div>

  <div class="form-section">

    <div class="title">
      กำหนดข้อมูลจำเพาะของสินค้า
    </div>

    <div class="form-row">
      <div id="specification_input" class="text-group">
        <div class="text-group-panel"></div>
        <a href="javascript:void(0);" class="text-add">เพิ่ม +</a>
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

  $(document).ready(function(){

    const inputFieldGroup = new InputFieldGroupList('specification_input','specifications');
    inputFieldGroup.setField('text','title','หัวข้อข้อมูลจำเพาะ');
    inputFieldGroup.setField('text','value','รายละเอียด');
    inputFieldGroup.setData({!!$_formData['specifications']!!});
    inputFieldGroup.load();

    const form = new Form();
    form.load();
    
  });    
</script>

@stop