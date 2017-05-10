@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.fixed_top_nav_admin')
@include('pages.shop.layouts.header') 

<div class="shop-content-wrapper">

  <div class="shop-notice">

    <div class="container">
    
      <div class="shop-notice-header">
        <h3>ภาพรวมร้านค้า</h3>
      </div>

      <div class="shop-notice-content">
        <div class="shop-notice-row">
          <a href="{{request()->get('shopUrl')}}setting"><h4 class="text-center">เพิ่มข้อมูลของร้านค้า</h4></a>
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
                <div class="tile-flag-count">{{$countNewOrder}}</div>

                <div class="tile-nav-image">
                  <a href="{{request()->get('shopUrl')}}order">
                    <img src="/images/common/bag.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="{{request()->get('shopUrl')}}order">
                    <h4 class="tile-nav-title">รายการสั่งซื้อ</h4>
                  </a>
                </div>
              </div>

              <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="{{request()->get('shopUrl')}}payment_method">
                    <img src="/images/common/payment.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="{{request()->get('shopUrl')}}payment_method">
                    <h4 class="tile-nav-title">วิธีการชำระเงิน</h4>
                  </a>
                </div>
              </div>

              <div class="tile-nav small">
                <div class="tile-nav-image">
                  <a href="{{request()->get('shopUrl')}}shipping_method">
                    <img src="/images/common/truck.png">
                  </a>
                </div>
                <div class="tile-nav-info">
                  <a href="{{request()->get('shopUrl')}}shipping_method">
                    <h4 class="tile-nav-title">วิธีการจัดส่งสินค้า</h4>
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
            <h3>การสั่งซื้อ</h3>

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

  </div>

</div>

@stop