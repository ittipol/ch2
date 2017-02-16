@extends('layouts.blackbox.main')
@section('content')

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
          <img src="/images/common/career.png">
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
        <a href="{{$jobApplyListUrl}}">
          <h4 class="tile-nav-title">แสดงรายชื่อผู้ที่สมัครงานของคุณ</h4>
        </a>
      </div>
    </div>

    <div class="tile-nav small">
      <div class="tile-nav-image">
          <a href="{{$branchUrl}}">
            <img src="/images/common/building.png">
          </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{$branchAddUrl}}">
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
            <div class="image">
              <a href="{{$data['detailUrl']}}">
                <div class="product-image" style="background-image:url({{$data['_imageUrl']}});"></div>
              </a>
            </div>
            <div class="product-detail">
              <a href="{{$data['detailUrl']}}">
                <div class="product-title">{{$data['_name_short']}}</div>
              </a>
              <div class="price">
                {{$data['_salary']}}
              </div>
            </div>
            <div>
    
              <a href="{{$data['editUrl']}}">
                <div class="button half-button">แก้ไข</div>
              </a>
 
              <a href="{{$data['detailUrl']}}">
                <div class="button half-button">แสดง</div>
              </a>
      
            </div>
          </div>
        </div>

        @endforeach

      </div>

      @include('components.pagination') 

    </div>

  @else

    <div class="shop-notice text-center space-top-20">
      <img class="space-bottom-20" src="/images/common/career.png">
      <div>
        <h3>ลงประกาศงาน</h3>
        <p>ยังไม่มีประกาศงานของคุณ เพิ่มประกาศงานของคุณเพื่อค้นหาพนักงานใหม่</p>
        <a href="{{$jobPostUrl}}" class="button">ลงประกาศงาน</a>
      </div>
    </div>

  @endif

</div>

@stop