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
          <a href="{{URL::to('account/profile/edit')}}">
            <img class="icon" src="/images/common/pencil.png" >
            <h4>แก้ไขโปรไฟล์</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('account/shop')}}">
            <img class="icon" src="/images/icons/building-header.png" >
            <h4>บริษัทหรือร้านค้าของคุณ</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('account/order')}}">
            <img class="icon" src="/images/common/bag.png" >
            <h4>รายการสั่งซื้อสินค้า</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('account/notification')}}">
            <img class="icon" src="/images/common/information.png" >
            <h4>การแจ้งเตือน</h4>
          </a>
        </div>

      </div>

    </div>

  </div>

@stop