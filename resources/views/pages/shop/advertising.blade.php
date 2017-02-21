@extends('layouts.blackbox.main')
@section('content')

<div class="container">

  <div class="tile-nav-group space-top-bottom-20 clearfix">

    <div class="tile-nav xxs">
      <div class="tile-nav-image">
        <a href="{{$advertisingPostUrl}}">
          <img src="/images/common/plus.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{$advertisingPostUrl}}">
          <h4 class="tile-nav-title">ลงโฆษณา</h4>
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
                <div class="product-title">{{$data['_short_name']}}</div>
              </a>
              <div>
                <div>ประเภทโฆษณา</div>
                {{$data['_advertisingType']}}
              </div>
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
      <img class="space-bottom-20" src="/images/common/megaphone.png">
      <div>
        <h3>โฆษณา</h3>
        <p>ยังไม่มีประกาศงานของคุณ เพิ่มประกาศงานของคุณเพื่อค้นหาพนักงานใหม่</p>
        <a href="{{$advertisingPostUrl}}" class="button">ลงโฆษณา</a>
      </div>
    </div>

  @endif

</div>

@stop