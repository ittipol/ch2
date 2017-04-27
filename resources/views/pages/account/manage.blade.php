@extends('layouts.blackbox.main')
@section('content')

<div class="container">

  <div class="row">

    <div class="col-xs-12">

      <div class="content-box">
        <div class="content-box-inner">
          <div class="row">

            <div class="col-sm-12">
              <div class="content-box-panel overlay-bg">
                <h5>โปรไฟล์</h5>

                <div class="row">

                  <div class="col-sm-12">
                    <div class="image-frame elem-center">
                      <div class="content-box-main-image" style="background-image:url({{Session::get('Person.profile_image')}});"></div>
                    </div>
                  </div>

                  <div class="col-sm-12">
                    <div class="profile-info text-center space-top-20">
                      <h3>{{$profile['name']}}</h3>
                    </div>
                  </div>

                </div>

                <div class="line space-top-bottom-20"></div>

                <div class="content-box-main-sub-content clearfix">
                  
                  <div class="main-sub-content">
                    <div><strong>เพศ</strong>: {{$profile['gender']}}</div>
                  </div>

                  <div class="main-sub-content">
                    <div><strong>วันเกิด</strong>: {{$profile['birthDate']}}</div>
                  </div>

                </div>

              </div>
            </div>

          </div>
        </div>
      </div>

    </div>

    <div class="col-xs-12">

      <div class="list-item-group">

        <div class="list-item">
          <a href="{{URL::to('account/profile_edit')}}">
            <img class="icon" src="/images/common/pencil.png" >
            <h4>แก้ไขโปรไฟล์</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('account/theme')}}">
            <img class="icon" src="/images/common/paint.png" >
            <h4>แก้ไขธีม</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('person/experience')}}">
            <img class="icon" src="/images/common/resume.png" >
            <h4>เพิ่มประวัติการทำงาน</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('person/freelance')}}">
            <img class="icon" src="/images/common/home-office.png" >
            <h4>งานฟรีแลนซ์ที่ประกาศ</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('account/shop')}}">
            <img class="icon" src="/images/common/shop.png" >
            <h4>บริษัทหรือร้านค้าของคุณ</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('account/item')}}">
            <img class="icon" src="/images/common/tag.png" >
            <h4>ประกาศซื้อ-เช่า-ขายสินค้า</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('account/real-estate')}}">
            <img class="icon" src="/images/common/building.png" >
            <h4>ประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('account/order')}}">
            <img class="icon" src="/images/common/bag.png" >
            <h4>รายการสั่งซื้อสินค้า</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('account/job_applying')}}">
            <img class="icon" src="/images/common/career.png" >
            <h4>งานที่สมัคร</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('account/notification')}}">
            <img class="icon" src="/images/common/information.png" >
            <h4>การแจ้งเตือน</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('account/trophy')}}">
            <img class="icon" src="/images/common/trophy.png" >
            <h4>ถ้วยรางวัล</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('account/analysis')}}">
            <img class="icon" src="/images/common/graph.png" >
            <h4>สถิติ</h4>
          </a>
        </div>

      </div>

    </div>

  </div>

@stop