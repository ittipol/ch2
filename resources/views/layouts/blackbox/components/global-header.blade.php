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
  </div>
</header>