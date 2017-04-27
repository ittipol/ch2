@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{URL::to('person/experience')}}" class="btn btn-secondary">กลับไปหน้าภาพรวมประวัติการทำงาน</a>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">เว็บไซต์ส่วนตัว</h2>
        <div class="tag-group">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container">

  <div class="secondary-message-box info space-bottom-20">
    <div class="secondary-message-box-inner">
      <p>*** ข้อมูลนี้จะแสดงไปยังบริษัทหรือร้านค้าเมื่อเวลาที่คุณสมัครงานของบริษัทหรือร้านค้า รวมถึงจะแสดงในหน้างานฟรีแลนซ์ของคุณ และหน้าประวัติการทำงานของคุณ</p>
    </div>
  </div>

  <div class="clearfix">
    <div class="tile-nav xs pull-left">
      <div class="tile-nav-image">
        <a href="{{URL::to('person/private_website/add')}}">
          <img src="/images/common/plus.png">
        </a>
      </div>
    </div>
    <h4 class="tile-nav-title pull-left">เพิ่ม</h4>
  </div>
  @if(!empty($privateWebsites))
  <div class="list-group">
    @foreach($privateWebsites as $privateWebsite)
      <div class="list-row row">
        <div class="col-xs-9">
          <h4>{{$privateWebsite['website']}}</h4>
          <h5>{{$privateWebsite['websiteType']}}</h5>
        </div>
        <div class="col-xs-3">
          <div class="additional-option round pull-right">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="additional-option-content">
              <a href="{{$privateWebsite['editUrl']}}">แก้ไข</a>
              <a href="{{$privateWebsite['deleteUrl']}}" data-modal="1" data-modal-title="ต้องการลบใช่หรือไม่">ลบ</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  @endif

</div>

@stop