@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}manage/product_catalog" class="btn btn-secondary">กลับไปยังหน้าจัดการแคตตาล็อกสินค้า</a>
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
          เพิ่ม/ลบสินค้าในแคตตาล็อก
        </div>
      </div>
    </div>
  </div>

  <h5>ชื่อแคตตาล็อก</h5>
  <h4>{{$_formData['name']}}</h4>

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
        echo Form::label('', 'สินค้าในแคตตาล็อก', array(
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