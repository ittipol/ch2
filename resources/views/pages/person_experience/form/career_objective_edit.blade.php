@extends('layouts.blackbox.main')
@section('content')

  <div class="container">

    <div class="container-header">
      <div class="row">
        <div class="col-md-8 col-xs-12">
          <div class="title">
            จุดมุ่งหมายในอาชีพ
          </div>
          <p>บ่งบอกตัวตนและจุดมุ่งหมายในการสมัครงานของคุณ</p>
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
          echo Form::textarea('career_objective', null, array(
            'class' => 'person-experience-textarea'
          ));
        ?>
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

      const form = new Form();
      form.load();

    });

  </script>

@stop