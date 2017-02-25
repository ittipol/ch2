@extends('layouts.blackbox.main')
@section('content')

<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-lg-6 col-sm-12">
        <div class="title">
          ฟรีแลนซ์
        </div>
      </div>
    </div>
  </div>

  <div class="tile-nav-group space-top-bottom-20 clearfix">

    <div class="tile-nav small">
      <div class="tile-nav-image">
        <a href="{{URL::to('person/freelance_post')}}">
          <img src="/images/common/plus.png">
        </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{URL::to('person/freelance_post')}}">
          <h4 class="tile-nav-title">เพิ่มงานฟรีแลนซ์ของคุณ</h4>
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
                <div class="card-title">{{$data['name']}}</div>
              </a>
            </div>

            <div class="button-group">

              <a href="{{$data['detailUrl']}}">
                <div class="button wide-button">แสดงรายละเอียด</div>
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

    <div class="list-empty-message text-center space-top-20">
      <img class="space-bottom-20" src="/images/common/career.png">
      <div>
        <h3>ฟรีแลนซ์</h3>
        <p>ยังไม่มีงานฟรีแลนซ์ เพิ่มฟรีแลนซ์ของคุณ เพื่อให้ผู้คนได้เห็นและจ้างงานฟรีแลนซ์คุณได้</p>
        <a href="{{URL::to('person/freelance_post')}}" class="button">เพิ่มสาขา</a>
      </div>
    </div>

  @endif

</div>

@stop