@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}manage/advertising" class="btn btn-secondary">กลับไปหน้ารายการโฆษณา</a>
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
        echo Form::label('branch', 'เลือกสาขาที่ลงโฆษณานี้ (เว้นว่างได้)');
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
        echo Form::textarea('description');
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
        echo Form::label('_tags', 'คำที่เกี่ยวข้องหรือสื่อถึงโฆษณานี้');
      ?>
      <div id="_tags" class="tag"></div>
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
      tagging.setTags({!!$_oldInput['Tagging']!!});
    @endif

    const targerArea = new TargetArea();
    targerArea.load();
    @if(!empty($_oldInput['TargetArea']))
      targerArea.setTags({!!$_oldInput['TargetArea']!!});
    @endif
    
    const form = new Form();
    form.load();
    
  });    
</script>

@stop