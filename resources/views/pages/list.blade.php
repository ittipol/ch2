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
                <div class="card-image round" style="background-image:url({{$data['_imageUrl']}});"></div>
              </a>
            </div>
            <div class="card-info">
              <a href="{{$data['detailUrl']}}">
                <div class="card-title text-center">{{$data['name']}}</div>
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
        <h3>ยังไม่มีข้อมูลนี้</h3>
        <p>ขออภัย ยังไม่มีข้อมูลซื้อ ขายสินค้า</p>
        <a href="{{URL::to('item/post')}}" class="button">เพิ่มข้อมูลซื้อ ขายสินค้า</a>
      </div>
    </div>

    @endif

  </div>

@stop