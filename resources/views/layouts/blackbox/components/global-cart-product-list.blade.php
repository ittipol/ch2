@if(!empty($_products))

  @foreach($_products as $product)
  <div class="product-list-table-row clearfix">

    <div class="product-image pull-left">
      <a href="{{$product['productDetailUrl']}}">
        <img src="{{$product['imageUrl']}}">
      </a>
    </div>

    <div class="product-info pull-left">
      <h4 class="product-title">
        <a href="{{$product['productDetailUrl']}}">{{$product['name']}}</a>
      </h4>
      <div class="line grey space-bottom-5"></div>
      <div>
        <span class="price">{{$product['price']}}</span> x {{$product['quantity']}}
      </div>
    </div>

    <a class="delete-product-button" data-id="{{$product['id']}}" role="button">×</a>

  </div>
  @endforeach

  <a href="{{URL::to('cart')}}" class="button wide-button space-top-20">ไปยังตระกร้าสินค้า</a>

@else
  
  <div class="cart-empty">
    <img class="elem-center" src="/images/icons/bag-blue.png">
    <h4 class="text-center">ยังไม่มีสินค้าในตระกร้าสินค้าของคุณ</h4>
    <a href="{{URL::to('product/list')}}" class="button wide-button space-top-20">เลือกซื้อสินค้า</a>
  </div>

@endif