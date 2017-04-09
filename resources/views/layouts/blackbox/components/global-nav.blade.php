<div id="global_navigation" class="global-nav">
  <nav>
    <div class="navigation-top">

      <div class="logo">
        <a class="logo-link" href="{{URL::to('/')}}">Chonburi Square</a>
      </div>

    </div>

    <div class="navigation-primary-menu">
      <div class="nano">
        <ul class="nav-stack-item nano-content">

          <li class="item">
            <a href="{{URL::to('/')}}">หน้าแรก</a>
          </li>

          @if (!Auth::check())

            <li class="item">
              <a href="{{URL::to('login')}}">เข้าสู่ระบบ</a>
            </li>

            <li class="item">
              <a href="{{URL::to('register')}}">สมัครสมาชิก</a>
            </li>

            <li class="line space-top-bottom-10"></li>

          @else

            <li class="item">
              <a href="{{URL::to('person/freelance')}}">ฟรีแลนซ์</a>
            </li>

            <li class="item">
              <a href="javascript:void(0)">บริษัทและร้านค้า</a>
            </li>

          @endif

          <li class="item">
            <a href="javascript:void(0)">สินค้าและการประกาศ</a>
            <ul class="submenu">
              <li class="submenu-item">
                <a href="{{URL::to('product/add')}}">สินค้าจากร้านค้า</a>
                <a href="{{URL::to('product/add')}}">ประกาศงาน</a>
                <a href="{{URL::to('product/add')}}">โฆษณาจากบริษัทและร้านค้า</a>
              </li>
            </ul>
          </li>

          <li class="line space-top-bottom-10"></li>

          <li class="item">
            <a href="javascript:void(0)">ประกาศซื้อ-เช่า-ขาย</a>
            <div class="additional-option">
              <div class="dot"></div>
              <div class="dot"></div>
              <div class="dot"></div>
              <div class="additional-option-content">
                <a href="{{URL::to('item/post')}}">เพิ่มประกาศสินค้า</a>
                <a href="{{URL::to('real_estate/post')}}">เพิ่มประกาศอสังหาริมทรัพย์</a>
              </div>
            </div>
            <ul class="submenu">
              <li class="submenu-item">
                <a href="{{URL::to('product/add')}}">ประกาศซื้อ-เช่า-ขายสินค้า</a>
                <a href="{{URL::to('real-estate/add')}}">ประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์</a>
              </li>
            </ul>
          </li>

          <li class="line space-top-bottom-10"></li>

          <li class="item">
            <a href="{{URL::to('entity/create')}}">วิธีการใช้งาน</a>
          </li>
          <li class="item">
            <a href="{{URL::to('entity/create')}}">แจ้งปัญหาการใช้งาน</a>
          </li>
          <li class="item">
            <a href="{{URL::to('entity/create')}}">ข้อเสนอแนะ</a>
          </li>
          <li class="item">
            <a href="{{URL::to('entity/create')}}">ติดต่อเรา</a>
          </li>

        </ul>
      </div>
    </div>
  </nav>
</div>
