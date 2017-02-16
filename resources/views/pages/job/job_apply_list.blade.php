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
            <div class="info">
              <a class="person-info" href="{{$data['detailUrl']}}">
                <h4>ชื่อผู้สมัคร</h4>
                <div class="person-name">{{$data['personName']}}</div>
              </a>
              <div class="job-info">
                <h4>งานที่สมัคร</h4>
                <div class="job-name">{{$data['_jobNameShort']}}</div>
              </div>
            </div>
            <div>
              <a href="{{$data['detailUrl']}}"><div class="button wide-button">แสดงรายละเอียด</div></a>
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