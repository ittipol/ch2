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
              <a href="{{URL::to('login')}}">เข้าสู่ระบบ</a>
            </li>

            <li class="item">
              <a href="{{URL::to('register')}}">สมัครสมาชิก</a>
            </li>

          @endif

          <li class="line space-top-bottom-10"></li>

          <li class="item">
            <a href="{{URL::to('community/shop')}}">
              <img class="icon" src="/images/icons/building-header.png">
              <span>บริษัทและร้านค้า</span>
            </a>
          </li>

          <li class="item">
            <a href="{{URL::to('community/shop/create')}}">
              <img class="icon" src="/images/icons/plus-header.png">
              <span>สร้างร้านค้า</span>
            </a>
          </li>

          <li class="item">
            <a href="{{URL::to('product/shelf')}}">
              <img class="icon" src="/images/icons/tag-header.png">
              <span>สินค้าจากร้านค้า</span>
            </a>
          </li>

          <li class="line space-top-bottom-10"></li>

          <li class="item">
            <a href="{{URL::to('product/category')}}">
              <img class="icon" src="/images/icons/layer-header.png">
              <span>หมวดสินค้า</span>
            </a>
            <!-- <ul class="submenu">
              <li class="submenu-item">
                <a href="http://ch.local/product/shelf/1">เสื้อผ้าและเครื่องแต่งกายสุภาพบุรุษ</a>
                <a href="http://ch.local/product/shelf/79">เสื้อผ้าและเครื่องแต่งกายสุภาพสตรี</a>
                <a href="http://ch.local/product/shelf/194">เสื้อผ้ากีฬาและอุปกรณ์ออกกำลังกาย</a>
                <a href="http://ch.local/product/shelf/377">สุขภาพและความงาม</a>
                <a href="http://ch.local/product/shelf/448">แม่และเด็ก</a>
                <a href="http://ch.local/product/shelf/493">ยานพาหนะ</a>
                <a href="http://ch.local/product/shelf/651">อิเล็กทรอนิกส์</a>
                <a href="http://ch.local/product/shelf/771">เกมและเครื่องเกม</a>
                <a href="http://ch.local/product/shelf/829">เกมกระดานและของเล่น</a>
                <a href="http://ch.local/product/shelf/871">ฟิกเกอร์ โมเดลและของสะสม</a>
                <a href="http://ch.local/product/shelf/898">กล้องและอุปกรณ์เสริม</a>
                <a href="http://ch.local/product/shelf/937">เครื่องดนตรีและอุปกรณ์เสริม</a>
                <a href="http://ch.local/product/shelf/1034">เครื่องเสียงและชุดหูฟัง</a>
                <a href="http://ch.local/product/shelf/1060">เครื่องใช้ไฟฟ้าภายในบ้านและเครื่องใช้ในครัวเรือน</a>
                <a href="http://ch.local/product/shelf/1260">เฟอร์นิเจอร์และของตกแต่งบ้าน</a>
                <a href="http://ch.local/product/shelf/1743">ลานบ้านและสวน</a>
                <a href="http://ch.local/product/shelf/2126">สัตว์เลี้ยงและอุปกรณ์สำหรับสัตว์เลี้ยง</a>
                <a href="http://ch.local/product/shelf/2147">หนังสือ</a>
                <a href="http://ch.local/product/shelf/2344">กระเป๋าและอุปกรณ์สำหรับการเดินทาง</a>
              </li>
            </ul> -->
          </li>

        </ul>
      </div>
    </div>
  </nav>
</div>
