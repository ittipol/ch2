@extends('layouts.blackbox.main')
@section('content')

@include('pages.product.layouts.top_nav')

<div class="container">
  
  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          หมวดสินค้า
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
        echo Form::label('category', 'เลือกหมวดสินค้า');
      ?>
      <div class="line space-bottom-20"></div>
      <h4>หมวดสินค้าที่เลือก</h4>
      <div id="category_selected" class="category-name">-</div>
      <div id="category_panel" class="product-category-list"></div>

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

    const productCategory = new ProductCategory('category_panel','category_selected');
    productCategory.setCatId({{$categoryId}});
    productCategory.setCatPath({!!$categoryPaths!!});
    productCategory.load();

    const form = new Form();
    form.load();
    
  });    
</script>

@stop