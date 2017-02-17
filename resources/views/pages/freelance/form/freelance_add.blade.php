@extends('layouts.blackbox.main')
@section('content')

<div class="container">
  
  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          ฟรีแลนซ์
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

    <div class="form-row">
      <?php 
        echo Form::label('branch', 'เลือกประเภทฟรีแลนซ์', array(
          'class' => 'required'
        ));
      ?>
      <div class="form-item-group">
        <div class="row">
            @foreach ($_fieldData['freelanceTypes'] as $id => $freelanceType)
            <div class="col-lg-4 col-md-6 col-sm-6 col-sm-12">
              <label class="box">
                <?php
                  echo Form::radio('freelance_type_id', $id);
                ?>
                <div class="inner"><?php echo $freelanceType; ?></div>
              </label>
            </div>
            @endforeach
        </div>
      </div>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('name', 'ชื่องานฟรีแลนซ์ที่รับทำ', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่องานฟรีแลนซ์ที่รับทำ',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'รายละเอียดข้อตกลง');
        echo Form::textarea('description', null, array(
          'class' => 'ckeditor'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('_tags', 'แท๊กที่เกี่ยวข้องฟรีแลนซ์นี้');
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
    echo Form::submit('ลงประกาศ', array(
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