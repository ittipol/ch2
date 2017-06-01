@extends('layouts.blackbox.main')
@section('content')

  <div class="container space-top-30">

    <h3 class="space-bottom-50">วิธีการใช้งาน</h3>
  
    <h4>UI & การใช้งานทั่วไป</h4>

    <div class="row">

      <div class="col-lg-4 col-sm-6 col-xs-12">
        <h5>
          <strong>UI</strong>
        </h5>

        <div class="nav-group space-bottom-30">
          <a href="{{URL::to('manual/ui-and-nav')}}">เมนู & ตัวนำทางหลัก</a>
        </div>
      </div>

      <div class="col-lg-4 col-sm-6 col-xs-12">
        <h5>
          <strong>การใช้งานทั่วไป</strong>
        </h5>

        <div class="nav-group space-bottom-30">
          <a href="{{URL::to('manual/search')}}">การค้นหา</a>
          <a href="{{URL::to('manual/notification')}}">การแจ้งเตือน</a>
        </div>
      </div>

      <div class="col-lg-4 col-sm-6 col-xs-12">
        <h5>
          <strong>บัญชีผู้ใช้</strong>
        </h5>

        <div class="nav-group space-bottom-30">
          <a href="{{URL::to('manual/profile-edit')}}">แก้ไขโปรไฟล์</a>
          <a href="{{URL::to('manual/my-shop')}}">ร้านค้าของฉัน</a>
        </div>
      </div>

    </div>

    <h4>คู่มือสำหรับผู้ขาย</h4>

    <div class="row">

      <div class="col-lg-4 col-sm-6 col-xs-12">
        <h5>
          <strong>ร้านค้า</strong>
        </h5>

        <div class="nav-group space-bottom-30">
          <a href="{{URL::to('manual/creating-shop')}}">สร้างร่านค้า</a>
          <a href="{{URL::to('manual/shop-profile-image')}}">รูปภาพโปรไฟล์ร้านค้า & รูปหน้าปก</a>
          <a href="{{URL::to('manual/adding-shipping-method')}}">เพิ่มตัวเลือกวิธีการจัดส่ง</a>
          <a href="{{URL::to('manual/adding-payment-method')}}">เพิ่มตัวเลือกการชำระเงิน</a>
          <a href="{{URL::to('manual/shop-setting')}}">ข้อมูลร้านค้า</a>
        </div>
      </div>

      <div class="col-lg-4 col-sm-6 col-xs-12">
        <h5>
          <strong>สินค้า</strong>
        </h5>

        <div class="nav-group space-bottom-30">
          <a href="{{URL::to('manual/adding-product')}}">เพิ่มสินค้า</a>
          <a href="{{URL::to('manual/product-detail-edit')}}">แก้ไขข้อมูลสินค้า & ข้อมูลจำเพาะ</a>
          <a href="{{URL::to('manual/adding-product-attribute')}}">เพิ่มตัวเลือกคุณลักษณะสินค้า</a>
          <a href="{{URL::to('manual/product-category-edit')}}">แก้ไขหมวดหมู่สินค้า</a>
          <a href="{{URL::to('manual/product-minimum-edit')}}">การสั่งซื้อขั้นต่ำ</a>
          <a href="{{URL::to('manual/product-quantity-edit')}}">จำนวนสินค้า</a>
          <a href="{{URL::to('manual/product-price-edit')}}">ราคาสินค้า</a>
          <a href="{{URL::to('manual/product-promotion-edit')}}">โปรโมชั่นการขาย</a>
          <a href="{{URL::to('manual/product-shipping-edit')}}">การคำนวณขนส่งสินค้า</a>
          <a href="{{URL::to('manual/product-notification-edit')}}">ข้อความและการแจ้งเตือน</a>
        </div>
      </div>

      <div class="col-lg-4 col-sm-6 col-xs-12">
        <h5>
          <strong>แคตตาล็อกสินค้า</strong>
        </h5>

        <div class="nav-group space-bottom-30">
          <a href="{{URL::to('manual/adding-product-catalog')}}">สร้างแคตตาล็อกสินค้า</a>
          <a href="{{URL::to('manual/product-catalog-edit')}}">แก้ไขแคตตาล็อกสินค้า</a>
          <a href="{{URL::to('manual/product-catalog-product-edit')}}">เพิ่ม / ลบสินค้าในแคตตาล็อก</a>
        </div>
      </div>

    </div>

    <h4>คู่มือสำหรับผู้ซื้อ</h4>

    <div class="row">

      <div class="col-lg-4 col-sm-6 col-xs-12">
        <h5>
          <strong>เลือกซื้อสินค้า & การสั่งซื้อ</strong>
        </h5>

        <div class="nav-group space-bottom-30">
          <a href="">เลือกซื้อสินค้า</a>
          <a href="">การสั่งซื้อ</a>
        </div>
      </div>

      <div class="col-lg-4 col-sm-6 col-xs-12">
        <h5>
          <strong>รายการสั่งซื้อ</strong>
        </h5>

        <div class="nav-group space-bottom-30">
          <a href="">ตรวจสอบรายการสั้่งซื้อ</a>
          <a href="">การชำระเงิน & การแจ้งการชำระเงิน</a>
        </div>
      </div>

    </div>

  </div>

@stop