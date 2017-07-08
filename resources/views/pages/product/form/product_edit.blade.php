@extends('layouts.blackbox.main')
@section('content')

@include('pages.product.layouts.fixed_top_nav')

<div class="container">
  
  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          ข้อมูลทั้วไปของสินค้า
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
        echo Form::label('name', 'ชื่อสินค้า', array(
          'class' => 'required'
        ));
        echo Form::text('name', null, array(
          'placeholder' => 'ชื่อสินค้า',
          'autocomplete' => 'off'
        ));
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'รายละเอียดของสินค้า');
        echo Form::textarea('description');
      ?>
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('product_unit', 'หน่วยสินค้า', array(
          'class' => 'required'
        ));
        echo Form::text('product_unit', null, array(
          'placeholder' => 'หน่วยสินค้า (ชิ้น, ตัว, เครื่อง, หลัง)',
          'autocomplete' => 'off'
        ));
      ?>
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

  <div class="form-section">

    <div class="title">
      ระบุพื้นที่เป้าหมาย
    </div>

    <div class="form-row">
      <a data-right-side-panel="1" data-right-side-panel-target="#target_area_panel"><img class="before-text-icon" src="/images/icons/plus-header.png">เพิ่มพื้นที่เป้าหมาย</a>

      <div id="selected_target_area" class="space-top-20"></div>
    
      <div id="target_area_panel" class="right-size-panel">
        <div class="right-size-panel-inner">

          @foreach($provinces as $province)

            <h4>{{$province['name']}}</h4>
            <div class="line"></div>

            <div class="row">
            @foreach($province['data'] as $id => $name)
              <div class="col-lg-4 col-sm-6 col-xs-12">

                <label class="choice-box">
                  <?php
                    echo Form::checkbox('TargetArea[province_id][]', $id, null, array(
                      'id' => 'province_area_chkbox_'.$id,
                      'class' => 'target-area-chkbox',
                      'data-name' => $name
                    ));
                  ?>
                  <div class="inner">{{$name}}</div>
                </label>

              </div>
            @endforeach
            </div>

          @endforeach

          <div class="right-size-panel-close-button"></div>
        </div>
      </div>

    </div>

  </div>

  <div class="form-section">

    <div class="title">
      แท๊ก
    </div>

    <div class="form-row">
      <?php 
        echo Form::label('_tags', 'คำที่เกี่ยวข้องหรือสื่อถึงสินค้านี้');
      ?>
      <div id="_tags" class="tag"></div>
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

    const targerArea = new TargetArea();
    targerArea.load();
    targerArea.setTags({!!$_formData['TargetArea']!!});
    
    const form = new Form();
    form.load();
    
  });    
</script>

@stop