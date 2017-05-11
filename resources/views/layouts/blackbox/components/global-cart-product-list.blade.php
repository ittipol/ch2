@if(!empty($_products))

  @foreach($_products as $product)
  <div class="product-list-table-row">

    @if(!empty($product['hasError']))
      <p class="product-list-message error-message">
        {{$product['errorMessage']}}
        @if($product['errorType'] == 5)
        <a href="{{URL::to('cart')}}">แก้ไชจำนวนการสั่งซื้อ</a>
        @endif
      </p>
      <div class="product-list-overlay"></div>
    @endif

    <div class="clearfix">

      <div class="image-tile pull-left">
        <a href="{{$product['productDetailUrl']}}">
          <div class="product-image" style="background-image:url({{$product['imageUrl']}});"></div>
        </a>
      </div>

      <div class="product-info pull-left">
        <h4 class="product-title">
          <a href="{{$product['productDetailUrl']}}">{{$product['name']}}</a>
        </h4>

        @if(!empty($product['productOption']))
          <div class="product-option">
            <span class="product-option-name">{{$product['productOption']['productOptionName']}}:</span>
            <span class="product-option-value-name">{{$product['productOption']['valueName']}}</span>
          </div>
        @endif

        <div class="line grey space-bottom-5"></div>

        <div>
          ราคาสินค้า: <span class="product-price">{{$product['price']}}</span> x {{$product['quantity']}}
        </div>
        @if($product['shipping_calculate_from'] == 2)
        <div>
          ค่าจัดส่งสินค้า: <span class="product-price">{{$product['shippingCost']}}</span>
        </div>
        @endif
        <div>
          มูลค่าสินค้า: <span class="product-price">{{$product['total']}}</span>
        </div>
      </div>

    </div>

    <a class="delete-product-button" data-id="{{$product['id']}}" data-option-value-id="{{$product['productOptionValueId']}}" data-global-cart="1" role="button">×</a>

  </div>
  @endforeach

  <a href="{{URL::to('product')}}" class="button half-button space-top-20">เลือกซื้อสินค้า</a>
  <a href="{{URL::to('checkout')}}" class="button half-button space-top-20">ดำเนินการสั่งซื้อ</a>

@else
  
  <div class="cart-empty text-center">
    <img src="/images/icons/bag-blue.png">
    <h4>ยังไม่มีสินค้าในตระกร้าสินค้าของคุณ</h4>
    <a href="{{URL::to('product')}}" class="button half-button space-top-20">เลือกซื้อสินค้า</a>
  </div>

@endif