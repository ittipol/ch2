<header class="global-header">

  <div id="global_header" class="header-fixed-top">
    <div class="nav-fixed-top">
    </div>

    <label class="hamburger-button" for="global_nav_trigger">
      ☰
      <input type="checkbox" id="global_nav_trigger" class="nav-trigger">
    </label>

    <div class="global-header-content-right">

      <label id="header_notification" class="header-button @if($_notification_count > 0) active @endif notification-button" for="notification_panel_trigger">
        <i class="fa fa-bell"></i>
        <input type="checkbox" id="notification_panel_trigger" class="notification-trigger">
        <span id="notification_count" class="badge badge-default">{{$_notification_count}}</span>
      </label>

      <label class="header-button cart-button" for="cart_panel_trigger">
        <i class="fa fa-shopping-basket"></i>
        <input type="checkbox" id="cart_panel_trigger" class="cart-trigger">
        <span id="cart_product_count" class="badge badge-default">{{$_product_count}}</span>
      </label>

      <label class="header-button search-button" for="search_panel_trigger">
        <i class="fa fa-search"></i>
        <input type="checkbox" id="search_panel_trigger" class="search-trigger">
      </label>

      @if (Auth::check())
      <label class="header-button account-button" for="account_panel_trigger">
        <div class="avatar">
          <div class="profile-image" style="background-image:url({{Session::get('Person.profile_image_xs')}});"></div>
        </div>
        <input type="checkbox" id="account_panel_trigger" class="account-trigger">
      </label>
      @endif

    </div>

  </div>
  
</header>