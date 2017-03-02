<div class="global-cart-panel">
  <div class="product-list-table">
    <div class="product-list-table-inner">
      <div id="global_cart_panel">
        @include('layouts.blackbox.components.global-cart-product-list')
      </div>

      <a href="{{URL::to('checkout')}}" class="button wide-button space-top-20">ไปยังตระกร้าสินค้า</a>
    </div>
  </div>
  <div class="cart-panel-close-button"></div>
</div>