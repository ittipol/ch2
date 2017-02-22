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
                <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
              </a>
            </div>
            <div class="card-info">
              <a href="{{$data['detailUrl']}}">
                <div class="card-title">{{$data['_short_name']}}</div>
              </a>
              <div class="price">
                {{$data['_salary']}}
              </div>
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