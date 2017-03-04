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

  <div class="product-list-table">

    @foreach($summaries as $summary)

      <div id="_shop_{{$summary['shop']['id']}}">

        <h4 class="shop-info">{{$summary['shop']['name']}}</h4>

        <div class="product-list-wrapper">
          @foreach($summary['products'] as $product)
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
                <input 
                class="quantity-text-input cart-summary-quantity-input" 
                type="text" name="quantity" 
                value="{{$product['quantity']}}"
                autocomplete="off"
                placeholder="จำนวนสินค้าที่สั่งซื้อ" 
                role="number"
                data-id="{{$product['id']}}"
                data-minimum="{{$product['minimum']}}" />
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
          <!-- สรุปการสั่งซื้อ -->
          <div class="sub-total pull-right">
            sub total
          </div>
          <div class="save-total"></div>
          <div class="total-amount"></div>
        </div>

      </div>

    @endforeach

  </div>

</div>

@stop