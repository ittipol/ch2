@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.fixed_top_nav_admin')

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-lg-6 col-sm-12">
        <div class="title">
          สินค้า
        </div>
      </div>
    </div>
  </div>

  <div class="tile-nav-group space-top-bottom-20 clearfix">

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{request()->get('shopUrl')}}product/add">
          <img src="/images/common/plus.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{request()->get('shopUrl')}}product/add">
          <h4 class="tile-nav-title">เพิ่มสินค้า</h4>
        </a>
      </div>
    </div>

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{request()->get('shopUrl')}}manage/product_catalog">
          <img src="/images/common/tag.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{request()->get('shopUrl')}}manage/product_catalog">
          <h4 class="tile-nav-title">แคตตาล็อกสินค้า</h4>
        </a>
      </div>
    </div>

    <div class="tile-nav small">
      <div class="tile-flag-count">{{$countOrder}}</div>

      <div class="tile-nav-image">
        <a href="{{request()->get('shopUrl')}}order">
          <img src="/images/common/bag.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{request()->get('shopUrl')}}order">
          <h4 class="tile-nav-title">รายการสั่งซื้อ</h4>
        </a>
      </div>
    </div>

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{request()->get('shopUrl')}}payment_method">
          <img src="/images/common/payment.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{request()->get('shopUrl')}}payment_method">
          <h4 class="tile-nav-title">วิธีการชำระเงิน</h4>
        </a>
      </div>
    </div>

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{request()->get('shopUrl')}}shipping_method">
          <img src="/images/common/truck.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{request()->get('shopUrl')}}shipping_method">
          <h4 class="tile-nav-title">วิธีการจัดส่งสินค้า</h4>
        </a>
      </div>
    </div>

  </div>

  <div class="line"></div>

  @if(!empty($_pagination['data']))
  
    <div class="grid-card">

      <div class="row">

        @foreach($_pagination['data'] as $data)

        <div class="col-md-3 col-xs-6">
          <div class="card sm">

            @if(!empty($data['flag']))
              <div class="flag-wrapper">
                <div class="flag sale-promotion">{{$data['flag']}}</div>
              </div>
            @endif

            <div class="image-tile">
              <a href="{{$data['detailUrl']}}">
                <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
              </a>
            </div>
            <div class="card-info">
              <a href="{{$data['detailUrl']}}">
                <div class="card-title">{{$data['name']}}</div>
              </a>
              <div class="card-sub-info">

                <div class="card-sub-info-row product-price-section">
                  @if(!empty($data['promotion']))
                    <span class="product-price">{{$data['promotion']['_reduced_price']}}</span>
                    <span class="product-price-discount-tag">{{$data['promotion']['percentDiscount']}}</span>
                    <h5 class="origin-price">{{$data['_price']}}</h5>
                  @else
                    <span class="product-price">{{$data['_price']}}</span>
                  @endif
                </div>

                <div class="card-sub-info-row">
                  <h5>จำนวนสินค้าคงเหลือ</h5>
                  {{$data['quantity']}}
                </div>

                <div class="card-sub-info-row">
                  <h5>สถานะการขาย</h5>
                  {{$data['_active']}}
                </div>

              </div>
            </div>

            <div class="button-group">

              <a href="{{$data['menuUrl']}}">
                <div class="button wide-button">จัดการสินค้านี้</div>
              </a>

              <div class="additional-option">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="additional-option-content">
                  <a href="{{$data['detailUrl']}}">แสดงสินค้านี้</a>
                  <a href="{{$data['deleteUrl']}}" data-modal="1" data-modal-title="ต้องการลบ {{$data['name']}} ใช่หรือไม่">ลบสินค้า</a>
                </div>
              </div>
            
            </div>

          </div>
        </div>

        @endforeach

      </div>

      @include('components.pagination') 

    </div>

  @else

    <div class="list-empty-message text-center space-top-20">
      <img src="/images/common/not-found.png">
      <div>
        <h3>สินค้า</h3>
        <p>ยังไม่มีสินค้า</p>
        <a href="{{request()->get('shopUrl')}}product/add" class="button">เพิ่มสินค้า</a>
      </div>
    </div>

  @endif

</div>

@stop