@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}" class="btn btn-secondary">ไปยังหน้าหลักร้านค้า</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}product">ไปยังหน้าหลักสินค้า</a>
              <a href="{{request()->get('shopUrl')}}job">ไปยังหน้าหลักประกาศงาน</a>
            </div>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="container list  space-top-30">

  <h3>โฆษณาจาก {{request()->get('shop')->name}}</h3>
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

      <div class="col-lg-3 col-sm-4 col-xs-12">
        <div class="card">
          <div class="image-tile">
            <a href="{{$data['detailUrl']}}">
              <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
            </a>
          </div>
          <div class="card-info">
            <a href="{{$data['detailUrl']}}">
              <div class="card-title">{{$data['_short_name']}}</div>
            </a>
            <div class="card-sub-info">
              <div>ประเภทโฆษณา</div>
              {{$data['_advertisingType']}}
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
      <h3>ไม่มีข้อมูลโฆษณาให้แสดง</h3>
    </div>
  </div>

  @endif

</div>

@stop