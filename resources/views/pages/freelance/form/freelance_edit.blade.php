@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{URL::to('experience/profile/edit')}}" class="btn btn-secondary">กลับไปหน้างานฟรีแลนซ์ที่ประกาศ</a>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="container">
  
  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          แก้ไขงานฟรีแลนซ์
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

    <div class="form-row">
      <?php 
        echo Form::label('branch', 'เลือกประเภทงานฟรีแลนซ์', array(
          'class' => 'required'
        ));
      ?>
      <div class="form-item-group">
        <div class="form-item-group-inner">
          <div class="row">
              @foreach ($_fieldData['freelanceTypes'] as $id => $freelanceType)
              <div class="col-lg-4 col-sm-6 col-sm-12">
                <label class="choice-box">
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
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('name', 'ชื่องานฟรีแลนซ์', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่องานฟรีแลนซ์',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('default_wage', 'อัตราค่าจ้างเริ่มต้น', array(
          'class' => 'required'
        ));
        echo Form::text('default_wage', null, array(
          'placeholder' => 'อัตราค่าจ้างเริ่มต้น',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'รายละเอียดข้อตกลง');
        echo Form::textarea('description');
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

    const images = new Images('_image_group','photo',10,'description');
    images.load({!!$_formData['Image']!!});

    const tagging = new Tagging();
    tagging.load({!!$_formData['Tagging']!!});
    
    const form = new Form();
    form.load();
    
  });    
</script>

@stop