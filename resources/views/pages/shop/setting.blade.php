@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.top_nav')

<div class="top-header-wrapper">
  <h2 class="top-header">ตั้งค่า</h2>
</div>

<div class="container">

  <div class="list-item-group">

    <div class="list-item">
      <a href="{{$descriptionUrl}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>คำอธิบายร้านค้า</h4>
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