<div class="global-panel global-account-panel">

  <div class="account-content-wrapper">

    <div class="account-info clearfix">
      <div class="account-image-frame">
        <a href="{{URL::to('account')}}">
          <div class="profile-image" style="background-image:url({{Session::get('Person.profile_image')}});"></div>
        </a>
      </div>
      <div class="text-center">
        <h4>{{Session::get('Person.name')}}</h4>
      </div>

      <div class="panel-close-button account-panel-close-button"></div>

      <div class="additional-option">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="additional-option-content">
          <a href="{{URL::to('account')}}">ไปยังหน้าโปรไฟล์</a>
          <a href="{{URL::to('logout')}}">ออกจากระบบ</a>
        </div>
      </div>

    </div>

    <div class="account-tile-button-group">

      <div class="row">
        <div class="col-xs-4">
          <a href="{{URL::to('account/profile/edit')}}">
            <div class="account-tile-button text-center">
              <img class="icon" src="/images/common/pencil.png">
              <h5>แก้ไขโปรไฟล์</h5>
            </div>
          </a>
        </div>

        <div class="col-xs-4">
          <a href="{{URL::to('account/order')}}">
            <div class="account-tile-button text-center">
              <img class="icon" src="/images/common/bag.png">
              <h5>รายการสั่งซื้อสินค้า</h5>
            </div>
          </a>
        </div>

        <div class="col-xs-4">
          <a href="{{URL::to('account')}}">
            <div class="account-tile-button text-center">
              <img class="icon" src="/images/common/additional.png">
              <h5>ทั้งหมด</h5>
            </div>
          </a>
        </div>
      </div>

    </div>

    <div class="account-content">

      <h4>ร้านค้าของฉัน</h4>
      <div class="line"></div>

      @if(!empty($_shops))

      <div class="list-h">
        @foreach ($_shops as $shop)
        <div class="list-h-item clearfix">
          <div class="col-md-11 col-xs-8">
            <a href="{{$shop['url']}}">{{$shop['name']}}</a>
            <div class="inline-flag-group">
              <a href="{{$shop['orderUrl']}}" class="inline-flag">
                รายการสั่งซื้อใหม่:
                <span class="flag-count">{{$shop['totalNewOrder']}}</span>
              </a>
            </div>
          </div>

          <div class="additional-option">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="additional-option-content">
              <a href="{{$shop['url']}}overview">ภาพรวม</a>
              <a href="{{$shop['url']}}manage/product">จัดการสินค้า</a>
              <a href="{{$shop['url']}}product/add">เพิ่มสินค้า</a>
              <!-- <a href="{{$shop['url']}}manage/product_catalog">จัดการแคตตาล็อกสินค้า</a> -->
              <a href="{{$shop['url']}}manage/job">จัดการงาน</a>
              <a href="{{$shop['url']}}setting">ข้อมูลร้านค้า</a>
            </div>
          </div>
        </div>
        @endforeach

        <div class="list-h-item clearfix">
          <div class="col-md-11 col-xs-8">
            <a href="{{URL::to('shop/create')}}">+ เพิ่มร้านค้า</a>
          </div>
        </div>

      </div>

      @else

        <div class="list-empty-message text-center space-top-20">
          <img src="/images/common/shop.png">
          <div>
            <h3>ยังไม่มีร้านค้า</h3>
            <p>สร้างร้านค้าของคุณ จัดการสินค้า และอื่นๆ รวมถึงเปิดโอกาสและเพิ่มช่องทางการขายใหม่ให้กับธุรกิจของคุณ</p>
            <a href="{{URL::to('shop/create')}}" class="button">สร้างร้านค้า</a>
          </div>
        </div>

      @endif

    </div>  

  </div>

</div>