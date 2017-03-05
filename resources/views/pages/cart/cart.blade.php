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

<div class="container cart">

  <div id="cart_panel" class="product-list-table">

    @if(!empty($data))

      @foreach($data as $value)

        <div id="_shop_{{$value['shop']['id']}}">

          <h5 class="shop-info">{{$value['shop']['name']}}</h5>

          <div class="product-list-wrapper">
            @foreach($value['products'] as $product)
            <div id="_product_{{$product['id']}}" class="product-list-table-row clearfix">

              <div class="product-image pull-left">
                <a href="{{$product['productDetailUrl']}}">
                  <img src="{{$product['imageUrl']}}">
                </a>
              </div>

              <div class="col-md-10 col-xs-8 product-info">

                <div class="col-md-4 col-xs-12 product-info-container">
                  <a href="{{$product['productDetailUrl']}}">
                    <h4 class="product-title">{{$product['name']}}</h4>
                  </a>
                </div>

                <div class="col-md-2 col-xs-12 product-info-container">
                  <div class="product-price">{{$product['price']}}</div>
                </div>

                <div class="col-md-4 col-xs-12 product-info-container">
                  <div class="quantity-text-input-panel">
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
                  <div class="product-total-amount">{{$product['total']}}</div>
                </div>

              </div>

              <a class="delete-product-button" data-id="{{$product['id']}}" role="button">×</a>

            </div>
            @endforeach
          </div>

          <div class="cart-summary clearfix">

            <div class="pull-right">
              <!-- สรุปการสั่งซื้อ -->
              <div class="text-right">
                มูลค่าสินค้า: <span class="sub-total amount">{{$value['summaries']['subTotal']['value']}}</span>
              </div>
              <div class="text-right">
                ยอดสุทธิ: <span class="total-amount amount">{{$value['summaries']['total']['value']}}</span>
              </div>

            </div>

          </div>

        </div>

      @endforeach

      <div class="cart-check-out text-right">
        <a href="{{URL::to('checkout')}}" class="button">ชำระเงิน</a>
      </div>

    @else

      @include('pages.cart.cart_empty')

    @endif

  </div>

</div>

@stop