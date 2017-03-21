@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h4 class="sub-title">เลขที่การสั่งซื้อ {{$invoiceNumber}}</h4>
        <h2 class="title">ยืนยันการชำระเงิน</h2>
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
        echo Form::label('', 'เลือกวิธีที่คุณชำระเงิน', array(
          'class' => 'required'
        ));
      ?>
      <div class="row">
        <?php 
          foreach ($paymentMethods as $id => $name):
        ?>
          <div class="col-sm-12">
            <label class="choice-box">
              <?php
                echo Form::radio('payment_method_id', $id);
              ?>
              <div class="inner">{{$name}}</div>
            </label>
          </div>
        <?php
          endforeach;
        ?>
      </div>

    </div>

    <div class="form-row">
      <?php 
        echo Form::label('description', 'รายละเอียดการชำระเงิน', array(
          'class' => 'required'
        ));
        echo Form::textarea('description', null, array(
          'class' => 'ckeditor'
        ));
      ?>
    </div>

    <div class="form-row">

      <?php
        echo Form::label('', 'รูปภาพ');
      ?>

      <div class="form-row">
        <div id="_image_group">
        </div>
      </div>

    </div>

  </div>

  <?php
    echo Form::submit('ยืนยันการชำระเงิน' , array(
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

    const form = new Form();
    form.load();

  });

</script>

@stop