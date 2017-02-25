@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-lg-6 col-sm-12">
        <div class="title">
          เพิ่มสินค้าที่ต้องการขาย
        </div>
        <div><a href="{{URL::to('entity/create')}}">สร้างร้านค้าของคุณ</a> เพื่อการใช้งานที่ดีขึ้น</div>
        <div>คุณสามารถสร้างร้านค้าของคุณเพื่อจัดการสินค้า เพิ่มโปรโมชั่น ตรวจรายการสั้งซื้อ และอื่นๆ</div>
      </div>
    </div>
  </div>

  @include('components.form_error') 

  <?php 
    echo Form::open(['id' => 'main_form','method' => 'post', 'enctype' => 'multipart/form-data']);
  ?>

  <?php
    echo Form::hidden('model', $formModel['modelName']);
  ?>

  <div class="form-section">

    <div class="form-row">
      <?php 
        echo Form::label('name', 'ชื่อสินค้าที่ต้องการขาย', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อสินค้าที่ต้องการขาย',
          'autocomplete' => 'off'
        ));
      ?>
      <p class="notice info">ชื่อจะมีผลโดยตรงต่อการค้นหา</p>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('product_category_id', 'หมวดหมู่หลักสินค้า', array(
          'class' => 'required'
        ));
      ?>
      <div class="form-item-group">
        <div class="form-item-group-inner">
          <div class="row">
            <?php 
              foreach ($fieldData['productCategories'] as $id => $category):
            ?>
              <div class="col-lg-4 col-md-6 col-sm-6 col-sm-12">
                <label class="choice-box">
                  <input type="radio" name="product_category_id" value="<?php echo $id; ?>" >  
                  <div class="inner"><?php echo $category; ?></div>
                </label>
              </div>
            <?php
              endforeach;
            ?>
          </div>
        </div>
      </div>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('tagging', 'หมวดหมู่ย่อยสินค้า', array(
          'class' => 'required'
        ));
      ?>
      <div id="_sub_cat" class="tag"></div>
      <p class="notice info">หมวดหมู่ย่อยสินค้าจะมีผลโดยตรงต่อการค้นหา</p>

    </div>

    <div class="form-row">
      <?php 
        echo Form::label('price', 'ราคาสินค้าที่ต้องการขาย', array(
          'class' => 'required'
        ));
        echo Form::text('price', null, array(
          'placeholder' => 'ราคาสินค้าที่ต้องการขาย',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'รายละเอียดสินค้า');
        echo Form::textarea('description', null, array(
          'class' => 'ckeditor'
        ));
      ?>
    </div>

    <div class="form-row">

      <div class="sub-title">รูปภาพ</div>

      <div>
        <p class="error-message">* รองรับไฟล์ jpg jpeg png</p>
        <p class="error-message">* รองรับรูปภาพขนาดไม่เกิน 3MB</p>
      </div>

      <div class="form-row">
        <div id="_image_group">
        </div>
      </div>

    </div>

    <div class="form-section">

      <div class="title">
        ตำแหน่งสินค้า
      </div>

      <div class="form-row">
        <?php 
          echo Form::label('province', 'จังหวัด');
          echo Form::text('province', 'ชลบุรี', array(
            'placeholder' => 'จังหวัด',
            'autocomplete' => 'off',
            'disabled' => 'disabled'
          ));
        ?>
      </div>

      <div class="form-row">
        <?php 
          echo Form::label('Address[district_id]', 'อำเภอ', array(
            'class' => 'required'
          ));
          echo Form::select('Address[district_id]', $fieldData['districts'] ,null, array(
            'id' => 'district'
          ));
        ?>
      </div>

      <div class="form-row">
        <?php 
          echo Form::label('Address[sub_district_id]', 'ตำบล', array(
            'class' => 'required'
          ));
          echo Form::select('Address[sub_district_id]', array() , null, array(
            'id' => 'sub_district'
          ));
        ?>
      </div>

      <div class="form-row">
        <?php echo Form::label('', 'ระบุตำแหน่งบริษัท องค์กร หรือ ธุรกิจชุมชนบนแผนที่'); ?>
        <input id="pac-input" class="controls" type="text" placeholder="Search Box">
        <div id="map"></div>
      </div>

    </div>

  </div>

  <?php
    echo Form::submit('ลงประกาศขาย' , array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  $(document).ready(function(){
    const images = new Images('_image_group',6);
    const district = new District();
    const map = new Map();
    const tagging = new Tagging('_sub_cat','SubCategory','หมวดหมู่ย่อย');
    const form = new Form();

    images.load();
    district.load();
    map.initialize();
    tagging.load();
    form.load();

  });

</script>

@stop