@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.top_nav')

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-lg-6 col-sm-12">
        <div class="title">
          งาน
        </div>
      </div>
    </div>
  </div>

  <div class="tile-nav-group space-top-bottom-20 clearfix">

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{$jobPostUrl}}">
          <img src="/images/common/plus.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{$jobPostUrl}}">
          <h4 class="tile-nav-title">ลงประกาศงาน</h4>
        </a>
      </div>
    </div>

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{$jobApplyListUrl}}">
          <img src="/images/common/resume.png">
        </a>
      </div>
      <div class="tile-nav-info">

        <div class="tile-flag-count">{{$countJobApplying}}</div>
        
        <a href="{{$jobApplyListUrl}}">
          <h4 class="tile-nav-title">รายชื่อผู้ที่สมัครงาน</h4>
        </a>
      </div>
    </div>

    <div class="tile-nav small">
      <div class="tile-nav-image">
          <a href="{{$branchManageUrl}}">
            <img src="/images/common/building.png">
          </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{$branchManageUrl}}">
          <h4 class="tile-nav-title">สาขา</h4>
        </a>
      </div>
    </div>

  </div>

  <div class="line"></div>

  @if(!empty($_pagination['data']))

    <div class="grid-card">

      <div class="row">

        @foreach($_pagination['data'] as $data)

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
          <div class="card">
            <div class="image-tile">
              <a href="{{$data['detailUrl']}}">
                <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
              </a>
            </div>
            <div class="card-info">
              <a href="{{$data['detailUrl']}}">
                <div class="card-title">{{$data['_short_name']}}</div>
              </a>
              <div class="card-sub-info">
                {{$data['_salary']}}
              </div>
            </div>
            
            <div class="button-group">

              <a href="{{$data['editUrl']}}">
                <div class="button wide-button">แก้ไข</div>
              </a>

              <div class="additional-option">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="additional-option-content">
                  <a href="{{$data['detailUrl']}}">แสดงรายละเอียด</a>
                  <a href="{{$data['deleteUrl']}}">ลบ</a>
                </div>
              </div>
          
            </div>

          </div>
        </div>

        @endforeach

      </div>

      @include('components.pagination') 

    </div>

  @else

    <div class="list-empty-message text-center space-top-20">
      <img class="space-bottom-20" src="/images/common/career.png">
      <div>
        <h3>ประกาศงาน</h3>
        <p>ยังไม่มีประกาศงานของคุณ เพิ่มประกาศงานของคุณเพื่อค้นหาพนักงานใหม่</p>
        <a href="{{$jobPostUrl}}" class="button">ลงประกาศงาน</a>
      </div>
    </div>

  @endif

</div>

@stop