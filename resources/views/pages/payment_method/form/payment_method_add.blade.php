@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-lg-12">
        <div class="title">
          เพิ่มวิธีการชำระเงิน
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
        echo Form::label('name', 'ชื่อวิธีการชำระเงิน', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อสาขา',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'รายละเอียดวิธีการชำระเงิน', array(
          'class' => 'required'
        ));
        echo Form::textarea('description', null, array(
          'class' => 'ckeditor'
        ));
      ?>
    </div>

    <div class="form-row">

      <?php
        echo Form::label('', 'รูปภาพ');
      ?>

      <div class="form-row">
        <div id="_image_group">
        </div>
      </div>

    </div>

  </div>

  <?php
    echo Form::submit('เพิ่มวิธีการชำระเงิน' , array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  $(document).ready(function(){
    
    const images = new Images('_image_group','photo',10);
    images.load();

    const form = new Form();
    form.load();

  });

</script>

@stop