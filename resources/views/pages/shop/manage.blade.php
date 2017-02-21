@extends('layouts.blackbox.main')
@section('content')

<div class="shop-wrapper">

  @include('pages.shop.layouts.top_nav') 
  @include('pages.shop.layouts.header') 

  <div class="shop-notice">

    <div class="container">
    
      <div class="shop-notice-header">
        <h3>ยินดีต้อนรับเข้าสู่ชุมชน</h3>
        <p>กรุณาเพิ่มข้อมูลต่างๆของร้านค้าของคุณ ก่อนการใช้งาน</p>
      </div>

      <div class="shop-notice-content">
        <div class="shop-notice-row">
          <a href="{{$settingUrl}}"><h4 class="text-center">เพิ่มข้อมูลของร้านค้า</h4></a>
        </div>
      </div>

    </div>

  </div>

  <!-- <div class="shop-menu">

    <div class="container">

      <h3>จัดการ</h3>

      <div class="tile-nav-group space-top-bottom-20 clearfix">

        <div class="tile-nav xxs">
          <div class="tile-nav-image">
            <a href="{{$productUrl}}">
              <img src="/images/common/tag.png">
            </a>
          </div>
          <div class="tile-nav-info">
            <a href="{{$productUrl}}">
              <h4 class="tile-nav-title">สินค้า</h4>
            </a>
          </div>
        </div>

        <div class="tile-nav xxs">
          <div class="tile-nav-image">
              <a href="{{$jobUrl}}">
                <img src="/images/common/career.png">
              </a>
          </div>
          <div class="tile-nav-info">
            <a href="{{$jobUrl}}">
              <h4 class="tile-nav-title">ลงประกาศงานงาน</h4>
            </a>
          </div>
        </div>

        <div class="tile-nav xxs">
          <div class="tile-nav-image">
              <a href="{{$advertisingUrl}}">
                <img src="/images/common/megaphone.png">
              </a>
          </div>
          <div class="tile-nav-info">
            <a href="{{$advertisingUrl}}">
              <h4 class="tile-nav-title">โฆษณา</h4>
            </a>
          </div>
        </div>

        <div class="tile-nav xxs">
          <div class="tile-nav-image">
              <a href="#">
                <img src="/images/icons/additional.png">
              </a>
          </div>
          <div class="tile-nav-info">
            <a href="#">
              <h4 class="tile-nav-title">แสดงทั้งหมด</h4>
            </a>
          </div>
        </div>

      </div>

    </div>

  </div> -->

  <div class="line"></div>

  <div class="container space-top-30">

    <div class="row">

    <div class="col-lg-4 col-md-6">
      <div class="box">
        <div class="box-inner">
          <h3>สินค้า</h3>

          <div class="notice text-center space-top-40">
            <img class="space-bottom-20" src="/images/common/tag.png">
            <div>
              <h3>สินค้า</h3>
              <p>ยังไม่มีสินค้า เพิ่มสินค้า เพื่อขายสินค้าของคุณ</p>
              <a href="" class="button">เพิ่มสินค้า</a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="box">
        <div class="box-inner">
          <h3>ประกาศงาน</h3>

          <div class="notice text-center space-top-40">
            <img class="space-bottom-20" src="/images/common/career.png">
            <div>
              <h3>ลงประกาศงาน</h3>
              <p>ยังไม่มีประกาศงานของคุณ เพิ่มประกาศงานของคุณเพื่อค้นหาพนักงานใหม่</p>
              <a href="" class="button">ลงประกาศงาน</a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="box">
        <div class="box-inner">
          <h3>โฆษณา</h3>

          <div class="notice text-center space-top-40">
            <img class="space-bottom-20" src="/images/common/megaphone.png">
            <div>
              <h3>โฆษณา</h3>
              <p>ยังไม่มีประกาศงานของคุณ เพิ่มประกาศงานของคุณเพื่อค้นหาพนักงานใหม่</p>
              <a href="" class="button">ลงโฆษณา</a>
            </div>
          </div>

        </div>
      </div>
    </div>

    </div>

  </div>

</div>

@stop