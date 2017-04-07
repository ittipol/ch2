@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">เว็บไซต์ส่วนตัว</h2>
        <div class="tag-group">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container">

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
        echo Form::label('websites', 'เว็บไซต์ส่วนตัว');
      ?>
      <div id="website_input" class="text-group">
        <div class="text-group-panel"></div>
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

    const textInputStack = new TextInputList('website_input','private_websites','เว็บไซต์',{!!$_fieldData['websiteTypes']!!});
    textInputStack.load({!!$_formData['private_websites']!!});
    
    const form = new Form();
    form.load();

  });

</script>

@stop