@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.fixed_top_nav_admin')

<div class="container list space-top-30">

  <h3>รายชื่อผู้สมัครงาน</h3>
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

          <div class="image-tile cover">
            <a href="{{$data['detailUrl']}}">
              <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
            </a>
          </div>
          <div class="card-info">
            <div class="card-sub-info">
              <h5>สมัครงานตำแหน่ง</h5>
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
    <img src="/images/common/not-found.png">
    <div>
      <h3>ยังไม่มีรายชื่อผู้สมัครงาน</h3>
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