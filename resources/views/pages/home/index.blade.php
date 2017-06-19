@extends('layouts.blackbox.main')
@section('content')

<div class="slider-banner">
  <div class="slider-banner-item color-type-4">
    <div class="slider-banner-image" style="background-image: url(/images/banners/bn6.png);"></div>
  </div>
  <div class="slider-banner-item color-type-3">
    <div class="slider-banner-image" style="background-image: url(/images/banners/bn4.png);"></div>
  </div>
  <div class="slider-banner-item color-type-2">
    <div class="slider-banner-image" style="background-image: url(/images/banners/bn5.png);"></div>
  </div>
  <div class="slider-banner-item color-type-1">
    <div class="slider-banner-image" style="background-image: url(/images/banners/banner_1.png);"></div>
  </div>
</div>

<div class="sub-banner clearfix">
  <div class="sub-banner-content">
    <div class="sub-banner-inner">
      <h3>เลือกซื้อสินค้า</h3>
      <p>สินค้ามากมายโดยตรงจากร้านค้าต่างๆ ที่รอให้คุณเป็นเจ้าของ เพียงหยิบสินค้าลงในตะกร้าและชำระเงิน จากนั้นก็รอรับสินค้าของคุณได้เลย</p>
      <a href="{{URL::to('product')}}">เลือกดูสินค้า<i class="fa fa-angle-double-right"></i></a>
    </div>
  </div>
  <div class="sub-banner-content">
    <div class="sub-banner-inner">
      <h3>สร้างร้านค้าและเริ่นต้นธุรกิจ</h3>
      <p>สร้างร้านค้า จัดการสินค้าและอื่นๆในแบบที่คุณต้องการ พร้อมระบบต่างๆ
ที่คอยซัพพอร์ตให้ธุรกิจของคุณ ได้เติบโตอย่างต่อเนื่อง</p>
      <a href="{{URL::to('shop/create')}}">สร้างร้านค้า<i class="fa fa-angle-double-right"></i></a>
    </div>
  </div>
</div>

<div class="main-content">

  <div class="main-row">

    <div class="container">
      <h3 class="space-bottom-50">ร้านค้า</h3>
    
      @if(!empty($recommendedShops))

      <div class="row">

        @foreach($recommendedShops as $data)

          <div class="col-md-6 col-xs-12">

            <div class="shop-content-section">

              <a href="{{$data['shop']['shopUrl']}}">
                <div class="shop-cover-frame shop-default-cover-bg">
                  @if(!empty($data['shop']['coverImage']))
                  <div class="shop-cover" style="background-image: url({{$data['shop']['coverImage']}});"></div>
                  @else
                  <div class="shop-cover"></div>
                  @endif
                </div>
              </a>

              <div class="shop-main-info">

                <div class="shop-main-info-inner">

                  <a href="{{$data['shop']['shopUrl']}}">
                    <div class="shop-logo-frame">
                      <div class="shop-logo-frame-inner">
                        @if(!empty($data['shop']['profileImage']))
                        <div class="shop-logo" style="background-image: url({{$data['shop']['profileImage']}});"></div>
                        @else
                        <div class="shop-logo"></div>
                        @endif
                      </div>
                    </div>
                  </a>

                  <a href="{{$data['shop']['shopUrl']}}">
                    <h3 class="shop-name">{{$data['shop']['name']}}</h3>
                  </a>
                  <div class="shop-description">
                  @if(!empty($data['shop']['description']) && ($data['shop']['description'] != '-'))
                  {{$data['shop']['description']}}
                  @else
                  ไม่มีคำอธิบาย
                  @endif
                  </div>

                </div>

              </div>

              <div class="line"></div>

              <div class="shop-product">

                <h4>สินค้าในร้านค้า</h4>

                @if(!empty($data['products']))

                  <div class="shop-product-list">

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

                  <div class="text-right">
                    <a href="{{$data['shop']['shopProductUrl']}}" class="flat-button">ดูสินค้าในร้านเพิ่มเติม</a>
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

          </div>

        @endforeach

      </div>

      @else

        <div class="list-empty-message text-center">
          <img class="not-found-image" src="/images/common/not-found.png">
          <div>
            <h3>ยังไม่มีร้านค้า</h3>
          </div>
        </div>

      @endif

      <div class="line grey space-top-bottom-20"></div>

      <div class="main-row color-bar bg-transparent">
        <div class="inner">
          <div class="color-bar-article">
            <div class="color-bar-title">มีร้านค้ามากมายที่รอให้คุณเยี่ยมชม</div>
            <a href="{{URL::to('shop')}}">แสดงร้านค้าทั้งหมด</a>
          </div>
        </div>
      </div>

    </div>

  </div>

  <div class="main-row color-bar">
    <div class="container">
      <div class="color-bar-bg" style="background-image:url(/images/test/a1.png);"></div>
      <div class="color-bar-article">
        <div class="color-bar-title">สินค้ามากมายจากร้านค้าที่พร้อมให้คุณเลือกซื้อ</div>
        <p>สินค้าจากบริษัทและร้านค้าที่มีมายมากและหลากหลายให้เลือกซื้อ ในราคาที่คุณสามารถเป็นเจ้าของได้</p>
      </div>
    </div>
  </div>

  <div class="main-row">

    <div class="container">

      <h3 class="space-bottom-50">สินค้าล่าสุดจากร้านค้าต่างๆ</h3>

      @if(!empty($latestProducts))

        <div class="row">

          @foreach($latestProducts as $product)

            <div class="col-md-2 col-xs-6">

              <div class="card sm no-border">

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

        </div>

        <div class="text-right">
          <a href="{{URL::to('product')}}" class="flat-button">ดูสินค้าเพิ่มเติม</a>
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

    $('.shop-product-list').slick({
      dots: true,
      infinite: false,
      speed: 300,
      slidesToShow: 2,
      slidesToScroll: 2
    });

    // $('.multiple-product').slick({
    //   dots: true,
    //   infinite: false,
    //   speed: 300,
    //   slidesToShow: 5,
    //   slidesToScroll: 5,
    //   responsive: [
    //     {
    //       breakpoint: 1200,
    //       settings: {
    //         slidesToShow: 4,
    //         slidesToScroll: 4,
    //       }
    //     },
    //     {
    //       breakpoint: 768,
    //       settings: {
    //         slidesToShow: 3,
    //         slidesToScroll: 3
    //       }
    //     },
    //     {
    //       breakpoint: 480,
    //       settings: {
    //         slidesToShow: 2,
    //         slidesToScroll: 2
    //       }
    //     }
    //   ]
    // });

    // $('.multiple-product-3').slick({
    //   dots: true,
    //   infinite: false,
    //   speed: 300,
    //   slidesToShow: 2,
    //   slidesToScroll: 2,
    // });

  });

</script>

@stop