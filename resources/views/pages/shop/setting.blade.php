@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}manage" class="btn btn-secondary">กลับไปหน้าจัดการหลัก</a>
        </div>

      </div>
    </div>
  </div>
</div>

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
      <a href="{{$descriptionUrl}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>คำอธิบายเกี่ยวกับร้านค้า</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{$addressUrl}}">
        <img class="icon" src="/images/common/location.png" >
        <h4>ที่อยู่</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{$contactUrl}}">
        <img class="icon" src="/images/common/mobile.png" >
        <h4>การติดต่อ</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{$openHoursUrl}}">
        <img class="icon" src="/images/common/clock.png" >
        <h4>เวลาทำการ</h4>
      </a>
    </div>

  </div>

</div>

@stop