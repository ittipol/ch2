@extends('layouts.blackbox.main')
@section('content')

<div class="container list list space-top-30">

  <h3>ร้านค้าของฉัน</h3>
  <div class="line space-bottom-50"></div>

  @if(!empty($_pagination['data']))

    <div class="row">

      @foreach($_pagination['data'] as $data)

      <div class="col-lg-3 col-sm-4 col-xs-6">
        <div class="card sm">
          <div class="image-tile">
            <a href="{{$data['shopUrl']}}">
              <div class="card-image" style="background-image:url({{$data['profileImage']}});"></div>
            </a>
          </div>
          <div class="card-info">
            <a href="{{$data['shopUrl']}}">
              <div class="card-title">{{$data['name']}}</div>
            </a>
            <div class="card-desciption">
              {!!$data['description']!!}
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
      <h3>ยังไม่มีร้านค้า</h3>
      <a href="{{URL::to('shop/create')}}" class="button">สร้างร้านค้า</a>
    </div>
  </div>

  @endif

</div>

@stop