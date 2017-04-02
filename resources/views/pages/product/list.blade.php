@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">
        <div class="additional-option pull-right">
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="additional-option-content">
            <a href="">ไปยังหน้าสินค้าหลัก</a>
            <a href="">ไปยังหน้าแสดงหมวดสินค้า</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container list space-top-30">

  <h3>{{$title}}</h3>
  <!-- <a data-right-side-panel="1" data-right-side-panel-target="#category_expand_panel">แสดงหมวดสินค้าที่เกี่ยวข้อง</a> -->
  <div class="line"></div>
  <div class="text-right space-top-bottom-20">
    <a class="button" data-right-side-panel="1" data-right-side-panel-target="#category_expand_panel">หมวดสินค้า</a>
    <a class="button" data-right-side-panel="1" data-right-side-panel-target="#filter_expand_panel">ตัวกรอง</a>
  </div>

  <div id="category_expand_panel" class="right-size-panel category">

    <a href="">หมวดสินค้าหลัก</a>
    <div class="line"></div>
    <a href="">หมวดสินค้า</a>
    <div class="line"></div>
    <a href="">หมวดก่อนหน้าสินค้า</a>

    <div class="right-size-panel-inner">
      @foreach($categories as $category)
        <a href="{{$category['url']}}">
          <div class="category-item">
            {{$category['name']}}
          </div>
        </a>
      @endforeach
      <div class="right-size-panel-close-icon"></div>
    </div>
  </div>

  <div id="filter_expand_panel" class="right-size-panel filter">
    <div class="right-size-panel-inner">
      @include('components.filter_expand_panel')
      <div class="right-size-panel-close-icon"></div>
    </div>
  </div>

  @if(!empty($_pagination['data']))

    <div class="row">

      @foreach($_pagination['data'] as $data)

      <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
        <div class="card">

          @if(!empty($data['flag']))
            <div class="flag sale-promotion">{{$data['flag']}}</div>
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

    @include('components.pagination') 

  @else

  <div class="list-empty-message text-center space-top-20">
    <img class="space-bottom-20" src="/images/common/not-found.png">
    <div>
      <h3>ไม่พบสินค้า</h3>
      <p>ขออภัย ไม่พบสินค้าที่คุณกำลังหา</p>
    </div>
  </div>

  @endif

</div>

@stop