@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{URL::to('job/board')}}" class="btn btn-secondary">เพิ่มประวัติการทำงาน</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{URL::to('community/shop/create')}}">ฟรีแลนซ์</a>
            </div>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="container list">

  <h3>ประวัติการทำงานบุคคล</h3>
  <div class="line"></div>
  
  @if(!empty($_pagination['data']))

    <div class="row">

      @foreach($_pagination['data'] as $data)

      <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
        <div class="card">
          <div class="image-tile">
            <a href="{{$data['detailUrl']}}">
              <div class="card-image round" style="background-image:url({{$data['_imageUrl']}});"></div>
            </a>
          </div>
          <div class="card-info">
            <a href="{{$data['detailUrl']}}">
              <div class="card-name">{{$data['name']}}</div>
            </a>
            @if(!empty($data['careerObjective']) && ($data['careerObjective'] != '-'))
            <div class="card-desciption">
              {{$data['careerObjective']}}
            </div>
            @endif
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
      <h3>ไม่พบข้อมูล ประวัติการทำงาน</h3>
      <a href="{{URL::to('person/experience')}}" class="button">เพิ่มข้อมูลประวัติการทำงาน</a>
    </div>
  </div>

  @endif

</div>

@stop