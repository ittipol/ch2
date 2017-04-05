@extends('layouts.blackbox.main')
@section('content')

@include('pages.shipping_method.layouts.top_nav')

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-lg-12">
        <div class="title">
          วิธีการจัดส่งสินค้า
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
        echo Form::label('name', 'ชื่อวิธีการจัดส่งสินค้า', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อวิธีการจัดส่งสินค้า',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('name', 'ผู้ให้บริการการจัดส่ง');
      ?>
      <div>{{$shippingMethod['shippingService']}}</div>
    </div>

    @if(!empty($_fieldData['branches']))
    <div class="form-row">
      <?php 
        echo Form::label('branch', 'ระบุสาขาที่ลูกค้าสามารถรับสินค้าได้ (เว้นว่างได้)');
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
    @endif

    <div class="form-row">
      <?php 
        echo Form::label('name', 'รูปแบบการคิดค่าจัดส่งของวิธีการจัดส่งสินค้านี้');
      ?>

      <div class="secondary-message-box info">
        <div class="secondary-message-box-inner">
          <h4>ตัวเลือกรูปแบบการคิดค่าจัดส่ง</h4>
          <div>
            <h5><strong>แปรพันตามสินค้า</strong></h5>
            <p>*** จะไม่กำหนดค่าจัดส่งใดๆ ทั้งสิ้น โดยค่าจัดส่งทั้งหมดจะถูกกำหนดจากผู้ขายเท่านั้น</p>
            <p>*** ค่าจัดส่งของรายการสั่งซื้อและสินค้าทั้งหมดจะถูกคงไว้ ไม่มีการเปลี่ยนแปลงใดๆ ทั้งสิ้น</p>
            <p>*** ผู้ขายสามารถแก้ไขค่าจัดส่งได้ในหน้าการยืนยันการสั่งซื้อ</p>
          </div>
          <div class="line space-top-bottom-20"></div>
          <div>
            <h5><strong>ค่าบริการคงที่</strong></h5>
            <p>*** จะเป็นการกำหนดค่าจัดส่งให้กับการสั่งซื้อแบบคงที่</p>
            <p>*** ถ้ารายการสั่งซื้อมีสินค้าที่ได้กำหนดค่าจัดส่งไว้แล้ว สินค้าจะถูกคงค่าจัดส่งไว้โดยจะไม่มีการเปลี่ยนแปลงค่าจัดส่งจนกว่าจะถูกแก้ไขจากผู้ขาย</p>
            <p>*** ผู้ขายสามารถแก้ไขค่าจัดส่งได้ในหน้ายืนยันการสั่งซื้อ</p>
          </div>
          <div class="line space-top-bottom-20"></div>
          <div>
            <h5><strong>ไม่คิดค่าบริการ</strong></h5>
            <p>*** จะไม่มีการคิดค่าจัดส่งสินค้าใดๆ ทั้งสิ้น ไม่ว่าจะเป็นค่าจัดส่งของรายการสั่งซื้อหรือค่าจัดส่งของสินค้าแต่ละรายการ</p>
            <p>*** สินค้าทั้งหมดในรายการสั่งซื้อจะถูกปรับค่าจัดส่งเป็น "จัดส่งฟรีทั้งหมด" โดยทันทีเมื่อลูกค้าได้สั่งซื้อสินค้า</p>
            <p>*** ผู้ขายสามารถแก้ไขค่าจัดส่งได้ในหน้ายืนยันการสั่งซื้อ</p>
          </div>
        </div>
      </div>

      <div class="space-top-bottom-10">{{$shippingMethod['shippingServiceCostType']}}</div>

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
    <div class="secondary-message-box-inner">
      <p>*** เมื่อเพิ่มวิธีการจัดส่งสินค้าแล้ว วิธีการจัดส่งสินค้าจะถูกแสดงเป็นตัวเลือกให้ลูกค้าเลือกในหน้าสั่งซื้อสินค้า</p>
    </div>
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

    const shippingMethod = new ShippingMethod();
    shippingMethod.load();

    const form = new Form();
    form.load();

  });

</script>

@stop