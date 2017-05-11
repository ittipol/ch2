@extends('layouts.blackbox.main')
@section('content')

<div class="container list list space-top-30">

  <h3>บริษัทหรือร้านค้าของคุณ</h3>
  <div class="line space-bottom-50"></div>

  @if(!empty($_pagination['data']))

    <div class="row">

      @foreach($_pagination['data'] as $data)

      <div class="col-lg-3 col-xs-6">
        <div class="card">
          <div class="image-tile">
            <a href="{{$data['shopUrl']}}">
              <div class="card-image" style="background-image:url({{$data['cover']}});"></div>
            </a>
          </div>
          <div class="card-info">
            <a href="{{$data['shopUrl']}}">
              <div class="card-title">{{$data['name']}}</div>
            </a>
          </div>
          
          <div class="button-group">

            <a href="{{$data['shopUrl']}}">
              <div class="button wide-button">ไปยังร้านค้า</div>
            </a>

            <div class="additional-option">
              <div class="dot"></div>
              <div class="dot"></div>
              <div class="dot"></div>
              <div class="additional-option-content">
                <a href="{{$data['shopManageUrl']}}">ภาพรวมร้านค้า</a>
                <a href="{{$data['shopSettingUrl']}}">ข้อมูลร้านค้า</a>
              </div>
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
      <h3>ยังไม่มีข้อมูลนี้</h3>
      <p>ขออภัย ยังไม่มีข้อมูลร้านค้าในชุมชนของคุณ</p>
      <a href="{{URL::to('shop/create')}}" class="button">เพิ่มร้านค้าในชุมชนของคุณ</a>
    </div>
  </div>

  @endif

</div>

@stop