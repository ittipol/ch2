<div id="global_navigation" class="global-nav">
  <nav>
    <div class="navigation-top">

      <div class="logo">
        <a class="logo-link" href="{{URL::to('/')}}">
          <img src="/images/logo3.png">
        </a>
      </div>

    </div>

    <div class="navigation-primary-menu">
      <div class="nano">

        <ul class="nav-stack-item nano-content">

          <li class="item">
            <a href="{{URL::to('/')}}">
              <img class="icon" src="/images/icons/home-header.png">
              <div class="item-title">หน้าแรก</div>
            </a>
          </li>

          @if (!Auth::check())

            <li class="item">
              <a href="{{URL::to('login')}}">
                <img class="icon" src="/images/icons/secure-header.png">
                <div class="item-title">เข้าสู่ระบบ</div>
              </a>
            </li>

            <li class="item">
              <a href="{{URL::to('register')}}">
                <img class="icon" src="/images/icons/key-header.png">
                <div class="item-title">สมัครสมาชิก</div>
              </a>
            </li>

          @endif

          <li class="line space-top-bottom-10"></li>

          <li class="item">
            <a href="{{URL::to('shop')}}">
              <img class="icon" src="/images/icons/building-header.png">
              <div class="item-title">บริษัทและร้านค้า</div>
            </a>
          </li>

          @if (Auth::check())
          <li class="item">
            <a href="{{URL::to('account/shop')}}">
              <img class="icon" src="/images/icons/shop-header.png">
              <div class="item-title">ร้านค้าของฉัน</div>
            </a>
          </li>
          @endif

          <li class="item">
            <a href="{{URL::to('shop/create')}}">
              <img class="icon" src="/images/icons/plus-header.png">
              <div class="item-title">สร้างบริษัทหรือร้านค้า</div>
            </a>
          </li>

          <li class="line space-top-bottom-10"></li>

          <li class="item">
            <a href="{{URL::to('product')}}">
              <img class="icon" src="/images/icons/tag-header.png">
              <div class="item-title">สินค้าจากบริษัทและร้านค้า</div>
            </a>
          </li>

          <li class="item">
            <a href="{{URL::to('product/category')}}">
              <img class="icon" src="/images/icons/layer-header.png">
              <div class="item-title">หมวดสินค้า</div>
            </a>
          </li>

          <li class="line space-top-bottom-10"></li>

          <li class="item">
            <a href="{{URL::to('job')}}">
              <img class="icon" src="/images/icons/document-header.png">
              <div class="item-title">งานจากบริษัทและร้านค้า</div>
            </a>
          </li>

        </ul>
      </div>
    </div>
  </nav>
</div>
