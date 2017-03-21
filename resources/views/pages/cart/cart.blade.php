@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">ตะกร้าสินค้า</h2>
      </div>
    </div>
  </div>
</div>

<div class="container">

  <div class="cart">

    <div id="cart_panel" class="product-list-table">

      @if(!empty($data))

        @foreach($data as $value)

          <div id="_shop_{{$value['shop']['id']}}">

            <h5 class="shop-name">{{$value['shop']['name']}}</h5>

            <div class="product-list-wrapper">
              @foreach($value['products'] as $product)
              <div id="_product_{{$product['id']}}" class="product-list-table-row" data-id="_shop_{{$value['shop']['id']}}">

                @if(!empty($product['hasError']))
                  <p class="product-error-message">
                    {{$product['errorMessage']}}
                  </p>
                @endif

                <div class="product-list-box clearfix">

                  <div class="product-image pull-left">
                    <a href="{{$product['productDetailUrl']}}">
                      <img src="{{$product['imageUrl']}}">
                    </a>
                  </div>

                  <div class="col-md-10 col-xs-8 product-info">

                    <div class="col-md-4 col-xs-12 product-info-container">
                      <a href="{{$product['productDetailUrl']}}">
                        <h4 class="product-title product-text">{{$product['name']}}</h4>
                      </a>
                    </div>

                    <div class="col-md-2 col-xs-12 product-info-container">
                      <div>
                        ราคาสินค้า:
                        <br>
                        <strong><span class="product-price product-text">{{$product['price']}}</span></strong>
                      </div>
                      <div>
                        ค่าจัดส่งสินค้า:
                        <br>
                        <strong><span class="product-shipping-cost-amount">{{$product['shippingCost']}}</span></strong>
                      </div>
                    </div>

                    <div class="col-md-4 col-xs-12 product-info-container">
                      <div class="quantity-input-panel">
                        <input 
                        class="quantity-text-input cart-summary-quantity-input" 
                        type="text" name="quantity" 
                        value="{{$product['quantity']}}"
                        autocomplete="off"
                        placeholder="จำนวนสินค้าที่สั่งซื้อ" 
                        role="number"
                        data-id="{{$product['id']}}"
                        data-minimum="{{$product['minimum']}}" />
                        <button class="cart-quantity-update-button">
                          <img src="/images/icons/edit-blue.png">
                        </button>
                      </div>
                    </div>

                    <div class="col-md-2 col-xs-12 product-info-container">
                      <div>
                        มูลค่าสินค้า:
                        <br>
                        <strong><span class="product-total-amount product-text">{{$product['total']}}</span></strong>
                      </div>
                    </div>

                  </div>

                  <a class="delete-product-button" data-id="{{$product['id']}}" role="button">×</a>

                </div>

              </div>
              @endforeach
            </div>

            <div class="cart-summary clearfix">

              <div class="pull-right">

                @foreach($value['summaries'] as $summary)
                  <div class="text-right">
                    <h5 class="{{$summary['class']}}">{{$summary['title']}}: <span class="amount">{{$summary['value']}}</span></h5>
                  </div>
                @endforeach

              </div>

            </div>

          </div>

        @endforeach

        <div class="cart-check-out text-right">
          <a href="{{URL::to('checkout')}}" class="button">ดำเนินการสั่งซื้อสินค้า</a>
        </div>

      @else

        @include('pages.cart.cart_empty')

      @endif

    </div>

  </div>

</div>

@stop