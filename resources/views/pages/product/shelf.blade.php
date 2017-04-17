@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <h2>สินค้า</h2>
    </div>
  </div>
</div>

<div class="container">

  <div class="text-right">
    <a href="{{URL::to('product/category')}}" class="button">แสดงหมวดสินค้า</a>
  </div>

  @foreach($shelfs as $shelf)
    <div class="shelf">

      <h3>{{$shelf['categoryName']}}</h3>

      <h2>{{$shelf['total']}}</h2>
      <h5>รายการสินค้า</h5>

      <div class="row">

        <div class="col-xs-12">

          @if(!empty($shelf['products']))
          <div class="row">

            @foreach($shelf['products']['items'] as $product)
              <div class="col-lg-3 col-sm-4 col-xs-12">

                <div class="card">

                  @if(!empty($product['flag']))
                  <div class="flag-wrapper">
                    <div class="flag sale-promotion">{{$product['flag']}}</div>
                  </div>
                  @endif
                  
                  <div class="image-tile">
                    <a href="{{$product['detailUrl']}}">
                      <div class="card-image" style="background-image:url({{$product['_imageUrl']}});"></div>
                    </a>
                  </div>
                  
                  <div class="card-info">
                    <a href="{{$product['detailUrl']}}">
                      <div class="card-title">{{$product['name']}}</div>
                    </a>
                    <div class="card-sub-info">

                      <div class="card-sub-info-row product-price-section">
                        @if(!empty($product['promotion']))
                          <span class="product-price">{{$product['promotion']['_reduced_price']}}</span>
                          <span class="product-price-discount-tag">{{$product['promotion']['percentDiscount']}}</span>
                          <h5 class="origin-price">{{$product['_price']}}</h5>
                        @else
                          <span class="product-price">{{$product['_price']}}</span>
                        @endif
                      </div>

                    </div>
                  </div>

                </div>

              </div>
            @endforeach

            @if(!empty($shelf['products']['all']))
              <div class="col-lg-3 col-xs-12">
                <a href="{{$shelf['productShelfUrl']}}" class="product-all-tile">
                  <span>
                    แสดงสินค้าทั้งหมด<br>
                    {{$shelf['products']['all']['title']}}
                    <img src="/images/common/tag.png">
                  </span>
                </a>
              </div>
            @endif

          </div>

          @else

            <div class="list-empty-message text-center space-top-20">
              <div>
                <h4>ยังไม่มีสินค้าหมวด{{$shelf['categoryName']}}</h4>
              </div>
            </div>
          
          @endif

        </div>

        <div class="col-xs-12">
          <a href="{{$shelf['categoryUrl']}}" class="bottom-wide-button space-top-20">แสดงหมวดสินค้าที่เกี่ยวข้อง</a>
        </div>

      </div>
    </div>

    <div class="line space-top-bottom-20"></div>
  @endforeach

</div>

@stop