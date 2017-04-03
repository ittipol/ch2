@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.top_nav')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">วิธีการจัดส่งสินค้า</h2>
      </div>
    </div>
  </div>
</div>

<div class="container">

  <div class="tile-nav-group space-top-bottom-20 clearfix">

    <div class="tile-nav small">
      <div class="tile-nav-image">
          <a href="{{$shippingMethodAddUrl}}">
            <img src="/images/common/truck.png">
          </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{$shippingMethodAddUrl}}">
          <h4 class="tile-nav-title">เพิ่มวิธีการจัดส่งสินค้า</h4>
        </a>
      </div>
    </div>

  </div>

  <div class="line"></div>

  @if(empty($pickingUpItem))
    <?php 
      echo Form::open(['url' => $addPickingupItemUrl, 'method' => 'post', 'enctype' => 'multipart/form-data']);
    ?>
    <div class="secondary-message-box info space-top-bottom-20">
      <h3>เปิดการใช้งานตัวเลือก "รับสินค้าเอง"</h3>
      <p>*** หากคุณมีหน้าร้าน สามารถที่จะเพิ่มตัวเลือกให้ลูกค้าสามารถรับสินค้าที่หน้าร้านเองได้</p>
      <?php
        echo Form::submit('เพิ่มตัวเลือก "รับสินค้าเอง"' , array(
          'class' => 'button space-top-10',
          'data-modal' => 1,
          'data-modal-title' => 'ต้องการเพิ่มตัวเลือก "รับสินค้าเอง" ใช่หรือไม่'
        ));
      ?>
    </div>
    <?php
      echo Form::close();
    ?>
  @else
  
    <div class="secondary-message-box success space-top-bottom-20">
      <div class="secondary-message-box-inner">
        <h3>เพิ่มตัวเลือก "รับสินค้าเอง" แล้ว</h3>
        <p>*** ลูกค้าสามารถเลือกตัวเลือก "รับสินค้าเอง" จากหน้าการสั่งซื้อได้แล้ว</p>
      </div>
    </div>

    <div class="list-h">

      <div class="list-h-item clearfix">

        <div class="list-image pull-left">
          <img src="/images/icons/truck-white.png">
        </div>

        <div class="col-md-11 col-xs-8">

          <div class="row">

            <div class="col-md-4 col-xs-12 list-content">
              <h4 class="primary-info">{{$pickingUpItem['name']}}</h4>
              <div class="secondary-info">ผู้ให้บริการการจัดส่ง: {{$pickingUpItem['shippingService']}}</div>
              <div class="secondary-info">ระยะเวลาจัดส่ง: {{$pickingUpItem['shipping_time']}}</div>
            </div>

            <div class="col-md-4 col-xs-12 list-content">
              <h4 class="primary-info">รูปแบบการคิดค่าจัดส่ง</h4>
              <div class="secondary-info">{{$pickingUpItem['shippingServiceCostType']}}</div>
            </div>

            <div class="col-md-4 col-xs-12 list-content">
              <h4 class="primary-info">ค่าบริการ</h4>
              <div class="secondary-info">{{$pickingUpItem['serviceCostText']}}</div>
            </div>

          </div>

        </div>

        <div class="additional-option">
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="additional-option-content">
            <a href="{{$pickingUpItemEditUrl}}">แก้ไข</a>
            <a href="{{$pickingUpItemDeleteUrl}}" data-modal="1" data-modal-title="ต้องการลบใช่หรือไม่">ลบ</a>
          </div>
        </div>

      </div>

    </div>

  @endif

  <div class="line"></div>

  @if(!empty($_pagination['data']))

    <div class="list-h">
    @foreach($_pagination['data'] as $data)
      <div class="list-h-item clearfix">

        <div class="list-image pull-left">
          <img src="/images/icons/truck-white.png">
        </div>

        <div class="col-md-11 col-xs-8">

          <div class="row">

            <div class="col-md-4 col-xs-12 list-content">
              <h4 class="primary-info">{{$data['name']}}</h4>
              <div class="secondary-info">ผู้ให้บริการการจัดส่ง: {{$data['shippingService']}}</div>
              <div class="secondary-info">ระยะเวลาจัดส่ง: {{$data['shipping_time']}}</div>
            </div>

            <div class="col-md-4 col-xs-12 list-content">
              <h4 class="primary-info">รูปแบบการคิดค่าจัดส่ง</h4>
              <div class="secondary-info">{{$data['shippingServiceCostType']}}</div>
            </div>

            <div class="col-md-4 col-xs-12 list-content">
              <h4 class="primary-info">ค่าบริการ</h4>
              <div class="secondary-info">{{$data['serviceCostText']}}</div>
            </div>

          </div>

        </div>
        
        <div class="additional-option">
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="additional-option-content">
            <a href="{{$data['editUrl']}}">แก้ไข</a>
            <a href="{{$data['deleteUrl']}}" data-modal="1" data-modal-title="ต้องการลบใช่หรือไม่">ลบ</a>
          </div>
        </div>

      </div>
    @endforeach
    </div>

    @include('components.pagination')

  @else

    <div class="list-empty-message text-center space-top-20">
      <img class="space-bottom-20" src="/images/common/truck.png">
      <div>
        <h3>วิธีการจัดส่งสินค้า</h3>
        <p>ยังไม่มีวิธีการจัดส่งสินค้า เพิ่มวิธีการจัดส่งสินค้าของคุณ เพื่อให้ลูกค้าสามารถเลือกวิธีการจัดส่งสินค้าได้</p>
        <a href="{{$shippingMethodAddUrl}}" class="button">เพิ่มวิธีการจัดส่งสินค้า</a>
      </div>
    </div>

  @endif

</div>

@stop