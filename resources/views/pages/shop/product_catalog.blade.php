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
        <a href="{{request()->get('shopUrl')}}product_catalog/add">
          <img src="/images/common/plus.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{request()->get('shopUrl')}}product_catalog/add">
          <h4 class="tile-nav-title">สร้างแคตตาล็อกสินค้า</h4>
        </a>
      </div>
    </div>

  </div>

  <div class="line"></div>

  @if(!empty($_pagination['data']))

    <div class="list-h">
    @foreach($_pagination['data'] as $data)
      <div class="list-h-item clearfix">

        <a href="{{$data['detailUrl']}}" class="list-image pull-left">
          <img src="/images/icons/bag-white.png">
        </a>

        <div class="col-md-11 col-xs-8">

          <a href="{{$data['detailUrl']}}">
            <h4 class="primary-info">{{$data['name']}}</h4>
          </a>
          <div class="secondary-info">จำนวนสินค้าในแคตตาล็อก: {{$data['totalProduct']}}</div>

        </div>
        
        <div class="additional-option">
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="additional-option-content">
            <a href="{{$data['menuUrl']}}">จัดการแคตตาล็อกนี้</a>
            <a href="{{$data['catalogEditUrl']}}">เพิ่ม/ลบสินค้าในแคตตาล็อก</a>
            <a href="{{$data['deleteUrl']}}">ลบแคตตาล็อก</a>
          </div>
        </div>

      </div>
    @endforeach
    </div>

    @include('components.pagination')

  @else

  <div class="list-empty-message text-center space-top-20">
    <img src="/images/common/not-found.png">
    <div>
      <h3>แคตตาล็อกสินค้า</h3>
      <p>ยังไม่มีแคตตาล็อกสินค้า</p>
      <a href="{{request()->get('shopUrl')}}/product_catalog/add" class="button">สร้างแคตตาล็อกสินค้า</a>
    </div>
  </div>

  @endif

</div>

@stop