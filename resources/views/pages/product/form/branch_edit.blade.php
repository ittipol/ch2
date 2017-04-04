@extends('layouts.blackbox.main')
@section('content')

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          สินค้า
        </div>
      </div>
    </div>
  </div>

  <div class="secondary-message-box info space-bottom-20">
    <div class="secondary-message-box-inner">
      <p class="text-bold">*** รายการสั่งซื้อหรือสินค้าบางรายการอาจยังไม่ได้รวมค่าจัดส่ง</p>
      <p class="text-bold">*** ค่าจัดส่งของการสั่งซื้ออาจมีการเปลี่ยนแปลงหลังจากผู้ขายได้ทำการยืนยันการสั่งซื้อ</p>
      <p class="text-bold">*** โปรดรอการยืนยันการสั่งซื้อจากผู้ขายก่อนการชำระเงิน</p>
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
        echo Form::label('branch', 'สาขาที่ขายสินค้านี้');
      ?>
      <div class="form-item-group">
        <div class="form-item-group-inner">
          <div class="row">
              @foreach ($_fieldData['branches'] as $id => $branch)
              <div class="col-lg-4 col-sm-6 col-sm-12">
                <label class="choice-box">
                  <?php
                    echo Form::checkbox('RelateToBranch[branch_id][]', $id);
                  ?>
                  <div class="inner"><?php echo $branch; ?></div>
                </label>
              </div>
              @endforeach
          </div>
        </div>
      </div>
    </div>

  </div>

  <?php
    echo Form::submit('เพิ่มสินค้า', array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

@stop