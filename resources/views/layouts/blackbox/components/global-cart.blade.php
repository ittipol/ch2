<div class="global-panel global-cart-panel">

  <div class="panel-header cart-panel-header">
    <div class="panel-header-title">ตระกร้าสินค้า</div>
    <div class="panel-close-button cart-panel-close-button"></div>
  </div>

  <div class="cart-header-nav">
    <div class="cart-header-nav-inner">
      <div class="text-right">
        <a href="{{URL::to('cart')}}" class="flat-button">แสดงสินค้าในตระกร้า</a>
      </div>
    </div>
  </div>
  
  <div class="product-list-table">
    <div class="product-list-table-inner">
      <div class="product-list-table-content" id="global_cart_panel">
        @include('layouts.blackbox.components.global-cart-product-list')
      </div>
    </div>
  </div>

</div>