@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}product/option/{{request()->product_id}}" class="btn btn-secondary">กลับไปหน้าตัวเลือกสินค้า</a>
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

<div class="container">
  <div class="container-header">
    <div class="row">
      <div class="col-lg-6 col-sm-12">
        <div class="title">แก้ไขตัวเลือกสินค้า</div>
      </div>
    </div>
  </div>
</div>

<div class="container">

  @include('components.form_error')

  <div class="secondary-message-box info space-bottom-30">
    <div class="secondary-message-box-inner">
      <h3>โปรดอ่านก่อนการกำหนดตัวเลือกสินค้า</h3>
      <p>*** สามารถกำหนดราคาให้กับแต่ละตัวเลือกสินค้าได้ หากไม่ได้กำหนดจะใช้ราคาที่กำหนดไว้ของสินค้านั้นๆ</p>
      <p>*** สามารถกำหนดจำนวนสินค้าให้กับแต่ละตัวเลือกสินค้าได้ หากไม่ได้กำหนดจะใช้จำนวนสินค้าที่กำหนดไว้ของสินค้านั้นๆ</p>
    </div>
  </div>

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
        echo Form::label('item_category_id', 'เลือกรูปแบบการแสดง', array(
          'class' => 'required'
        ));
      ?>
      <div class="form-item-group no-padding no-border-bottom">
        <div class="form-item-group-inner">
          <div class="row">
            <div class="col-sm-12">
              <label class="choice-box">
                <?php
                  echo Form::radio('display_type', 1, true);
                ?> 
                <div class="inner">แสดงเฉพาะชื่อตัวเลือก</div>
              </label>
            </div>

            <div class="col-sm-12">
              <label class="choice-box">
                <?php
                  echo Form::radio('display_type', 2);
                ?> 
                <div class="inner">แสดงเฉพาะรูปภาพ</div>
              </label>
            </div>

            <div class="col-sm-12">
              <label class="choice-box">
                <?php
                  echo Form::radio('display_type', 3);
                ?> 
                <div class="inner">แสดงชื่อตัวเลือกพร้อมรูปภาพ</div>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('name', 'ชื่อตัวเลือก', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อตัวเลือก',
          'autocomplete' => 'off'
        ));
      ?>
      <p class='notice info'>เช่น ขนาดเสื้อผ้า S,M,L หรือ เฉดสีของเครื่องสำอาง</p>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('price', 'ราคาขาย');
      ?>
      <p class="error-message">*** หากไม่ได้กำหนดจะใช้ราคาที่กำหนดไว้ของสินค้านั้นๆ</p>
      <?php
        echo Form::text('price', null, array(
          'placeholder' => 'ราคาขาย',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('quantity', 'จำนวนสินค้า');
      ?>
      <p class="error-message">*** หากไม่ได้กำหนดจะใช้จำนวนสินค้าที่กำหนดไว้ของสินค้านั้นๆ</p>
      <?php
        echo Form::text('quantity', null, array(
          'placeholder' => 'จำนวนสินค้า',
          'autocomplete' => 'off',
          'id' => 'quantity_input_box'
        ));
      ?>
    </div>

  </div>

  <div class="form-section">

    <div class="title">
      รูปภาพ
    </div>

    <div class="form-row">
      <div id="_image_group"></div>
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

    const images = new Images('_image_group','photo',1);
    images.load({!!$_formData['Image']!!});

    const form = new Form();
    form.load();
    
  });    
</script>

@stop