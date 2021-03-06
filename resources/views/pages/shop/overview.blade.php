@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}" class="btn btn-secondary">หน้าหลักร้านค้า</a>
          <button class="btn btn-secondary additional-option">
            จัดการ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}manage/product">สินค้า</a>
              <a href="{{request()->get('shopUrl')}}manage/job">งาน</a>
              <a href="{{request()->get('shopUrl')}}manage/advertising">โฆษณา</a>
              <a href="{{request()->get('shopUrl')}}manage/product_catalog">แคตตาล็อกสินค้า</a>
              <a href="{{request()->get('shopUrl')}}setting">ข้อมูลร้านค้า</a>
            </div>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- @include('pages.shop.layouts.header') -->

<div class="shop-content-wrapper">

  <div class="shop-notice">

    <div class="container">
    
      <div class="shop-notice-header">
        <h3>ภาพรวมร้านค้า</h3>
      </div>

      <div class="shop-notice-content">
        <div class="shop-notice-row">
          <a href="{{request()->get('shopUrl')}}setting">
            <h4 class="text-center">เพิ่มข้อมูลของร้านค้า</h4>
          </a>
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

            <a href="{{request()->get('shopUrl')}}manage/product" class="button wide-button space-top-bottom-20">
              จัดการสินค้า
            </a>

            <div class="line"></div>

            <div class="tile-nav-group space-top-20 clearfix">

              <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="{{request()->get('shopUrl')}}product/add">
                    <img src="/images/common/plus.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="{{request()->get('shopUrl')}}product/add">
                    <h4 class="tile-nav-title">เพิ่มสินค้า</h4>
                  </a>
                </div>
              </div>

              <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="{{request()->get('shopUrl')}}manage/payment_method">
                    <img src="/images/common/payment.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="{{request()->get('shopUrl')}}manage/payment_method">
                    <h4 class="tile-nav-title">วิธีการชำระเงิน</h4>
                  </a>
                </div>
              </div>

              <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="{{request()->get('shopUrl')}}manage/shipping_method">
                    <img src="/images/common/truck.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="{{request()->get('shopUrl')}}manage/shipping_method">
                    <h4 class="tile-nav-title">วิธีการจัดส่งสินค้า</h4>
                  </a>
                </div>
              </div>

            </div>

            <div class="line"></div>

            <h3>การสั่งซื้อสินค้า</h3>

            <div>
              <h1>{{$totalOrder}}</h1>
              <div>การสั่งซื้อ</div>
            </div>

            <div>
              <h1>{{$countNewOrder}}</h1>
              <div>การสั่งซื้อใหม่</div>
            </div>

            <a href="{{request()->get('shopUrl')}}order" class="button wide-button space-top-bottom-20">
              จัดการการสั่งซื้อ
            </a>

          </div>
        </div>
      </div>

      <div class="col-lg-6 col-md-12 col-xs-12">
        <div class="box">
          <div class="box-inner">
            <h3>งาน</h3>

            <div>
              <h1>{{$totalJob}}</h1>
              <div>ตำแหน่งที่ประกาศ</div>
            </div>

            <a href="{{request()->get('shopUrl')}}manage/job" class="button wide-button space-top-bottom-20">
              จัดการงาน
            </a>

            <div class="line"></div>

            <div class="tile-nav-group space-top-20 clearfix">

              <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="{{request()->get('shopUrl')}}job/add">
                    <img src="/images/common/plus.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="{{request()->get('shopUrl')}}job/add">
                    <h4 class="tile-nav-title">ลงประกาศงาน</h4>
                  </a>
                </div>
              </div>

              <!-- <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="{{request()->get('shopUrl')}}job_applying">
                    <img src="/images/common/resume.png">
                  </a>
                </div>
                <div class="tile-nav-info">

                  <div class="tile-flag-count">{{$countNewJobApplying}}</div>
                  
                  <a href="{{request()->get('shopUrl')}}job_applying">
                    <h4 class="tile-nav-title">รายชื่อผู้สมัครงาน</h4>
                  </a>
                </div>
              </div> -->

            </div>

            <div class="line"></div>

            <h3>การสมัครงาน</h3>

            <div>
              <h1>{{$totalJobApplying}}</h1>
              <div>การสมัครงาน</div>
            </div>

            <div class="clearfix">

              <div class="pull-left">
                <h1>{{$countNewJobApplying}}</h1>
                <div>การสมัครงานใหม่</div>
              </div>

            </div>

            <a href="{{request()->get('shopUrl')}}job_applying" class="button wide-button space-top-bottom-20">
              จัดการการสมัครงาน
            </a>

          </div>
        </div>
      </div>

      <div class="col-lg-6 col-md-12 col-xs-12">
        <div class="box">
          <div class="box-inner">
            <h3>โฆษณา</h3>

            <div>
              <h1>{{$totalAdvertising}}</h1>
              <div>รายการโฆษณา</div>
            </div>

            <a href="{{request()->get('shopUrl')}}manage/advertising" class="button wide-button space-top-bottom-20">
              จัดการโฆษณา
            </a>

            <div class="line"></div>

            <div class="tile-nav-group space-top-20 clearfix">

              <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="{{request()->get('shopUrl')}}advertising/add">
                    <img src="/images/common/plus.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="{{request()->get('shopUrl')}}advertising/add">
                    <h4 class="tile-nav-title">ลงโฆษณา</h4>
                  </a>
                </div>
              </div>

            </div>

          </div>
        </div>
      </div>

      <div class="col-lg-6 col-md-12 col-xs-12">
        <div class="box">
          <div class="box-inner">
            <h3>แคตตาล็อกสินค้า</h3>

            <div>
              <h1>{{$totalProductCatalog}}</h1>
              <div>รายการแคตตาล็อกสินค้า</div>
            </div>

            <a href="{{request()->get('shopUrl')}}manage/product_catalog" class="button wide-button space-top-bottom-20">
              จัดการแคตตาล็อกสินค้า
            </a>

            <div class="line"></div>

            <div class="tile-nav-group space-top-20 clearfix">

              <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="{{request()->get('shopUrl')}}product_catalog/add">
                    <img src="/images/common/plus.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="{{request()->get('shopUrl')}}product_catalog/add">
                    <h4 class="tile-nav-title">สร้างแคตตาล็อกสินค้า</h4>
                  </a>
                </div>
              </div>

            </div>

          </div>
        </div>
      </div>

    </div>

</div>

@stop