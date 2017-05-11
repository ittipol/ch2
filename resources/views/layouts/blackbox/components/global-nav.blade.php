<div id="global_navigation" class="global-nav">
  <nav>
    <div class="navigation-top">

      <div class="logo">
        <a class="logo-link" href="{{URL::to('/')}}">SUNDAY SQUARE</a>
      </div>

    </div>

    <div class="navigation-primary-menu">
      <div class="nano">

        <ul class="nav-stack-item nano-content">

          <li class="item">
            <a href="{{URL::to('/')}}">
              <img class="icon" src="/images/icons/home-header.png">
              <span>หน้าแรก</span>
            </a>
          </li>

          @if (!Auth::check())

            <li class="item">
              <a href="{{URL::to('login')}}">
                <img class="icon" src="/images/icons/secure-header.png">
                <span>เข้าสู่ระบบ</span>
              </a>
            </li>

            <li class="item">
              <a href="{{URL::to('register')}}">
                <img class="icon" src="/images/icons/key-header.png">
                <span>สมัครสมาชิก</span>
              </a>
            </li>

          @endif

          <li class="line space-top-bottom-10"></li>

          <li class="item">
            <a href="{{URL::to('shop')}}">
              <img class="icon" src="/images/icons/building-header.png">
              <span>บริษัทและร้านค้า</span>
            </a>
          </li>

          <li class="item">
            <a href="{{URL::to('shop/create')}}">
              <img class="icon" src="/images/icons/plus-header.png">
              <span>สร้างบริษัทหรือร้านค้า</span>
            </a>
          </li>

          <li class="line space-top-bottom-10"></li>

          <li class="item">
            <a href="{{URL::to('product')}}">
              <img class="icon" src="/images/icons/tag-header.png">
              <span>สินค้าจากบริษัทและร้านค้า</span>
            </a>
          </li>

          <li class="item">
            <a href="{{URL::to('product/category')}}">
              <img class="icon" src="/images/icons/layer-header.png">
              <span>หมวดสินค้า</span>
            </a>
          </li>

          <li class="line space-top-bottom-10"></li>

          <li class="item">
            <a href="{{URL::to('job')}}">
              <img class="icon" src="/images/icons/document-header.png">
              <span>งานจากบริษัทและร้านค้า</span>
            </a>
          </li>

        </ul>
      </div>
    </div>
  </nav>
</div>
