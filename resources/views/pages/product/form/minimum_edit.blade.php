@extends('layouts.blackbox.main')
@section('content')

@include('pages.product.layouts.top_nav')

<div class="container">
  
  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          การสั่งซื้อขั้นต่ำ
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
      จำนวนการสั่งซื้อขั้นต่ำ
    </div>

    <div class="form-row">

      <div class="form-row">
        <?php 
          echo Form::label('minimum', 'จำนวนการซื้อขั้นต่ำต่อการสั่งซื้อ', array(
            'class' => 'required'
          ));
          echo Form::text('minimum', 1, array(
            'placeholder' => 'จำนวนการซื้อขั้นต่ำต่อการสั่งซื้อ',
            'autocomplete' => 'off'
          ));
        ?>
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