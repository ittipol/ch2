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
    <a href="" class="button">แสดงหมวดหมู่สินค้าทั้งหมด</a>
  </div>

  @foreach($shelfs as $shelf)
  <div class="shelf">

    <h3>{{$shelf['categoryName']}}</h3>
    <div class="space-bottom-10">
      <a href="" >แสดงสินค้าทั้งหมด ({{$shelf['total']}})</a>
      <!-- <a href="">แสดงหมวดสินค้าที่เกี่ยวข้องทั้งหมด</a> -->
    </div>

    <div class="row">

      <div class="col-md-12 col-xs-12">

        @if(!empty($shelf['products']))
        <div class="row">

          @foreach($shelf['products']['items'] as $product)
            <div class="col-md-4 col-xs-12">

              <div class="card">

                @if(!empty($product['flag']))
                  <div class="flag sale-promotion">{{$product['flag']}}</div>
                @endif
                
                <div class="image-tile">
                 
                    <div class="card-image" style="background-image:url({{$product['_imageUrl']}});"></div>
              
                </div>
                
                <div class="card-info">
                 
                    <div class="card-title">{{$product['name']}}</div>
               
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
            <div class="col-md-4 col-xs-12">
              <a href="#" class="product-all-tile">
                {{$shelf['products']['all']['title']}}
              </a>
            </div>
          @endif

        </div>

        @else

          <div class="list-empty-message text-center space-top-20">
            <!-- <img class="space-bottom-20 not-found-image" src="/images/common/not-found.png"> -->
            <div>
              <h4>ยังไม่มีสินค้าหมวด{{$shelf['categoryName']}}</h4>
            </div>
          </div>
        
        @endif

      </div>

      <div class="col-md-12 col-xs-12">
        <a href="" class="bottom-wide-button space-top-20">แสดงหมวดสินค้าที่เกี่ยวข้องทั้งหมด</a>
      </div>

    </div>
  </div>

  <div class="line space-top-bottom-20"></div>
  @endforeach

</div>

@stop