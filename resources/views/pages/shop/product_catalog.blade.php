@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.fixed_top_nav_admin')

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

    <div class="grid-card">

      <div class="row">

        @foreach($_pagination['data'] as $data)

        <div class="col-md-4 col-xs-6">
          <div class="card">

            <div class="image-tile">
              <a href="{{$data['detailUrl']}}">
                <div class="card-image cover" style="background-image:url({{$data['_imageUrl']}});"></div>
              </a>
            </div>
            <div class="card-info">
              <a href="{{$data['detailUrl']}}">
                <div class="card-title">{{$data['name']}}</div>
              </a>
              <div class="card-sub-info">

                <div class="card-sub-info-row">
                  <h5>จำนวนสินค้าในแคตตาล็อก</h5>
                  {{$data['totalProduct']}}
                </div>

              </div>
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
                  <a href="{{$data['detailUrl']}}">แสดงแคตตาล็อกนี้</a>
                  <a href="{{$data['catalogEditUrl']}}">เพิ่ม/ลบสินค้าในแคตตาล็อก</a>
                  <a href="{{$data['deleteUrl']}}" data-modal="1" data-modal-title="ต้องการลบแคตตาล็อก {{$data['name']}} ใช่หรือไม่">ลบแคตตาล็อก</a>
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
      <a href="{{request()->get('shopUrl')}}/product_catalog/add" class="button">สร้างแคตตาล็อกสินค้า</a>
    </div>
  </div>

  @endif

</div>

@stop