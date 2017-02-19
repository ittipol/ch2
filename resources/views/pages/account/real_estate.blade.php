@extends('layouts.blackbox.main')
@section('content')

  <div class="container list">

    @if(!empty($_pagination['data']))

      <div class="row">

        @foreach($_pagination['data'] as $data)

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
          <div class="card">

            <div class="overlay-top-info text-center">{{$data['_realEstateTypeName']}}</div>

            <div class="image">
              <a href="{{$data['detailUrl']}}">
                <div class="product-image" style="background-image:url({{$data['_imageUrl']}});"></div>
              </a>
            </div>
            <div class="product-detail">
              <a href="{{$data['detailUrl']}}">
                <div class="product-title">{{$data['_short_name']}}</div>
              </a>
              <div class="price">
                {{$data['_price']}}
              </div>
            </div>
            
            <div class="button-group">

              <a href="{{$data['detailUrl']}}">
                <div class="button wide-button">แสดงรายละเอียด</div>
              </a>

              <div class="additional-option">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="additional-option-content">
                  <a href="{{$data['editUrl']}}">แก้ไข</a>
                  <a href="{{$data['deleteUrl']}}">ลบ</a>
                </div>
              </div>
            
            </div>
            
          </div>
        </div>

        @endforeach

      </div>

      @include('components.pagination') 

    @else

    <div class="shop-notice text-center space-top-20">
      <img class="space-bottom-20" src="/images/common/not-found.png">
      <div>
        <h3>ยังไม่มีข้อมูลนี้</h3>
        <p>ขออภัย ยังไม่มีข้อมูลซื้อ ขายอสังหาริมทรัพย์ของคุณ</p>
        <a href="{{URL::to('real-estate/post')}}" class="button">เพิ่มข้อมูลซื้อ ขายอสังหาริมทรัพย์ของคุณ</a>
      </div>
    </div>

    @endif

  </div>

@stop