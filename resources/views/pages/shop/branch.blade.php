@extends('layouts.blackbox.main')
@section('content')

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-lg-6 col-sm-12">
        <div class="title">
          สาขา
        </div>
      </div>
    </div>
  </div>

  <div class="tile-nav-group space-top-bottom-20 clearfix">

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{$jobUrl}}">
          <img src="/images/common/job.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{$jobUrl}}">
          <h4 class="tile-nav-title">งาน</h4>
        </a>
      </div>
    </div>

    <div class="tile-nav small">
      <div class="tile-nav-image">
          <a href="{{$branchAddUrl}}">
            <img src="/images/common/plus.png">
          </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{$branchAddUrl}}">
          <h4 class="tile-nav-title">เพิ่มสาขา</h4>
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
                <div class="product-title">{{$data['name']}}</div>
              </a>
            </div>
            
            <div class="button-group">

              <a href="{{$data['detailUrl']}}">
                <div class="button wide-button">แสดง</div>
              </a>

              <div class="additional-option">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="additional-option-content">
                  <a href="{{$data['editUrl']}}">แก้ไข</a>
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

    <div class="shop-notice text-center space-top-20">
      <img class="space-bottom-20" src="/images/common/building.png">
      <div>
        <h3>สาขา</h3>
        <p>ยังไม่มีสาขา เพิ่มสาขาเพื่อมให้ผู้ทราบถึงสินค้า งานบริการ หรืออื่นๆ ในแต่ละสาขาของคุณ</p>
        <a href="{{$branchAddUrl}}" class="button">เพิ่มสาขา</a>
      </div>
    </div>

  @endif

</div>

@stop