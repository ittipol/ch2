@extends('layouts.blackbox.main')
@section('content')
  
<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          ทักษะและความสามารถ
        </div>
      </div>
    </div>
  </div>

  <div class="line"></div>

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
        echo Form::text('skill', null, array(
          'placeholder' => 'ทักษะและความสามารถ',
          'autocomplete' => 'off'
        ));
      ?>
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

@stop