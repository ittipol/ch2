@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}manage/product/catalog" class="btn btn-secondary">กลับไปยังหน้าจัดการแคตตาล็อกสินค้า</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}manage/product">กลับไปยังหน้าหลักจัดการสินค้า</a>
              <a href="{{request()->get('shopUrl')}}manage">ไปยังหน้าจัดการหลัก</a>
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
      <div class="col-md-8 col-xs-12">
        <div class="title">
          เพิ่มแคตตาล็อกสินค้า
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
        echo Form::label('name', 'ชื่อแคตตาล็อก', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อแคตตาล็อก',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'คำอธิบายของแคตตาล็อก');
        echo Form::textarea('description');
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('real_estate_type_id', 'สินค้าในแคตตาล็อก', array(
          'class' => 'required'
        ));
      ?>
      <div class="form-item-group">
        <div class="form-item-group-inner">
          <div class="row">
            <?php 
              foreach ($_fieldData['products'] as $id => $name):
            ?>
              <div class="col-sm-12">
                <label class="choice-box">
                  <?php
                    echo Form::checkbox('ProductToProductCatalog[product_id][]', $id);
                  ?>
                  <div class="inner">{{$name}}</div>
                </label>
              </div>
            <?php
              endforeach;
            ?>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="form-section">

    <div class="title">
      รูปภาพแบนเนอร์ (1200 x 300)
    </div>

    <div class="form-row">
      <p class="error-message">*** หากรูปภาพแบนเนอร์มีขนาดเล็กกว่าขนาดที่แนะนำ รูปภาพแบนเนอร์จะถูกขยายให้เต็มกรอบเมื่อแสดงรูปภาพ</p>
      <div id="_image_group"></div>
    </div>

  </div>

  <div class="form-section">

    <div class="title">
      แท๊ก
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('_tags', 'แท๊กที่เกี่ยวข้องกับสินค้านี้');
      ?>
      <div id="_tags" class="tag"></div>
    </div>

  </div>

  <?php
    echo Form::submit('เพิ่มแคตตาล็อกสินค้า', array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const images = new Images('_image_group','banner',1,'banner');
    images.load();

    const tagging = new Tagging();
    tagging.load();
    @if(!empty($_oldInput['Tagging']))
      tagging.setTags('{!!$_oldInput['Tagging']!!}');
    @endif

    const form = new Form();
    form.load();
    
  });    
</script>

@stop