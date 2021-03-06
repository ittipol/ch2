@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.fixed_top_nav')

<div class="container list space-top-30">

  <h3>แคตตาล็อกสินค้าจาก {{request()->get('shop')->name}}</h3>
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

      <div class="col-md-4 col-xs-6">
        <div class="card">

          <div class="image-tile">
            <a href="{{$data['detailUrl']}}">
              <div class="card-image cover" style="background-image:url({{$data['_imageUrl']}});"></div>
            </a>
          </div>
          
          <div class="card-info">
            <a href="{{$data['detailUrl']}}">
              <div class="card-title">{{$data['name']}}</div>
            </a>
          </div>

          <div class="button-group">

            <a href="{{$data['detailUrl']}}">
              <div class="button wide-button">แสดงสินค้าในแคตตาล็อก</div>
            </a>
          
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
      <h3>ไม่พบแคตตาล็อกสินค้า</h3>
    </div>
  </div>

  @endif

</div>

@stop