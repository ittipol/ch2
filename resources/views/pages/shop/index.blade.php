@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.top_nav')
@include('pages.shop.layouts.header')

<div class="shop-content-wrapper">

  <div class="container">

    @if(!empty($permission['edit']) && $permission['edit'])
    <div class="box">
      <div class="box-header">
        <h4 class="box-header-title">
          <img class="icon-before-title" src="/images/icons/edit-blue.png">ปักหมุดข้อความ
        </h4>
      </div>
      <textarea class="pin-message-input"></textarea>
      <div class="box-footer text-right">
        <a href="" class="button">โพสต์</a>
      </div>
    </div>
    @endif

    <div class="box">
      <div class="box-header">
        <h4 class="box-header-title">
          <img class="icon-before-title" src="/images/icons/edit-blue.png">สินค้า
        </h4>
      </div>

      <div class="box-content padding-15">

        <div class="row">

          @foreach($products as $data)

          <div class="col-lg-4 col-sm-4 col-xs-12">
            <div class="card">

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

                </div>
              </div>

              <div class="button-group">

                <a href="{{$data['detailUrl']}}">
                  <div class="button wide-button">รายละเอียดสินค้า</div>
                </a>
              
              </div>

            </div>
          </div>

          @endforeach

        </div>

      </div>

    </div>

    <a href="{{request()->get('shopUrl')}}product" class="button wide-button">แสดงสินค้าทั้งหมด</a>

  </div>

</div>

@stop