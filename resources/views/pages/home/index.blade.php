@extends('layouts.blackbox.main')
@section('content')

<div class="secondary-message-box error fixed">
  <div class="secondary-message-box-inner">
    <h4 class="text-center">ขออภัย เว็บไซต์อยู่ระหว่างการปรับปรุงใช้งาน</h4>
  </div>
</div>

<div class="slider-banner">
  <div class="slider-banner-item">
    <img src="/images/banners/main.jpg">
  </div>
  <div class="slider-banner-item">
    <img src="/images/banners/main.jpg">
  </div>
  <div class="slider-banner-item">
    <img src="/images/banners/main.jpg">
  </div>
</div>

<div class="container">

  <h3>ร้านค้าแนะนำ</h3>

  <div>

    @foreach($recommendedShops as $data)

      <h3><img src="{{$data['shop']['profileImage']}}">{{$data['shop']['name']}}</h3>

      <div class="row">
        
        <div class="col-lg-3 col-sm-4 col-xs-12">
          <div class="card sm">

            <div class="image-tile">
              <a href="{{$data['shop']['shopUrl']}}">
                <div class="card-image cover" style="background-image:url({{$data['shop']['profileImage']}});"></div>
              </a>
            </div>
            <div class="card-info">
              <a href="{{$data['shop']['shopUrl']}}">
                <div class="card-title">{{$data['shop']['name']}}</div>
              </a>
              <div class="card-desciption">
                {!!$data['shop']['description']!!}
              </div>
            </div>
            
          </div>
        </div>

        <div class="col-lg-9 col-sm-8 col-xs-12">
          
          <h4 class="space-bottom-20">สินค้าในร้านค้า</h4>

          @if(!empty($data['products']))

            <div class="multiple-product">

              @foreach($data['products'] as $product)

                <div class="card xs no-border no-margin">

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

              @endforeach

            </div>

            <div class="row space-bottom-30">
              <div class="col-xs-12 text-right">
                <a href="{{$data['shop']['shopProductUrl']}}" class="flat-button">ดูสินค้าเพิ่มเติม</a>
              </div>
            </div>

          @else

            <div class="list-empty-message text-center">
              <img class="not-found-image" src="/images/common/not-found.png">
              <div>
                <h3>ยังไม่มีสินค้า</h3>
              </div>
            </div>

          @endif

        </div>

      </div>

    @endforeach

  </div>

  <!-- <h3>ร้านค้าอื่นๆ ที่น่าสนใจ</h3>

  <div class="row">

    @foreach($otherShops as $data)

    <div class="col-sm-3 col-xs-6">
      <div class="card sm">

        <div class="image-tile">
          <a href="{{$data['shopUrl']}}">
            <div class="card-image cover" style="background-image:url({{$data['profileImage']}});"></div>
          </a>
        </div>
        <div class="card-info">
          <a href="{{$data['shopUrl']}}">
            <div class="card-title">{{$data['name']}}</div>
          </a>
          <div class="card-desciption">
            {!!$data['description']!!}
          </div>
        </div>
        
      </div>
    </div>

    @endforeach

  </div> -->

  <div class="row space-bottom-30">
    <div class="col-xs-12 text-right">
      <a href="{{URL::to('shop')}}" class="flat-button">แสดงร้านค้าทั้งหมด</a>
    </div>
  </div>

  <h3>แฟชั่นสุภาพสตรี</h3>

  <div class="row">

    <div class="col-md-6 col-sm-12">

      <div class="channel-column">

        <div class="title color-type-1">
          เสื้อผ้าผู้หญิง
        </div>

        <div>

          <div class="row">
            @foreach($shirts as $data)
            <div class="product-banner">
              <div class="product-banner-content col-xs-12">
                <div class="image-tile pull-left">
                  <a href="{{$data['detailUrl']}}">
                    <div class="product-banner-image" style="background-image:url({{$data['_imageUrl']}});"></div>
                  </a>
                </div>
                <div class="product-banner-info pull-left">
                  <div class="product-title">{{$data['name']}}</div>

                  <div>
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
            </div>
            @endforeach
          </div>

        </div>
        
      </div>

    </div>

    <div class="col-md-6 col-sm-12">

      <div class="channel-column">

        <div class="title color-type-2">
          ชุดเดรส
        </div>

        <div class="multiple-product-2 color-type-2">

          @foreach($dresses as $product)

            <div class="card xs no-border no-margin">

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

          @endforeach

        </div>
        
      </div>

    </div>

    <div class="col-md-6 col-sm-12">

      <div class="channel-column">

        <div class="title color-type-3">
          กระเป๋า
        </div>

        <div class="multiple-product-2 color-type-3">

          @foreach($bags as $product)

            <div class="card xs no-border no-margin">

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

          @endforeach

        </div>

      </div>

    </div>

    <div class="col-md-6 col-sm-12">

      <div class="channel-column">

        <div class="title color-type-4">
          รองเท้า
        </div>

        <div class="multiple-product-2 color-type-4">

          @foreach($shoes as $product)

            <div class="card xs no-border no-margin">

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

          @endforeach

        </div>
        
      </div>

    </div>

  </div>

  <div>
    <h3>สินค้าแนะนำ</h3>

    @foreach($recommendedProducts as $product)

      <h4 class="color-blue space-bottom-20">{{$product['label']}}</h4>

      @if(!empty($product['data']))

      <div class="multiple-product-3">

        @foreach($product['data'] as $data)

          <div class="card xs no-border no-margin">

            @if(!empty($data['flag']))
            <div class="flag-wrapper">
              <div class="flag sale-promotion">{{$data['flag']}}</div>
            </div>
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

          </div>

        @endforeach

      </div>

      @else

        <div class="list-empty-message text-center">
          <img class="not-found-image" src="/images/common/not-found.png">
          <div>
            <h3>ยังไม่มีสินค้า</h3>
          </div>
        </div>

      @endif

    @endforeach

  </div>

</div>

<script type="text/javascript">
  $(document).ready(function(){

    $('.slider-banner').slick({
      dots: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 5000,
      infinite: true,
      arrows:false,
      pauseOnFocus: false,
      pauseOnHover: false,
    });

    $('.multiple-product').slick({
      dots: true,
      infinite: false,
      speed: 300,
      slidesToShow: 4,
      slidesToScroll: 4,
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
          }
        },
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3
          }
        },
        {
          breakpoint: 800,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        }
      ]
    });

    $('.multiple-product-2').slick({
      dots: true,
      infinite: false,
      speed: 300,
      slidesToShow: 2,
      slidesToScroll: 2
    });

    $('.multiple-product-3').slick({
      dots: true,
      infinite: false,
      speed: 300,
      slidesToShow: 6,
      slidesToScroll: 6,
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 4,
            slidesToScroll: 4,
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 4,
            slidesToScroll: 4
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        }
      ]
    });

  });

</script>

@stop