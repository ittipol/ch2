@extends('layouts.blackbox.main')
@section('content')

<div class="container list space-top-30">

  <h3>ประวัติการทำงานบุคคล</h3>
  <div class="line"></div>
  <div class="text-right space-top-bottom-20">
    <a class="button" data-right-side-panel="1" data-right-side-panel-target="#filter_expand_panel">ตัวกรอง</a>
  </div>

  <div id="filter_expand_panel" class="right-size-panel filter">
    <div class="right-size-panel-inner">
      @include('components.filter_expand_panel')
      <div class="right-size-panel-close-button"></div>
    </div>
  </div>
  
  @if(!empty($_pagination['data']))

    <div class="row">

      @foreach($_pagination['data'] as $data)

      <div class="col-lg-3 col-xs-6">
        <div class="card">
          <div class="image-tile">
            <a href="{{$data['detailUrl']}}">
              <div class="card-image cover" style="background-image:url({{$data['_imageUrl']}});"></div>
            </a>
          </div>
          <div class="card-info">
            <a href="{{$data['detailUrl']}}">
              <div class="card-name">{{$data['name']}}</div>
            </a>
            <div class="card-sub-info">
              <h5>เพศ</h5>
              <div>{{$data['gender']}}</div>
            </div>
            <div class="card-sub-info">
              <h5>ตำแหน่งงานล่าสุด</h5>
              <div>{{$data['position']}}</div>
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
      <h3>ไม่พบประวัติการทำงานรายบุคคล</h3>
      <a href="{{URL::to('resume')}}" class="button">เพิ่มข้อมูลประวัติการทำงาน</a>
    </div>
  </div>

  @endif

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const filter = new Filter(true);
    filter.load();

  });

</script>

@stop