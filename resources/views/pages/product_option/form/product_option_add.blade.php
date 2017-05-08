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
        <div class="title">เพิ่มหัวข้อคุณลักษณะสินค้า</div>
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
        echo Form::label('name', 'ชื่อหัวข้อคุณลักษณะ', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อหัวข้อคุณลักษณะ',
          'autocomplete' => 'off'
        ));
      ?>
      <p class='notice info'>เช่น ขนาดเสื้อผ้า หรือ เฉดสีของเครื่องสำอาง</p>
    </div>

  </div>

  <?php
    echo Form::submit('เพิ่มตัวเลือกสินค้า', array(
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