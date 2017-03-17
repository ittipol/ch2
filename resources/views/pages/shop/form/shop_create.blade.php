@extends('layouts.blackbox.main')
@section('content')

  <div class="container">

    <div class="container-header">
      <div class="row">
        <div class="col-lg-7 col-sm-12">
          <div class="title">
            ชุมชน
          </div>
          <p>นำบริษัท ร้านค้าหรือธุรกิจของคุณเข้าสู่ชุมชน เพื่อเชื่อมต่อธุรกิจของคุณกับชุมชนและผู้คนในชลบุรี เพื่อสะดวกต่อการค้นหาบิษัท ร้านค้า สินค้า แบรนด์ งานบริการ ตำแหน่งงาน และอื่นๆ อีกมากมาย</p>
        </div>
      </div>
    </div>

    <div class="line space-top-bottom-30"></div>
      
    @include('components.form_error') 
      
    <div class="row">

      <div class="col-lg-6 col-xs-12">
        <h3>เชื่อมต่อธุรกิจของคุณกับผู้คนในขลบุรี</h3>
        <p>เพิ่มธุรกิจของคุณ และให้เราทำหน้าที่เชื่อมต่อธุรกิจของคุณกับผู้คนในขลบุรี</p>
      </div>
      <div class="col-lg-6 col-xs-12">
        <h3>ขายสินค้าและกำหนดโปรโมชั่น</h3>
        <p>ขายสินค้าและจัดการสินค้าของคุณ รวมถึงสร้งโปรโมชั่นเพื่อเพิ่มยอดขายของคุณ</p>
      </div>
      <div class="col-lg-6 col-xs-12">
        <h3>โฆษณาธุรกิจและงานบริการ</h3>
        <p>โฆษณาสินค้า แบรนด์ งานบริการและอื่นๆ เพื่อให้ลูกทราบถึงธุรกิจงานบริการต่างๆของคุณ</p>
      </div>
      <div class="col-lg-6 col-xs-12">
        <h3>ค้นหาและเข้าถึงบริษัทหรือร้านค้าของคุณ</h3>
        <p>บริษัทและร้านค้าของคุณจะถูกเก็บและถูกระบุบนแผนที่เพื่อง่ายต่อการค้นหาและเข้าถึง</p>
      </div>
      <div class="col-lg-6 col-xs-12">
        <h3>ลงประกาศงาน</h3>
        <p>ลงประกาศงานเพื่อหาพนักงานใหม่ๆ หรือ ค้นหาโดยตรงจากประวัติการทำของบุคคลนั้นๆ<br/>รวมถึงการจัดการและตรวจสอบรายชื่อผู้ที่สนใจงานของคุณ</p>
      </div>
    </div>

    <div class="line space-top-bottom-30"></div>

    <div class="container-header">
      <div class="row">
        <div class="col-lg-7 col-sm-12">
          <div class="title">
            เพิ่มบริษัท ร้านค้า หรือธุรกิจของคุณ
          </div>
          <p>รวมสร้างชุมชน นำสินค้าของคุณมาขายในชุมชน ค้นหาพนักงานให้กับธุรกิจของคุณ รวมถึงการโฆษณาแบรนด์ ธุรกิจ หรืองานบริการของคุณ</p>
        </div>
      </div>
    </div>

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

@stop