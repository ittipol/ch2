@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h4 class="sub-title">เลขที่การสั่งซื้อ {{$order['invoice_number']}}</h4>
        <h2 class="title">รายละเอียดการชำระเงิน</h2>
      </div>
    </div>
  </div>
</div>

<div class="detail container">

  <?php 
    echo Form::model([], [
      'url' => $paymentConfirmUrl,
      'method' => 'PATCH',
      'enctype' => 'multipart/form-data'
    ]);
  ?>

  <div class="text-right space-bottom-20">
    <?php
      echo Form::submit('ยืนยันการชำระเงิน' , array(
        'class' => 'button',
        'data-modal' => 1,
        'data-modal-title' => 'ต้องการยืนยันการชำระเงินเลขที่การสั่งซื้อ '.$order['invoice_number'].' ใช่หรือไม่'
      ));
    ?>
  </div>

  <?php
    echo Form::close();
  ?>

  <div class="row">

    <div class="col-md-4 col-sm-12">

      <div class="detail-group">
        <h4>รายละเอียดการชำระเงิน</h4>
        <div class="line"></div>
        <div class="detail-group-info-section">

          <div class="detail-group-info">
            <h5 class="title">วิธีการชำระเงิน</h5>
            <p>{{$_modelData['paymentMethodName']}}</p>
          </div>

          <div class="detail-group-info">
            <h5 class="title">จำนวนเงิน</h5>
            <p>{{$_modelData['paymentAmount']}}</p>
          </div>

          <div class="detail-group-info">
            <h5 class="title">วันที่ชำระเงิน</h5>
            <p>{{$_modelData['paymentDate']}}</p>
          </div>

          <div class="detail-group-info">
            <h5 class="title">เวลาชำระเงิน</h5>
            <p>{{$_modelData['paymentTime']}}</p>
          </div>

        </div>
      </div>

    </div>

    <div class="col-md-8 col-sm-12">

      <div class="detail-info-section">
        <h4>รายละเอียดเพิ่มเติม</h4>
        <div class="line"></div> 
        <div class="detail-info">
          {!!$_modelData['description']!!}
        </div>
      </div>

    </div>

  </div>

  <div class="image-gallery">

    <div class="row">

      <div class="col-sm-12 image-gallary-display">

        <div class="image-description">
         <div class="image-description-inner">
          <div id="image_description"></div>
         </div>
         <div class="close-image-description-icon"></div>
         <div class="image-description-pagination clearfix">
            <div id="prev_image_description" class="prev-image-description-icon pull-left"></div>
            <div class="pull-left">
              <span id="current_image_description" class="current-page-number"></span>
              <span>/</span>
              <span id="total_image_description" class="total-page-number"></span>
            </div class="pull-left">
            <div id="next_image_description" class="next-image-description-icon pull-left"></div>
          </div>
        </div>

        <div class="image-gallary-display-inner">

          <div class="image-gallary-panel">
            <img id="image_display">
          </div>

          <div class="display-image-description-icon additional-option icon">
            <img src="/images/icons/additional-white.png">
            <div class="additional-option-content">
              <a class="image-description-display-button">แสดงคำอธิบายรูปภาพ</a>
            </div>
          </div>

        </div>
      </div>

    </div>

    @if(!empty($_modelData['Image']))
    <div class="row">
      <div class="col-sm-12">
        <div id="image_gallery_list" class="image-gallery-list clearfix"></div>
      </div>
    </div>
    <div class="line space-top-bottom-20"></div>
    @endif

  </div>

</div>

<script type="text/javascript">
  $(document).ready(function(){
    imageGallery = new ImageGallery(false);
    imageGallery.load({!!$_modelData['Image']!!});
  });
</script>

@stop