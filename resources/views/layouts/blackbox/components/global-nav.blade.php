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
              <i class="fa fa-home"></i>
              <div class="item-title">หน้าแรก</div>
            </a>
          </li>

          @if (Auth::check())

            <li class="item">
              <a href="{{URL::to('account/shop')}}">
                <i class="fa fa-bookmark"></i>
                <div class="item-title">ร้านค้าของฉัน</div>
              </a>
            </li>

          @else

            <li class="item">
              <a href="{{URL::to('login')}}">
                <i class="fa fa-lock"></i>
                <div class="item-title">เข้าสู่ระบบ</div>
              </a>
            </li>

            <li class="item">
              <a href="{{URL::to('register')}}">
                <i class="fa fa-group"></i>
                <div class="item-title">สมัครสมาชิก</div>
              </a>
            </li>

          @endif

          <li class="line space-top-bottom-10"></li>

          <li class="item">
            <a href="{{URL::to('shop')}}">
              <i class="fa fa-building"></i>
              <div class="item-title">บริษัทและร้านค้า</div>
            </a>
          </li>

          <li class="item">
            <a href="{{URL::to('shop/create')}}">
              <i class="fa fa-plus"></i>
              <div class="item-title">สร้างบริษัทหรือร้านค้า</div>
            </a>
          </li>

          <li class="line space-top-bottom-10"></li>

          <li class="item">
            <a href="{{URL::to('product')}}">
              <i class="fa fa-tag">
                <div class="badge">{{$_product_count}}</div>
              </i>
              <div class="item-title">สินค้าจากบริษัทและร้านค้า</div>
            </a>
          </li>

          <li class="item">
            <a href="{{URL::to('product/category')}}">
              <i class="fa fa-cubes"></i>
              <div class="item-title">หมวดสินค้า</div>
            </a>
          </li>

          <li class="item">
            <a href="{{URL::to('job')}}">
              <i class="fa fa-clipboard">
                <div class="badge">{{$_job_count}}</div>
              </i>
              <div class="item-title">งานจากบริษัทและร้านค้า</div>
            </a>
          </li>

          <li class="item">
            <a href="{{URL::to('advertising')}}">
              <i class="fa fa-bullhorn">
                <div class="badge">{{$_advertising_count}}</div>
              </i>
              <div class="item-title">โฆษณาจากบริษัทและร้านค้า</div>
            </a>
          </li>

        </ul>
      </div>
    </div>
  </nav>
</div>
