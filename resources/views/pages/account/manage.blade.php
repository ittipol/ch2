@extends('layouts.blackbox.main')
@section('content')

<header class="header-wrapper">
  <div class="container">
    <div class="header-top">
      <div class="header-title">โปรไฟล์</div>
    </div>
  </div>
  <div class="header-fix">
    <div class="header-title">โปรไฟล์</div>
  </div>

  <label class="hamburger-button" for="main_nav_trigger">
    ☰
    <input type="checkbox" id="main_nav_trigger" class="nav-trigger">
  </label>

</header>

<div class="container">

  <div class="space-top-bottom-20">

    <div class="row">

    <div class="col-lg-5 col-sm-12">

      <div class="clearfix">
        <div class="account pull-left">
          @if(!empty($profileImageUrl))
          <div class="profile-image" style="background-image:url({{$profileImageUrl}});"></div>
          @endif
        </div>

        <div class="profile-info pull-left">
          <h3>{{$profile['name']}}</h3>

          <dl>
            <dt>เพศ</dt>
            <dd>{{$profile['gender']}}</dd>
          </dl>

          <dl>
            <dt>วันเกิด</dt>
            <dd>{{$profile['birthDate']}}</dd>
          </dl>
        </div>

      </div>

      <div class="space-top-bottom-20">

        @if(!empty($profile['Address']['_long_address']))
        <dl>
          <dt>ที่อยู่ปัจจุบัน</dt>
          <dd>{{$profile['Address']['_long_address']}}</dd>
        </dl>
        @endif

        @if(!empty($profile['Contact']['phone_number']))
        <dl>
          <dt>หมายเลขโทรศัพท์</dt>
          <dd>{{$profile['Contact']['phone_number']}}</dd>
        </dl>
        @endif

        @if(!empty($profile['Contact']['email']))
        <dl>
          <dt>อีเมล</dt>
          <dd>{{$profile['Contact']['email']}}</dd>
        </dl>
        @endif

        @if(!empty($profile['Contact']['line']))
        <dl>
          <dt>Line ID</dt>
          <dd>{{$profile['Contact']['line']}}</dd>
        </dl>
        @endif
        
      </div>

    </div>

    <div class="col-lg-7 col-sm-12">

      <div class="list">

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
            <h4>ประวัติการทำงาน</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('person/freelance')}}">
            <img class="icon" src="/images/common/career.png" >
            <h4>จัดการงานฟรีแลนซ์ที่ประกาศ</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('account/shop')}}">
            <img class="icon" src="/images/common/shop.png" >
            <h4>ร้านค้าของคุณในชุมชน</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('account/item')}}">
            <img class="icon" src="/images/common/tag.png" >
            <h4>สินค้ามือหนึ่งและมือสองที่ประกาศ</h4>
          </a>
        </div>

        <div class="list-item">
          <a href="{{URL::to('account/real_estate')}}">
            <img class="icon" src="/images/common/building.png" >
            <h4>อสังหาทรัพย์ที่ประกาศ</h4>
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

</div>

@stop