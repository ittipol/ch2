@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}setting" class="btn btn-secondary">กลับไปยังหน้าข้อมูลร้านค้า</a>

          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}manage">กลับไปยังหน้าจัดการหลัก</a>
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
        <div class="title">คำอธิบายร้านค้า</div>
      </div>
    </div>
  </div>
</div>

<div class="container">

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
        echo Form::label('description', 'คำอธิบายสั้นๆ เพื่ออธิบายถึงบริษัท ร้านค้า หรือธุรกิจของคุณ');
        echo Form::textarea('description');
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('brand_story', 'เรื่องราว (Brand Story)');
        echo Form::textarea('brand_story');
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('vision', 'วิสัยทัศน์');
        echo Form::textarea('vision');
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('mission', 'พันธกิจ');
        echo Form::textarea('mission');
      ?>
    </div>

  </div>

  <?php
    echo Form::submit('บันทึก' , array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

@stop