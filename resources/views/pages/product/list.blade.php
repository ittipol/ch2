@extends('layouts.blackbox.main')
@section('content')

<div class="container list">

  <h3>{{$title}}</h3>

  <a href="">แสดงประเททสินค้า</a>
  <!-- <div id="filter_expand_panel" class="right-size-panel filter">
    <div class="right-size-panel-inner">

      <div class="right-size-panel-close-icon"></div>
    </div>
  </div> -->

  <!-- <div class="text-right space-top-20">
    <a class="button" data-right-side-panel="1" data-right-side-panel-target="#filter_expand_panel">
      ค้นหา
    </a>
  </div> -->

  <div class="display-category-button">แสดงประเททสินค้า</div>

  <div class="category-list-extend-panel">
    <div class="nano">
      <div class="category-list-extend-panel-inner nano-content">

        <div>

          <h4>หมวดสินค้า</h4>

          <div class="button-group">

            <div class="additional-option">
              <div class="dot"></div>
              <div class="dot"></div>
              <div class="dot"></div>
              <div class="additional-option-content">
                <a href="">แสดงสินค้านี้</a>
              </div>
            </div>
          
          </div>
        </div>

        <div class="row">
          @foreach($categories as $category)
          <div class="col-xs-12">
            <a href="{{$category['url']}}">
              <div class="category-item">
                {{$category['name']}}
              </div>
            </a>
          </div>
          @endforeach
        </div>
      </div>
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