@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

@include('pages.shop.layouts.top_nav') 
@include('pages.shop.layouts.header') 

<div class="shop-content-wrapper">

  <div class="shop-notice">

    <div class="container">
    
      <div class="shop-notice-header">
        <h3>จัดการร้านค้า</h3>
        <p>กรุณาเพิ่มข้อมูลของร้านค้า ก่อนการใช้งาน</p>
      </div>

      <div class="shop-notice-content">
        <div class="shop-notice-row">
          <a href="{{$settingUrl}}"><h4 class="text-center">เพิ่มข้อมูลของร้านค้า</h4></a>
        </div>
      </div>

    </div>

  </div>

  <div class="line"></div>

  <div class="container">

    <div class="row">

      <div class="col-lg-6 col-md-12 col-xs-12">
        <div class="box">
          <div class="box-inner">
            <h3>สินค้า</h3>
            <div>
              <h1>{{$totalProduct}}</h1>
              <div>รายการสินค้า</div>
            </div>

            <a href="#" class="button wide-button space-top-bottom-20">
              จัดการสินค้า
            </a>

            <div class="line"></div>

            <div class="tile-nav-group space-top-20 clearfix">

              <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="{{$productPostUrl}}">
                    <img src="/images/common/plus.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="{{$productPostUrl}}">
                    <h4 class="tile-nav-title">เพิ่มสินค้า</h4>
                  </a>
                </div>
              </div>

              <div class="tile-nav small">
                <div class="tile-flag-count">99</div>

                <div class="tile-nav-image">
                  <a href="">
                    <img src="/images/common/bag.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="">
                    <h4 class="tile-nav-title">รายการสั่งซื้อ</h4>
                  </a>
                </div>
              </div>

              <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="">
                    <img src="/images/common/payment.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="">
                    <h4 class="tile-nav-title">วิธีการชำระเงิน</h4>
                  </a>
                </div>
              </div>

              <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="">
                    <img src="/images/common/truck.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="">
                    <h4 class="tile-nav-title">วิธีการจัดส่งสินค้า</h4>
                  </a>
                </div>
              </div>

            </div>

          </div>
        </div>
      </div>

      <div class="col-md-6 col-xs-12">
        <div class="box">
          <div class="box-inner">
            <h3>ประกาศงาน</h3>

            <div>
              <h1>{{$totalJob}}</h1>
              <div>รายการประกาศ</div>
            </div>

            <a href="{{request()->get('shopUrl')}}manage/job" class="button wide-button space-top-bottom-20">
              จัดการประกาศงาน
            </a>

            <div class="line"></div>

            <div class="tile-nav-group space-top-bottom-20 clearfix">

              <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="">
                    <img src="/images/common/plus.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="">
                    <h4 class="tile-nav-title">ลงประกาศงาน</h4>
                  </a>
                </div>
              </div>

              <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="">
                    <img src="/images/common/resume.png">
                  </a>
                </div>
                <div class="tile-nav-info">

                  <div class="tile-flag-count">99</div>
                  
                  <a href="">
                    <h4 class="tile-nav-title">รายชื่อผู้ที่สมัครงาน</h4>
                  </a>
                </div>
              </div>

            </div>

          </div>
        </div>
      </div>

      <div class="col-md-6 col-xs-12">
        <div class="box">
          <div class="box-inner">
            <h3>โฆษณา</h3>

            <div>
              <h1>{{$totalAdvertising}}</h1>
              <div>รายการโฆษณา</div>
            </div>

            <a href="#" class="button wide-button space-top-bottom-20">
              จัดการโฆษณา
            </a>

            <div class="line"></div>

            <div class="tile-nav-group space-top-bottom-20 clearfix">

              <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="">
                    <img src="/images/common/plus.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="">
                    <h4 class="tile-nav-title">ลงโฆษณา</h4>
                  </a>
                </div>
              </div>

            </div>

          </div>
        </div>
      </div>

      <div class="col-md-6 col-xs-12">
        <div class="box">
          <div class="box-inner">
            <h3>สาขา</h3>

            <div>
              <h1>0</h1>
              <div>สาขา</div>
            </div>

            <a href="#" class="button wide-button space-top-bottom-20">
              จัดการสาขา
            </a>

            <div class="line"></div>

            <div class="tile-nav-group space-top-bottom-20 clearfix">

              <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="">
                    <img src="/images/common/plus.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="">
                    <h4 class="tile-nav-title">เพิ่มสาขา</h4>
                  </a>
                </div>
              </div>

            </div>

          </div>
        </div>
      </div>

      <div class="col-xs-12">
        <div class="box">
          <div class="box-inner">
            <h3>ที่อยู่และการติดต่อ</h3>

            <div id="map" class="map-small"></div>

            <div>
              <h5><strong>ที่อยู่</strong></h5>
              @if(!empty($_modelData['Address']['_long_address']))
                {{$_modelData['Address']['_long_address']}}
              @else
                -
              @endif
            </div>

            <div>
              <h5><strong>หมายเลขโทรศัพท์</strong></h5>
              @if(!empty($_modelData['Contact']['phone_number']))
                {{$_modelData['Contact']['phone_number']}}
              @else
                -
              @endif
            </div>

            <div>
              <h5><strong>อีเมล</strong></h5>
              @if(!empty($_modelData['Contact']['email']))
                {{$_modelData['Contact']['email']}}
              @else
                -
              @endif
            </div>

            <div>
              <h5><strong>เว็บไซต์</strong></h5>
              @if(!empty($_modelData['Contact']['website']))
                {{$_modelData['Contact']['website']}}
              @else
                -
              @endif
            </div>

            <div class="line only-space space-top-bottom-20"></div>

            <a href="" class="button">แก้ไขที่อยู่</a>
            <a href="" class="button">แก้ไขการติดต่อ</a>

          </div>
        </div>
      </div>

    </div>

  </div>

</div>

<script type="text/javascript">
  $(document).ready(function(){

    @if(!empty($_modelData['Address']['_geographic']))
    const map = new Map(false,false,false);
    map.initialize();
    map.setLocation({!!$_modelData['Address']['_geographic']!!});
    @endif

  });
</script>

@stop