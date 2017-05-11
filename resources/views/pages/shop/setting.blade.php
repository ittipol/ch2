@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.fixed_top_nav_admin')

<div class="container">
  <div class="container-header">
    <div class="row">
      <div class="col-lg-6 col-sm-12">
        <div class="title">ข้อมูลร้านค้า</div>
      </div>
    </div>
  </div>
</div>

<div class="container">

  <div class="list-item-group">

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}description">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>คำอธิบายเกี่ยวกับร้านค้า</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}address">
        <img class="icon" src="/images/common/location.png" >
        <h4>ที่อยู่</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}contact">
        <img class="icon" src="/images/common/mobile.png" >
        <h4>การติดต่อ</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}opening_hours">
        <img class="icon" src="/images/common/clock.png" >
        <h4>เวลาทำการ</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}delete"  data-modal="1" data-modal-title="ต้องการลบร้านค้าใช่หรือไม่">
        <img class="icon" src="/images/common/close.png" >
        <h4>ลบร้านค้า</h4>
      </a>
    </div>

  </div>

</div>

@stop