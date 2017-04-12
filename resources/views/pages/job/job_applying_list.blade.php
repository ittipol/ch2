@extends('layouts.blackbox.main')
@section('content')

  <div class="container list">

    @if(!empty($_pagination['data']))

      <div class="row">

        @foreach($_pagination['data'] as $data)

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">

          <div class="card">

            <div class="image-tile cover">
              <a href="{{$data['detailUrl']}}">
                <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
              </a>
            </div>
            <div class="card-info">
              <div class="card-sub-info">
                <h5>ตำแหน่งงาน</h5>
                {{$data['_jobNameShort']}}
              </div>
              <div class="card-sub-info">
                <h5>ชื่อผู้สมัคร</h5>
                {{$data['personName']}}
              </div>
              <div class="card-sub-info">
                <h5>สมัครเมื่อ</h5>
                {{$data['createdDate']}}
              </div>
            </div>

            <div class="button-group">
              <a href="{{$data['detailUrl']}}"><div class="button wide-button">แสดงรายละเอียด</div></a>
            </div>

          </div>

        </div>

        @endforeach

      </div>

      @include('components.pagination') 

    @else

    <div class="list-empty-message text-center space-top-20">
      <img class="space-bottom-20" src="/images/common/resume.png">
      <div>
        <h3>ยังไม่มีรายชื่อผู้ที่สมัครงานของคุณ</h3>
      </div>
    </div>

    @endif

  </div>

@stop