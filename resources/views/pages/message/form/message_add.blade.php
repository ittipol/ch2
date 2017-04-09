@extends('layouts.blackbox.main')
@section('content')

<div class="container">
  <div class="container-header">
    <div class="row">
      <div class="col-lg-6 col-sm-12">
        <div class="title">ข้อความถึงผู้สมัคร</div>
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
        echo Form::label('message', 'ข้อความ');
        echo Form::textarea('message', null, array(
          'class' => 'ckeditor'
        ));
      ?>
    </div>

    <div class="form-row">

      <div class="sub-title">แนบไฟล์</div>

      <div class="secondary-message-box error space-bottom-20">
        <div class="secondary-message-box-inner">
          <p>*** ขนาดของไฟล์ต้องไม่เกิน 3 MB</p>
        </div>
      </div>

      <div class="form-row">
        <div id="_file_group"></div>
      </div>

      @if(!empty($sendAs))
        <div class="form-row">
        @foreach($sendAs as $value)
          <label class="choice-box">
            <?php
              echo Form::radio('send_as', $value['value'], $value['select']);
            ?>
            <div class="inner">{{$value['text']}}</div>
          </label>
        @endforeach
        </div>
      @endif

    </div>

  </div>

  <?php
    echo Form::submit('ส่ง' , array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const attachedFile = new AttachedFile('_file_group');
    attachedFile.load();

    const form = new Form();
    form.load();

  });

</script>

@stop