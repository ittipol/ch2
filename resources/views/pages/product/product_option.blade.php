@extends('layouts.blackbox.main')
@section('content')

  @include('pages.product.layouts.fixed_top_nav')

  <div class="container">

    <div class="container-header">
      <div class="row">
        <div class="col-lg-6 col-sm-12">
          <div class="title">
            ตัวเลือกสินค้า
          </div>
        </div>
      </div>

    </div>

    <div class="tile-nav-group space-top-bottom-20 clearfix">

      <div class="tile-nav small">
        <div class="tile-nav-image">
          <a href="{{$productOptionAdd}}">
            <img src="/images/common/plus.png">
          </a>
        </div>
        <div class="tile-nav-info">
          <a href="{{$productOptionAdd}}">
            <h4 class="tile-nav-title">เพิ่มหัวข้อตัวเลือกสินค้า</h4>
          </a>
        </div>
      </div>

      <div class="tile-nav small">
        <div class="tile-nav-image">
          <a href="{{$productOptionAdd}}">
            <img src="/images/common/plus.png">
          </a>
        </div>
        <div class="tile-nav-info">
          <a href="{{$productOptionAdd}}">
            <h4 class="tile-nav-title">เพิ่มตัวเลือกสินค้า</h4>
          </a>
        </div>
      </div>

    </div>

    <div class="line"></div>

    @if(!empty($_pagination['data']))

      <div class="list-h">
      @foreach($_pagination['data'] as $data)
        <div class="list-h-item clearfix">

          <a class="list-image pull-left">
            <img src="/images/icons/tag-white.png">
          </a>

          <div class="col-md-11 col-xs-8">

            <div class="row">

              <div class="col-md-4 col-xs-12 list-content">
                <h4 class="primary-info">ชื่อตัวเลือก</h4>
                <div>{{$data['name']}}</div>
              </div>

              <div class="col-md-3 col-xs-12 list-content">
                <h4 class="primary-info">จำนวนสินค้าคงเหลือ</h4>
                <div class="secondary-info">{{$data['quantity']}}</div>
              </div>

              <div class="col-md-2 col-xs-12 list-content">
                <h4 class="primary-info">ราคาขาย</h4>
                <div class="secondary-info">{{$data['price']}}</div>
              </div>

              <div class="col-md-3 col-xs-12 list-content">
                <h4 class="primary-info">รูปแบบการแสดง</h4>
                <div class="secondary-info">{{$data['displayType']}}</div>
              </div>

            </div>

          </div>
          
          <div class="additional-option">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="additional-option-content">
              <a href="{{$data['editUrl']}}">แก้ไข</a>
              <a href="{{$data['deleteUrl']}}" data-modal="1" data-modal-title="ต้องการลบตัวเลือก {{$data['name']}} ใช่หรือไม่">ลบ</a>
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
          <h3>ตัวเลือกสินค้า</h3>
          <p>ยังไม่มีตัวเลือกสินค้า</p>
          <a href="{{$productOptionAdd}}" class="button">เพิ่มตัวเลือกสินค้า</a>
        </div>
      </div>

    @endif

  </div>

@stop