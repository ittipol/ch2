@extends('layouts.blackbox.main')
@section('content')

  <div class="container list">

    @if(!empty($_pagination['data']))

      <div class="row">

        @foreach($_pagination['data'] as $data)

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
          <div class="card">
            <div class="image-tile">
              <a href="{{$data['detailUrl']}}">
                <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
              </a>
            </div>
            <div class="info">
              <a class="person-info" href="{{$data['detailUrl']}}">
                <h4>ชื่อผู้สมัคร</h4>
                <div class="person-name">{{$data['personName']}}</div>
              </a>
              <div class="job-info">
                <h4>งานที่สมัคร</h4>
                <div class="job-name">{{$data['_jobNameShort']}}</div>
              </div>
            </div>
            <div>
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