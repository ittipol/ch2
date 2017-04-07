@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">แก้ไขเว็บไซต์ส่วนตัว</h2>
        <div class="tag-group">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container">

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
        echo Form::label('websites', 'เว็บไซต์ส่วนตัว');
      ?>
      <div id="website_input" class="text-group">
        <div class="text-group-panel">
          <div class="text-input-wrapper">
            <?php
              echo Form::select('website_type_url', $_fieldData['websiteTypes']);
              echo Form::text('website_url', null, array(
                'placeholder' => 'เว็บไซต์',
                'autocomplete' => 'off'
              ));
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