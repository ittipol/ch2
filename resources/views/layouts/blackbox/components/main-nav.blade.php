<div id="global_navigation" class="global-nav">
  <nav>
    <div class="navigation-top">

      <div class="logo">
        <a class="logo-link" href="{{URL::to('/')}}">Chonburi Square</a>
      </div>

      @if (!Auth::check())

        <div class="account-info">
          <div>คุญยังไม่ได้เข้าสู่ระบบ</div>
          <div class="account-description">
            <a href="{{URL::to('login')}}">
              <h4>เข้าสู่ระบบ</h4>
            </a>
          </div>
        </div>
        <div class="line space-top-bottom-10"></div>

      @else

        <div class="account-info clearfix">
          <a class="avatar pull-left" href="{{URL::to('account')}}">
            <div class="profile-image" style="background-image:url({{Session::get('Person.profile_image')}});"></div>
          </a>
          <div class="account-description pull-left">
            <div>{{Session::get('Person.name')}}</div>
            <div><a class="pull-left" href="{{URL::to('account')}}">จัดการบัญชี</a></div>
          </div>
          <div class="additional-option">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="additional-option-content">
              <a href="{{URL::to('account/profile_edit')}}">แก้ไขโปรไฟล์</a>
              <a href="{{URL::to('logout')}}">ออกจากระบบ</a>
            </div>
          </div>
        </div>

      @endif

    </div>

    <div class="navigation-primary-menu">
      <div class="nano">
        <ul class="nav-stack-item nano-content">

          <li class="item">
            <a href="{{URL::to('/')}}">หน้าแรก</a>
          </li>
          <li class="item">
            <a href="{{URL::to('/')}}">Wiki ชลบุรี</a>
          </li>
          <li class="item">
            <a href="{{URL::to('experience')}}">เพิ่มประวัติการทำงาน</a>
          </li>

          @if (Auth::check())

            <li class="line space-top-bottom-10"></li>

            <li class="item">
              <a href="javascript:void(0)">ร้านค้าในชุมชนของคุณ</a>
              <ul class="submenu">
                <li class="submenu-item">

                  @if(!empty($_shops)) 

                    @foreach ($_shops as $shop)

                    <div class="submenu-item-row">
                      <a href="{{$shop['url']}}">{{$shop['name']}}</a>
                      <div class="additional-option">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="additional-option-content">
                          <a href="{{$shop['url']}}manage">จัดการสินค้า</a>
                          <a href="{{$shop['url']}}job">ประกาศงาน</a>
                          <a href="{{$shop['url']}}advertising">จัดการโฆษณา</a>
                          <a href="{{$shop['url']}}setting">ตั้งค่า</a>
                        </div>
                      </div>
                    </div>

                    @endforeach

                    <div class="submenu-item-row">
                      <a href="{{URL::to('community/shop_create')}}">เพิ่มร้านค้า</a>
                    </div>

                  @else

                    <div class="submenu-item-row">
                      <a href="{{URL::to('community/shop_create')}}">ยังไม่มีร้านค้าของคุณในชุมชน</a>
                      <div class="additional-option">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="additional-option-content">
                          <a href="{{URL::to('community/shop_create')}}">เพิ่มร้านค้าของคุณ</a>
                        </div>
                      </div>
                    </div>

                  @endif

                </li>
              </ul>
            </li>

            <li class="line space-top-bottom-10"></li>
            <li class="item">
              <a href="javascript:void(0)">ประกาศ</a>
              <div class="additional-option">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="additional-option-content">
                  <a href="{{URL::to('announcement/create')}}">แสดงรายละเอียด</a>
                </div>
              </div>
              <ul class="submenu">
                <li class="submenu-item">
                  <a href="{{URL::to('product/add')}}">ประกาศซื้อ-เช่า-ขายสินค้า</a>
                  <a href="{{URL::to('real-estate/add')}}">โฆษณาซื้อ-เช่า-ขายอสังหาริมทรัพย์</a>
                </li>
              </ul>
            </li>
            <li class="line space-top-bottom-10"></li>
            <li class="item">
              <a href="{{URL::to('entity/create')}}">สินค้าแนะนำ</a>
            </li>
            <li class="item">
              <a href="{{URL::to('entity/create')}}">งานที่คุณกำลังค้นหา</a>
            </li>
            <li class="line space-top-bottom-10"></li>
            <li class="item">
              <a href="{{URL::to('entity/create')}}">ตั้งค่า</a>
            </li>
            <li class="item">
              <a href="{{URL::to('entity/create')}}">ข้อเสนอแนะ</a>
            </li>
            <li class="item">
              <a href="{{URL::to('entity/create')}}">ติดต่อเรา</a>
            </li>

          @endif
        </ul>
      </div>
    </div>
  </nav>
</div>
