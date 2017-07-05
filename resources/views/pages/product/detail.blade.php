@extends('layouts.blackbox.main')
@section('content')

@if(!empty(request()->get('shop')))
@include('pages.shop.layouts.fixed_top_nav')
@endif

<div class="container">

  @if(!empty($categoryPaths))
  <ol class="breadcrumb space-top-20 space-bottom-10">
    @foreach($categoryPaths as $path)
    <li class="breadcrumb-item">
      <a href="{{$path['url']}}">{{$path['name']}}</a>
    </li>
    @endforeach
  </ol>
  @endif
  <h3 class="title">{{$_modelData['name']}}</h3>
  <div class="line"></div>

</div>

<div class="container detail">

  <div class="row">

    <div class="image-gallery space-top-30 col-sm-8 col-xs-12">

      @if(!empty($_modelData['flag']))
        <div class="flag-wrapper">
          <div class="flag sale-promotion">{{$_modelData['flag']}}</div>
        </div>
      @endif

      <div class="image-gallary-display">

        <div class="image-description">
         <div class="image-description-inner">
          <div id="image_description"></div>
         </div>
         <div class="close-image-description-icon"></div>
         <div class="image-description-pagination clearfix">
            <div id="prev_image_description" class="prev-image-description-icon pull-left"></div>
            <div class="pull-left">
              <span id="current_image_description" class="current-page-number"></span>
              <span>/</span>
              <span id="total_image_description" class="total-page-number"></span>
            </div class="pull-left">
            <div id="next_image_description" class="next-image-description-icon pull-left"></div>
          </div>
        </div>

        <div class="image-gallary-display-inner">

          <div class="image-gallary-panel">
            <img id="image_display">
          </div>

          <div class="additional-option">
              <div class="dot"></div>
              <div class="dot"></div>
              <div class="dot"></div>
              <div class="additional-option-content">
                <a class="image-description-display-button">คำอธิบายรูปภาพ</a>
              </div>
            </div>

        </div>
        
      </div>

      @if(!empty($_modelData['Image']))
      <div class="row">
        <div class="col-sm-12">
          <div id="image_gallery_list" class="image-gallery-list clearfix"></div>
        </div>
      </div>
      @endif

      <div class="line only-space space-top-bottom-20"></div>

    </div>

    <div class="col-sm-4 col-xs-12 space-top-30">

      <div class="item-info">

        <div class="item-info-row">
          <p>ราคา</p>
          @if(!empty($_modelData['promotion']))
            <span class="text-emphasize">{{$_modelData['promotion']['_reduced_price']}}<span class="sub-info-text"> / {{$_modelData['product_unit']}}</span></span>
            <span class="product-price-discount-tag">{{$_modelData['promotion']['percentDiscount']}}</span>
            <h5 id="_price" class="origin-price">{{$_modelData['_price']}}</h5>
          @else
            <span class="text-emphasize">
              <span id="_price">{{$_modelData['_price']}}</span>
              <span class="sub-info-text"> / {{$_modelData['product_unit']}}</span>
            </span>
          @endif
        </div>

        <div class="item-info-row">
          <p>จำนวนการสั่งซื้อขั้นต่ำ</p>
          <h4 class="text-emphasize">{{$_modelData['minimum']}}<span class="sub-info-text"> {{$_modelData['product_unit']}} / การสั่งซื้อ</span></h4>
        </div>

        @if($_modelData['shipping_calculate_from'] == 2)
        <div class="item-info-row">
          <p>ค่าจัดส่งสินค้า</p>
          <h4 class="text-emphasize">{{$_modelData['shippingCostText']}}
            @if(!empty($_modelData['shippingCostAppendText']))
              <span class="sub-info-text"> / {{$_modelData['shippingCostAppendText']}}</span>
            @endif
          </h4>
          <p class="text-emphasize-green space-top-10">{{$_modelData['freeShippingMessage']}}</p>
        </div>
        @endif

      </div>

      @if(!empty($productOptionValues))
      <div class="product-option-value">
        @foreach($productOptionValues as $productOptionValue)
          <h5>{{$productOptionValue['name']}}</h5>
          @foreach($productOptionValue['options'] as $value)
            <label>
              <?php
                echo Form::radio('product-option-value-'.$productOptionValue['id'], $value['id'], null, array(
                  'class' => 'product-option-rdobox',
                  'data-price' => $value['realPrice'],
                  'data-quantity-text' => $value['_availability']
                ));
              ?>
              <div class="product-option-value-box product-option-display-{{$value['display_type']}}">
                @if(($value['display_type'] == 2) || ($value['display_type'] == 3))
                <div class="image-tile">
                  <div class="product-option-value-image" style="background-image:url({{$value['imageUrl']}});"></div>
                </div>
                @endif
                @if(($value['display_type'] == 1) || ($value['display_type'] == 3))
                <div class="product-option-value-name">{{$value['name']}}</div>
                @endif
              </div>
            </label>
          @endforeach
        @endforeach
      </div>
      @endif

      <div class="quantity-box">
      @if($_modelData['active'])

        <h5 id="_quantity_text">{{$_modelData['_availability']}}</h5>
        <div class="clearfix">
          <input id="product_quantity" class="quantity-text-input pull-left" type="text" name="quantity" value="{{$_modelData['minimum']}}" autocomplete="off" placeholder="จำนวนสินค้าที่สั่งซื้อ" role="number" />
          <a id="add_to_cart_button" class="button add-to-cart-button pull-right">ใส่ตระกร้า</a>
        </div>

      @else
        <h4 class="error-message">ยังไม่เปิดขายสินค้า</h4>
        <p>สินค้านี้ถูกปิดการสั่งซื้อชั่วคราวจากผู้ขาย</p>
      @endif
      </div>

    </div>

  </div>

  <div class="row">
    
    <div class="col-sm-8 col-xs-12">

      @if(!empty($_modelData['specifications']))
      <div class="detail-info-section">
        <h4>ข้อมูลจำเพาะ</h4>  
        <div class="line"></div> 
        <table class="table table-striped">
          <tbody>
            @foreach($_modelData['specifications'] as $specification)
              <tr>
                <td>{{$specification['title']}}</td>
                <td>{{$specification['value']}}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @endif

      <div class="tabs clearfix">
        <label>
          <input class="tab" type="radio" name="tabs"  data-tab="product_description_tab">
          <span>รายละเอียดสินค้า</span>
        </label>
        <label>
          <input class="tab" type="radio" name="tabs" data-tab="shipping_method_tab">
          <span>ช่องทางการจัดส่ง</span>
        </label>
      </div>

      <div id="product_description_tab" class="tab-content">
        <div class="detail-info-section">
          <div class="detail-info description">
            {!!$_modelData['description']!!}
          </div>
        </div>
      </div>

      <div id="shipping_method_tab" class="tab-content">
        
        @if(!empty($shippingMethods))        
        <div class="detail-info description">
          @foreach($shippingMethods as $shippingMethod)

            @if(empty($shippingMethod['image']))
              <h4 class="space-bottom-20">{{$shippingMethod['name']}}</h4>
            @else
              <h4 class="space-bottom-20"><img class="lg" src="{{$shippingMethod['image']}}">{{$shippingMethod['name']}}</h4>
            @endif

            <table class="table table-striped">
              <thead>
                <tr>
                  <th>วิธีการจัดส่ง</th>
                  <th>การคิดค่าจัดส่ง</th>
                  <th>ค่าบริการ</th>
                </tr>
              </thead>
              <tbody>
                @foreach($shippingMethod['data'] as $data)
                  <tr>
                    <td>{{$data['name']}}</td>
                    <td>{{$data['shippingServiceCostType']}}</td>
                    <td>{{$data['serviceCostText']}}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>

          @endforeach
        </div>
        @else
          <div class="list-empty-message text-center space-top-20">
            <img class="not-found-image" src="/images/common/not-found.png">
            <div>
              <h3>ยังไม่มีการระบุจากทางร้าน</h3>
            </div>
          </div>
        @endif

      </div>

    </div>

    <div class="col-sm-4 col-xs-12">
      
      <div class="content-box content-box-bg" style="background-image:url({{$shopCoverUrl}})">
        <div class="content-box-inner">
          <div class="row">

            <div class="col-md-10 col-xs-12">
              <div class="content-box-panel overlay-bg">
                <div>
                  <h3>
                    <a href="{{$shopUrl}}">{{$shop['name']}}</a>
                  </h3>
                  <div class="line space-top-bottom-20"></div>
                  <p>{{$shop['_short_description']}}</p>
                </div>

                <a href="{{$shopUrl}}" class="button wide-button">ไปยังบริษัทหรือร้านค้า</a>

                <div class="line space-top-bottom-20"></div>

                <div class="contact-list">

                  @if(!empty($shop['Contact']['phone_number']))
                  <div class="contact-info">
                    <img src="/images/common/phone.png">
                    {{$shop['Contact']['phone_number']}}
                  </div>
                  @endif

                  @if(!empty($shop['Contact']['fax']))
                  <div class="contact-info">
                    <!-- <h5><strong>แฟกซ์</strong></h5> -->
                    <img src="/images/common/fax.png">
                    {{$shop['Contact']['fax']}}
                  </div>
                  @endif

                  @if(!empty($shop['Contact']['email']))
                  <div class="contact-info">
                    <img src="/images/common/email.png">
                    {{$shop['Contact']['email']}}
                  </div>
                  @endif

                  @if(!empty($shop['Contact']['websiteUrl']))
                  <div class="contact-info">
                    <img src="/images/common/website.png">
                    @foreach($shop['Contact']['websiteUrl'] as $website)
                      <a href="{{$website['link']}}">{{$website['name']}}</a>
                    @endforeach
                  </div>
                  @endif

                  @if(!empty($shop['Contact']['facebook']))
                  <div class="contact-info">
                     <img src="/images/common/fb-logo.png">
                    {{$shop['Contact']['facebook']}}
                  </div>
                  @endif

                  @if(!empty($shop['Contact']['line']))
                  <div class="contact-info">
                     <img src="/images/common/line.png">
                    {{$shop['Contact']['line']}}
                  </div>
                  @endif

                </div>

              </div>
            </div>

          </div>
        </div>
      </div>

      @if(!empty($productCatalogs))
      <h4 class="space-top-50">แคตตาล็อกสินค้าที่เกี่ยวข้อง</h4>
      <div class="line"></div> 
      <div class="list-h">
      @foreach($productCatalogs as $data)
        <div class="list-h-item list-h-sm no-border clearfix">

          <a href="{{$data['detailUrl']}}" class="list-image pull-left">
            <img src="/images/icons/book-white.png">
          </a>

          <div class="col-md-11 col-xs-8">
            <a href="{{$data['detailUrl']}}">
              <h4 class="primary-info single-info">{{$data['name']}}</h4>
            </a>
          </div>

        </div>
      @endforeach
      </div>
      @endif

    </div>

  </div>

  @if($hasBranchLocation)
  <!-- <h4>สาขาที่ขายสินค้า</h4>   
  <div class="line"></div>
  <div class="row">
    <div class="col-xs-12">

      <div id="map_panel" class="map-panel">

        <div id="map"></div>
        <div class="side-panel">
          <div class="nano">
            <div id="location_items" class="nano-content"></div>
          </div>
        </div>

      </div>

    </div> 
  </div> -->
  @endif

  <div class="line space-top-bottom-40"></div>

  <h4 class="space-top-50 space-bottom-30">
    <img src="/images/icons/tag-blue.png">
    สินค้าอื่นๆที่เกี่ยวข้อง
  </h4>
  <!-- <div class="line space-bottom-20"></div> -->

  <h5><strong>จากผู้ขายรายนี้</strong></h5>
  @if(!empty($shopRealatedProducts))

    <div class="realated-products space-top-20">
      @foreach($shopRealatedProducts as $product)

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

  @else

    <div class="list-empty-message">
      <h5>ยังไม่มีสินค้าอื่นๆที่เกี่ยวข้องให้แสดง</h5>
    </div>

  @endif

  <div class="line only-space space-top-bottom-40"></div>

  <h5><strong>จากผู้ขายรายอื่น</strong></h5>
  @if(!empty($realatedProducts))

    <div class="realated-products space-top-20">
      @foreach($realatedProducts as $product)

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

  @else

    <div class="list-empty-message">
      <h5>ยังไม่มีสินค้าอื่นๆที่เกี่ยวข้องให้แสดง</h5>
    </div>

  @endif

  <div class="line space-top-bottom-40"></div>

  @include('pages.product.layouts.review_content')

</div>

<script type="text/javascript">
  $(document).ready(function(){

    $('.realated-products').slick({
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

    const imageGallery = new ImageGallery(true);
    imageGallery.load({!!$_modelData['Image']!!});

    const product = new Product({{$_modelData['id']}});
    product.load();

    @if($hasBranchLocation)
    // const map = new Map(false,false,false);
    // map.setLocations({!!$branchLocations!!});
    @endif

    const tabs = new Tabs('product_description_tab');
    tabs.load();

    const review = new Review('Product',{{$_modelData['id']}});
    review.load();

  });
</script>

@stop