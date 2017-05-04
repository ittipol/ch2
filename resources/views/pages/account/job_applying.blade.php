@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">งานที่สมัคร</h2>
      </div>
    </div>
  </div>
</div>

<div class="container list">

  @if(!empty($_pagination['data']))

    <div class="row">

      @foreach($_pagination['data'] as $data)

      <div class="col-lg-3 col-xs-6">
        <div class="card">
          <div class="image-tile">
            <a href="{{$data['detailUrl']}}">
              <div class="card-image" style="background-image:url({{$data['_jobImageUrl']}});"></div>
            </a>
          </div>
          <div class="card-info">
            <a href="{{$data['detailUrl']}}">
              <div class="card-title">{{$data['_jobNameShort']}}</div>
            </a>
            <div class="card-sub-info">
              <h5>สมัครเมื่อ</h5>
              {{$data['createdDate']}}
            </div>
          </div>
        </div>
      </div>
      @endforeach

    </div>

    @include('components.pagination') 

  @else

  <div class="list-empty-message text-center space-top-20">
    <img class="space-bottom-20" src="/images/common/not-found.png">
    <div>
      <h3>ยังไม่มีงานที่สมัคร</h3>
      <a href="{{URL::to('job/list')}}" class="button">ค้นหางาน</a>
    </div>
  </div>

  @endif

</div>

@stop