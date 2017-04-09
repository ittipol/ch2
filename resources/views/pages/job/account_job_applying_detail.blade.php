@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h4 class="sub-title">งานที่สมัคร</h4>
        <h2 class="title">{{$jobName}}</h2>
      </div>
    </div>
  </div>
</div>

<div class="container">

  <div class="row">

    <div class="col-md-4 col-sm-12">

      <div class="detail-group">
        <h4>รายละเอียดการสั่งซื้อ</h4>
        <div class="line"></div>

        <div class="detail-group-info-section">

          <div class="detail-group-info">
            <h5 class="title">ชื้อบริษัทหรือร้านค้าที่ขายสินค้า</h5>
            <a href="{{$shopUrl}}">
              <p>{{$shopName}}</p>
            </a>
          </div>

          <div class="detail-group-info">
            <h5 class="title">ชื้อผู้ซื้อ</h5>
            <a href="$jobUrl">
              <p>{{$jobName}}</p>
            </a>
          </div>

          <div class="detail-group-info">
            <h5 class="title">วันที่สมัคร</h5>
            <p>{{$createdDate}}</p>
          </div>

        </div>
      </div>

    </div>

  </div>

  <h4>ข้อความ</h4>
  <div class="line"></div>
  <div class="list-empty-message text-center space-top-20">
    <img class="space-bottom-20 not-found-image" src="/images/common/not-found.png">
    <div>
      <h3>ยังไม่มีการตอบกลับจากผู้ประกาศงาน</h3>
    </div>
  </div>

</div>

@stop