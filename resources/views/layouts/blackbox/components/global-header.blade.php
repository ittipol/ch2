<header class="global-header">

  <div id="global_header" class="header-fixed-top">
    <div class="nav-fixed-top">
    </div>

    <label class="hamburger-button" for="global_nav_trigger">
      ☰
      <input type="checkbox" id="global_nav_trigger" class="nav-trigger">
    </label>

    <label class="header-button notification-button" for="notification_panel_trigger">
      <img class="icon-header" src="/images/icons/bell-white.png" />
      <input type="checkbox" id="notification_panel_trigger" class="notification-trigger">
      <span id="notification_count" class="badge badge-default">{{$_notification_count}}</span>
    </label>

    <label class="header-button cart-button" for="cart_panel_trigger">
      <img class="icon-header" src="/images/icons/bag-white.png" />
      <input type="checkbox" id="cart_panel_trigger" class="cart-trigger">
      <span id="cart_product_count" class="badge badge-default">{{$_product_count}}</span>
    </label>

    <label class="header-button search-button" for="search_panel_trigger">
      <img class="icon-header" src="/images/icons/search-white.png" />
      <input type="checkbox" id="search_panel_trigger" class="search-trigger">
    </label>

    <label class="header-button additional-button" for="additional_panel_trigger">
      <div class="dot-group">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
      </div>
      <input type="checkbox" id="additional_panel_trigger" class="additional-trigger">
    </label>

    <!-- <label class="header-button additional-option-button additional-option">
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="additional-option-content">
        <a href="">เพิ่มงานฟรีแลนซ์ของคุณ</a>
        <a href="">เพิ่มร้านค้า</a>
        <a href="">เพิ่มประกาศซื้อ-เช่า-ขายสินค้า</a>
        <a href="">เพิ่มประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์</a>
      </div>
    </label> -->
  </div>
  
</header>