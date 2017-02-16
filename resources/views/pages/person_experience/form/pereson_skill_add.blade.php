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
    echo Form::open(['id' => 'main_form','method' => 'post', 'enctype' => 'multipart/form-data']);
  ?>

  <?php
    echo Form::hidden('_model', $_formModel['modelName']);
  ?>

  <div class="form-section">

    <div class="form-row">
      <div id="skills_input" class="text-group">
        <div class="text-group-panel">
        </div>
        <a href="javascript:void(0);" class="text-add">เพิ่ม +</a>
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
    const textInputStack = new TextInputStack('skills_input','skills','ทักษะและความสามารถ');
    textInputStack.load();
    textInputStack.enableCheckingEmpty();
  });

</script>

@stop