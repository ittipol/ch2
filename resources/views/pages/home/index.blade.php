@extends('layouts.blackbox.main')
@section('content')

<div class="secondary-message-box error fixed">
  <div class="secondary-message-box-inner">
    <h4 class="text-center">ขออภัย เว็บไซต์อยู่ระหว่างการปรับปรุงใช้งาน</h4>
  </div>
</div>

<div class="slider-banner">
  <div class="slider-banner-item color-type-1">
    <div class="slider-banner-image" style="background-image: url(/images/xxx3.jpg);"></div>
  </div>
  <div class="slider-banner-item color-type-2">
    <div class="slider-banner-image" style="background-image: url(/images/xxx4.jpg);"></div>
  </div>
  <div class="slider-banner-item color-type-3">
    <div class="slider-banner-image" style="background-image: url(/images/xxx5.jpg);"></div>
  </div>
</div>

<div class="main-content">

  <div class="main-row">

    <div class="container">

      <h3>ร้านค้า</h3>

      @foreach($recommendedShops as $data)

        <h3>
          @if(!empty($data['shop']['profileImage']))
          <img src="{{$data['shop']['profileImage']}}">
          @endif
          {{$data['shop']['name']}}
        </h3>
        <p>{!!$data['shop']['description']!!}</p>
        <div class="line space-bottom-20"></div>

        <div class="row">
          <div class="col-xs-12">

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

  </div>

  <div class="main-row grey">

    <div class="container">

      <h3>ร้านค้าอื่นๆ</h3>

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

      </div>

      <div class="row space-bottom-30">
        <div class="col-xs-12 text-right">
          <a href="{{URL::to('shop')}}" class="flat-button">แสดงร้านค้าทั้งหมด</a>
        </div>
      </div>

    </div>

  </div>

  <div class="main-row">

    <div class="container">

      <h3>สินค้าสำหรับสุภาพสตรี</h3>

      <div class="row">

        <div class="col-xs-12">

          <div class="channel-column">

            <div class="title color-type-1">
              เสื้อผ้า
            </div>

            <div class="multiple-product-2 color-type-1">

              @foreach($shirts as $product)

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

        <div class="col-xs-12">

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

        <div class="col-xs-12">

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

        <div class="col-xs-12">

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

    </div>

  </div>

  <div class="main-row grey">

    <div class="container">

      <h3>สินค้า</h3>

      <div class="row">

        @foreach($recommendedProducts as $product)

        <div class="col-md-6 col-xs-12">

          <div class="multiple-product-box">

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

              <!-- <div class="text-right">
                <a href="{{$product['moreUrl']}}">ดูสินค้าเพิ่มเติม</a>
              </div> -->

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

    </div>

  </div>

  <!-- <div class="main-row">

    <div class="container">

      <h4>ต้องการสร้างร้านค้า?</h4>
      <a href="" class="button wide-button">สร้างร้านค้า</a>

    </div>

  </div> -->

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

    $('.multiple-product-2').slick({
      dots: true,
      infinite: false,
      speed: 300,
      slidesToShow: 4,
      slidesToScroll: 4,
      responsive: [
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        }
      ]
    });

    $('.multiple-product-3').slick({
      dots: true,
      infinite: false,
      speed: 300,
      slidesToShow: 2,
      slidesToScroll: 2,
    });

  });

</script>

@stop