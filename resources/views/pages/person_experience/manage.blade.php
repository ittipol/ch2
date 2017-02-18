@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper">
  <h2 class="top-header">ประวัติการทำงาน</h2>
</div>

<div class="container">

  เพิ่งงานฟรีแลนของคุณ
  ใครบ้างที่สามารถเห็นประวัติของคุณได้
  - ทุกคน
  - เฉพาะฉัน
  - ทุกคนที่เป็นสมาชิก Chonburi Square
  <div class="tile-nav-group space-top-20 clearfix">

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{URL::to('experience/profile/edit')}}">
          <img src="/images/common/resume.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{URL::to('experience/profile/edit')}}">
          <h4 class="tile-nav-title">เพิ่มประวัติการทำงานของคุณ</h4>
        </a>
      </div>
    </div>

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{URL::to('experience/profile/website_add')}}">
          <img src="/images/common/globe.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{URL::to('experience/profile/website_add')}}">
          <h4 class="tile-nav-title">เพิ่มเว็บไซต์ส่วนตัว</h4>
        </a>
      </div>
    </div>

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{URL::to('experience/profile/contact_add')}}">
          <img src="/images/common/plus.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{URL::to('experience/profile/contact_add')}}">
          <h4 class="tile-nav-title">เพิ่มข้อมูลการติดต่อ</h4>
        </a>
      </div>
    </div>

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{URL::to('person/freelance')}}">
          <img src="/images/common/career.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{URL::to('person/freelance')}}">
          <h4 class="tile-nav-title">ฟรีแลนซ์</h4>
        </a>
      </div>
    </div>

  </div>

  <div class="line"></div>

  <div class="space-top-bottom-20">

    <h4>ประวัติโดยย่อ</h4>

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
      
    </div>

    <a href="{{$experienceDetailUrl}}" class="button">แสดงประวัติทั้งหมด</a>

  </div>
  
</div>

@stop