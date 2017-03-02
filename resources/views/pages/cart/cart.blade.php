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

    @foreach($products as $product)
    <div class="product-list-table-row clearfix">

      <div class="product-image pull-left">
        <a href="{{$product['productDetailUrl']}}">
          <img src="{{$product['imageUrl']}}">
        </a>
      </div>

      <div class="product-info pull-left">
        <a href="{{$product['productDetailUrl']}}">
          <h4 class="product-title">{{$product['name']}}</h4>
        </a>
        <div class="line grey space-bottom-5"></div>
        <div>
          <span class="price">{{$product['price']}}</span> x {{$product['quantity']}}
        </div>
      </div>

    </div>
    @endforeach

  </div>

</div>

@stop