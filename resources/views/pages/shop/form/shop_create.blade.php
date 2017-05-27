@extends('layouts.blackbox.main')
@section('content')

  <div class="container">

    <div class="container-header">
      <div class="row">
        <div class="col-lg-7 col-sm-12">
          <div class="title">
            สร้างร้านค้า
          </div>
          <p>สร้างบริษัท ร้านค้าหรือธุรกิจของคุณ เชื่อมต่อธุรกิจของคุณกับผู้คนที่หลากหลาย เพิ่มช่องทางการขายสินค้า เพิ่มมูลค่าของแบรนด์ และอื่นๆ อีกมากมาย</p>
        </div>
      </div>
    </div>

    <div class="line space-top-bottom-30"></div>
      
    @include('components.form_error') 
      
    <div class="row">

      <div class="col-lg-6 col-xs-12">
        <h3>เชื่อมต่อธุรกิจของคุณกับผู้คนที่หลากหลาย</h3>
        <p>เพิ่มธุรกิจของคุณ และให้เราทำหน้าที่เชื่อมต่อธุรกิจของคุณกับผู้คนที่หลากหลาย</p>
      </div>
      <div class="col-lg-6 col-xs-12">
        <h3>ขายสินค้าและกำหนดโปรโมชั่น</h3>
        <p>ขายสินค้าและจัดการสินค้าของคุณ รวมถึงสร้งโปรโมชั่นเพื่อเพิ่มยอดขายของคุณ</p>
      </div>
    </div>

    <div class="line space-top-bottom-30"></div>

    <?php 
      echo Form::open(['id' => 'main_form','method' => 'post', 'enctype' => 'multipart/form-data']);
    ?>

    <?php
      echo Form::hidden('_model', $_formModel['modelName']);
    ?>

    <div class="form-section">

      <div class="form-row">
        <?php 
          echo Form::label('name', 'ชื่อบริษัท ร้านค้าหรือธุรกิจ', array(
            'class' => 'required'
          ));
          echo Form::text('name', null, array(
            'placeholder' => 'ชื่อบริษัท ร้านค้าหรือธุรกิจ',
            'autocomplete' => 'off'
          ));
        ?>
      </div>

      <div class="form-row">
        <?php 
          echo Form::label('description', 'คำอธิบายสั้นๆ เพื่ออธิบายถึงบริษัท ร้านค้า หรือธุรกิจของคุณ');
          echo Form::textarea('description',null,array(
            'class' => 'sm'
          ));
        ?>
      </div>

      <div class="form-row">
      <?php 
        echo Form::label('Contact[phone_number]', 'หมายเลขโทรศัพท์', array(
            'class' => 'required'
        ));
        echo Form::text('Contact[phone_number]', null, array(
          'placeholder' => 'หมายเลขโทรศัพท์',
          'autocomplete' => 'off'
        ));
      ?>
      </div>

    </div>

    <?php
      echo Form::submit('เริ่มต้น' , array(
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