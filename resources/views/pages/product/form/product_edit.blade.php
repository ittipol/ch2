@extends('layouts.blackbox.main')
@section('content')

<div class="container">
  
  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          ข้อมูลทั้วไปของสินค้า
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
        echo Form::label('name', 'ชื่อสินค้า', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อสินค้า',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'รายละเอียดของสินค้า');
        echo Form::textarea('description', null, array(
          'class' => 'ckeditor'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('_tags', 'แท๊กที่เกี่ยวข้องกับงานนี้');
      ?>
      <div id="_tags" class="tag"></div>
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

    const images = new Images('_image_group','photo',10,'description');
    images.load({!!$_formData['Image']!!});

    const tagging = new Tagging();
    tagging.load({!!$_formData['Tagging']!!});
    
    const form = new Form();
    form.load();
    
  });    
</script>

@stop