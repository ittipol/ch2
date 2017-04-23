@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.top_nav')

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-lg-6 col-sm-12">
        <div class="title">
          แคตตาล็อกสินค้า
        </div>
      </div>
    </div>
  </div>

  <div class="tile-nav-group space-top-bottom-20 clearfix">

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{request()->get('shopUrl')}}product/catalog/add">
          <img src="/images/common/plus.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{request()->get('shopUrl')}}product/catalog/add">
          <h4 class="tile-nav-title">เพิ่มแคตตาล็อกสินค้า</h4>
        </a>
      </div>
    </div>

  </div>

  <div class="line"></div>

  @if(!empty($_pagination['data']))

    <div class="grid-card">

      <div class="row">

        @foreach($_pagination['data'] as $data)

        <div class="col-lg-3 col-sm-4 col-xs-12">
          <div class="card">

            @if(!empty($data['flag']))
              <div class="flag-wrapper">
                <div class="flag sale-promotion">{{$data['flag']}}</div>
              </div>
            @endif

            <div class="image-tile">
              <a href="{{$data['menuUrl']}}">
                <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
              </a>
            </div>
            <div class="card-info">
              <a href="{{$data['menuUrl']}}">
                <div class="card-title">{{$data['name']}}</div>
              </a>
            </div>

            <div class="button-group">

              <a href="{{$data['menuUrl']}}">
                <div class="button wide-button">จัดการแคตตาล็อกนี้</div>
              </a>

              <div class="additional-option">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="additional-option-content">
                  <a href="{{$data['deleteUrl']}}">แก้ไขข้อมูลทั่วไป</a>
                  <a href="{{$data['deleteUrl']}}">เพิ่ม/ลบสินค้าในแคตตาล็อก</a>
                  <a href="{{$data['deleteUrl']}}">ลบแคตตาล็อก</a>
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
        <h3>แคตตาล็อกสินค้า</h3>
        <p>ยังไม่มีแคตตาล็อกสินค้า</p>
        <a href="{{request()->get('shopUrl')}}/product/catalog/add" class="button">เพิ่มแคตตาล็อกสินค้า</a>
      </div>
    </div>

  @endif

</div>

@stop