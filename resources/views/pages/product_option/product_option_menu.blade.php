@extends('layouts.blackbox.main')
@section('content')

  @include('pages.product.layouts.fixed_top_nav')

  <div class="container">

    <div class="container-header">
      <div class="row">
        <div class="col-lg-6 col-sm-12">
          <div class="title">
            หัวข้อตัวเลือกสินค้า
          </div>
        </div>
      </div>

    </div>

  </div>

  <div class="top-header-wrapper no-margin">
    <div class="container">
      <div class="top-header">

        <div class="row">
          <div class="header-info col-sm-12 no-margin">
            <h3>{{$productOption['name']}}</h3>
          </div>
        </div>

        <div class="additional-option">
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="additional-option-content">
            <a href="{{$productOption['editUrl']}}">แก้ไข</a>
            <a href="{{$productOption['deleteUrl']}}" data-modal="1" data-modal-title="ต้องการลบหัวข้อตัวเลือก {{$productOption['name']}} ใช่หรือไม่">ลบ</a>
          </div>
        </div>
        
      </div>

      <div class="line"></div>

    </div>
  </div>

  <div class="container">

    <div class="list-item-group no-padding">

      <div class="list-item">
        <a href="{{$productOptionValueAdd}}">
          <img class="icon" src="/images/common/pencil.png">
          <h4>เพิ่มตัวเลือกของ "{{$productOption['name']}}"</h4>
        </a>
      </div>

    </div>

    <div>
      <h3>ตัวเลือก{{$productOption['name']}}</h3>

      <h2>{{$totalOptionValue}}</h2>
      <h5>รายการตัวเลือก</h5>

      @if(!empty($productOptionValues))

        <div class="list-h">
        @foreach($productOptionValues as $data)
          <div class="list-h-item list-h-sm clearfix">

            <a class="list-image pull-left">
              <img src="/images/icons/tag-white.png">
            </a>

            <div class="col-md-11 col-xs-8">

              <div class="row">

                <div class="col-md-4 col-xs-12 list-content">
                  <h4 class="primary-info single-info">{{$data['name']}}</h4>
                </div>

                <div class="col-md-3 col-xs-12 list-content">
                  <h4 class="primary-info">จำนวนสินค้าคงเหลือ</h4>
                  <div class="secondary-info">{{$data['quantity']}}</div>
                </div>

                <div class="col-md-2 col-xs-12 list-content">
                  <h4 class="primary-info">ราคาบวกเพิ่ม</h4>
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

      @else
        <div class="list-empty-message text-center space-top-20">
          <img src="/images/common/not-found.png">
          <div>
            <h3>ยังไม่มีตัวเลือก{{$productOption['name']}}</h3>
            <a href="{{$productOptionValueAdd}}" class="button">เพิ่มตัวเลือก</a>
          </div>
        </div>
      @endif

    </div>

  </div>

@stop