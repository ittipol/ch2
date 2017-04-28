@extends('layouts.blackbox.main')
@section('content')

@include('pages.product.layouts.fixed_top_nav')

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

    <div class="title">
      สถานะการขายสินค้า
    </div>

    <div class="form-row">

      <div class="btn-group">
        <label class="btn">
          <?php
            echo Form::radio('active', 1, true);
          ?>
          <div class="inner">เปิดการขายสินค้านี้</div>
        </label>
        <label class="btn">
          <?php
            echo Form::radio('active', 0);
          ?>
          <div class="inner">ปิดการขายสินค้านี้</div>
        </label>
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