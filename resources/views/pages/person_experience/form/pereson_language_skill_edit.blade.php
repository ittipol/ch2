@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{URL::to('experience/profile/edit')}}" class="btn btn-secondary">กลับไปหน้าเพิ่มประวัติการทำงาน</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{URL::to('person/experience')}}">ไปยังหน้าภาพรวมประวัติการทำงาน</a>
            </div>
          </button>
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
          ภาษาที่สามารถสื่อสารได้
        </div>
      </div>
    </div>
  </div>

  <div class="line"></div>

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
      
      <div>
        <h5>ภาษา</h5>
        <?php
          echo Form::select('language_id', $languages);
        ?>
      </div>

      <div>
        <h5>ระดับความสามารถในการใช้งาน</h5>
        <?php
          echo Form::select('language_skill_level_id', $levels);
        ?>
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

@stop