@extends('layouts.default.main')
@section('content')
  
  <?php 
    echo Form::open(['id' => 'main_form','method' => 'post', 'enctype' => 'multipart/form-data']);
  ?>

  <div style="width: 60%; margin: 0 auto;">

  <?php 
    echo Form::label('pid', 'Parent ID', array(
      'class' => 'required'
    ));
    echo Form::text('pid', null, array(
      'placeholder' => 'Parent ID',
      'autocomplete' => 'off'
    ));
  ?>

  <?php 
    echo Form::label('description');
    echo Form::textarea('description', null, array(
      'style' => 'width: 100%;'
    ));
  ?>

  <br/>

  <?php
    echo Form::submit('ลงประกาศ' , array(
      'class' => 'button'
    ));
  ?>

  </div>

  <?php
    echo Form::close();
  ?>

@stop