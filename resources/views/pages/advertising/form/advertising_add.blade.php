@extends('layouts.blackbox.main')
@section('content')

<div class="container">
  
  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          ลงประกาศโฆษณา
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

    @if(!empty($_fieldData['branches']))
    <div class="form-row">
      <?php 
        echo Form::label('branch', 'เลือกสาขาที่ลงโฆษณานี้ (สามารถเว้นว่างได้)');
      ?>
      <div class="form-item-group">
        <div class="form-item-group-inner">
          <div class="row">
              @foreach ($_fieldData['branches'] as $id => $branch)
              <div class="col-lg-4 col-sm-6 col-sm-12">
                <label class="choice-box">
                  <?php
                    echo Form::checkbox('RelateToBranch[branch_id][]', $id);
                  ?>
                  <div class="inner"><?php echo $branch; ?></div>
                </label>
              </div>
              @endforeach
          </div>
        </div>
      </div>
    </div>
    @endif

    <div class="form-row">
      <?php 
        echo Form::label('name', 'ชื่อโฆษณาหรือหัวข้อโฆษณา', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อโฆษณา หรือหัวข้อโฆษณา',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php
        echo Form::label('advertising_type_id', 'ประเภทของโฆษณา', array(
          'class' => 'required'
        ));
      ?>
      @foreach ($_fieldData['advertisingTypes'] as $id => $advertisingType)
        <label class="choice-box">
          <?php
            echo Form::radio('advertising_type_id', $id);
          ?>
          <div class="inner">{{$advertisingType}}</div>
        </label>
      @endforeach
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'รายละเอียดโฆษณา');
        echo Form::textarea('description', null, array(
          'class' => 'ckeditor'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('_tags', 'แท๊กที่เกี่ยวข้องกับโฆษณานี้');
      ?>
      <div id="_tags" class="tag"></div>
    </div>

  </div>

  <div class="form-section">

    <div class="title">
      รูปภาพ
    </div>

    <div class="form-row">
      <div id="_image_group"></div>
    </div>

  </div>

  <?php
    echo Form::submit('ลงโฆษณา', array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const images = new Images('_image_group','photo',10,'description');
    images.load();

    const tagging = new Tagging();
    tagging.load();
    @if(!empty($_oldInput['Tagging']))
      tagging.setTags('{!!$_oldInput['Tagging']!!}');
    @endif
    
    const form = new Form();
    form.load();
    
  });    
</script>

@stop