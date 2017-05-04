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
              <a href="{{request()->get('shopUrl')}}advertising">ไปยังหน้าหลักโฆษณา</a>
            </div>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="container list space-top-30">

  <h3>สาขา</h3>
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
              <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
            </a>
          </div>
          <div class="card-info">
            <a href="{{$data['detailUrl']}}">
              <div class="card-title">{{$data['name']}}</div>
            </a>
          </div>
          <div>
            <a href="{{$data['detailUrl']}}"><div class="button wide-button">แสดง</div></a>
          </div>
        </div>
      </div>
      @endforeach

    </div>

    @include('components.pagination') 

  @else

  <h3>ไม่พบสินค้า</h3>

  @endif

</div>

@stop