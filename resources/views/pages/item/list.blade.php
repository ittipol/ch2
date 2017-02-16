@extends('layouts.blackbox.main')
@section('content')

  <div class="container list">

    @if(!empty($_pagination['data']))

      <div class="row">

        @foreach($_pagination['data'] as $data)

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
          <div class="card">
            <div class="image">
              <a href="{{$data['detailUrl']}}">
                <div class="product-image" style="background-image:url({{$data['_imageUrl']}});"></div>
              </a>
            </div>
            <div class="product-detail">
              <a href="{{$data['detailUrl']}}">
                <div class="product-title">{{$data['_name_short']}}</div>
              </a>
              <div class="price">
                {{$data['_price']}}
              </div>
            </div>
            <div>
              <a href="{{$data['detailUrl']}}"><div class="button wide-button">แสดงสินค้านี้</div></a>
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