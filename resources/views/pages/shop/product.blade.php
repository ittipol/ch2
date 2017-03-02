@extends('layouts.blackbox.main')
@section('content')

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
        <a href="{{$productPostUrl}}">
          <img src="/images/common/tag.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{$productPostUrl}}">
          <h4 class="tile-nav-title">เพิ่มสินค้า</h4>
        </a>
      </div>
    </div>

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{$productPostUrl}}">
          <img src="/images/common/sale2.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{$productPostUrl}}">
          <h4 class="tile-nav-title">เพิ่มโปรโมชั่น</h4>
        </a>
      </div>
    </div>

  </div>

  <div class="line"></div>

  @if(!empty($_pagination['data']))

    <div class="grid-card">

      <div class="row">

        @foreach($_pagination['data'] as $data)

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
          <div class="card">
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

                <div class="card-sub-info-row">
                  <h5>จำนวนสินค้าคงเหลือ</h5>
                  @if($data['unlimited_quantity'])
                  {{$data['_unlimited_quantity']}}
                  @else
                  {{$data['quantity']}}
                  @endif
                </div>

                <div class="card-sub-info-row">
                  <h5>สถานะการขาย</h5>
                  {{$data['_active']}}
                </div>

              </div>
            </div>

            <div class="button-group">

              <a href="{{$data['detailUrl']}}">
                <div class="button wide-button">แสดงรายละเอียด</div>
              </a>

              <div class="additional-option">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="additional-option-content">
                  <a href="{{$data['menuUrl']}}">ไปยังหน้าจัดการสินค้านี้</a>
                  <a href="{{$data['deleteUrl']}}">ลบ</a>
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
      <img class="space-bottom-20" src="/images/common/tag.png">
      <div>
        <h3>สินค้า</h3>
        <p>ยังไม่มีสินค้า เพิ่มสินค้า เพื่อขายสินค้าของคุณ</p>
        <a href="{{$productPostUrl}}" class="button">เพิ่มสินค้า</a>
      </div>
    </div>

  @endif

</div>

@stop