<div class="header">
  <div class="header-inner">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-xs-12">
          <div class="logo"><a href="{{URL::to('/')}}">CHONBURI SQUARE</a></div>
        </div>
        <div class="col-lg-6 col-xs-12">
          <nav class="pull-right">
              <ul class="nav navbar-nav">
                @if (!Auth::check())
                <li><a href="{{URL::to('login')}}">เข้าสู่ระบบ</a></li>
                <li><a href="{{URL::to('register')}}">สมัครสมาชิก</a></li>
                @else
                <li>
                  <a href="{{URL::to('user/account')}}"><img class="avatar" src="/avatar" /></a>
                </li>
                <li><a href="{{URL::to('user/account')}}">{{Session::get('Person.name')}}</a><li>
                <li><a href="{{URL::to('logout')}}">ออกจากระบบ</a><li>
                @endif
                <li><a href="#">ข้อเสนอแนะ</a></li> 
                <li><a href="#">ติดต่อเรา</a></li> 
              </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>