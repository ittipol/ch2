@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper">
  <h2 class="top-header">รปูภาพโปรไฟล์และหน้าปก</h2>
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
        echo Form::label('name', 'รูปภาพโปรไฟล์');
      ?>
      <div id="_profile_image">
      </div>
    </div>

    <div class="form-row">
      <?php
        echo Form::label('name', 'รูปภาพหน้าปก');
      ?>
      <div id="_cover">
      </div>
    </div>

  </div>

  <?php
    echo Form::submit('บันทึก' , array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  $(document).ready(function(){
    const profileImage = new Images('_profile_image','profile-image',1);
    profileImage.load('{!!$profileImage!!}');

    const cover = new Images('_cover','cover',1);
    cover.load('{!!$cover!!}');
  });

</script>

@stop