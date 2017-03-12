@extends('layouts.blackbox.main')
@section('content')

  <div class="container list">

    <div class="container-header">
      <div class="row">
        <div class="col-lg-6 col-sm-12">
          <div class="title">
            รายการสั่งซื้อสินค้า
          </div>
        </div>
      </div>
    </div>

    @if(!empty($_pagination['data']))

      <div class="grid-card">

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
                  <div class="card-title">{{$data['id']}}</div>
                </a>
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

      </div>

      @include('components.pagination') 

    @else

    <div class="list-empty-message text-center space-top-20">
      <img class="space-bottom-20" src="/images/common/not-found.png">
      <div>
        <h3>ยังไม่มีรายการสั่งซื้อสินค้า</h3>
        <a href="{{URL::to('product/list')}}" class="button">เลือกซื้อสินค้า</a>
      </div>
    </div>

    @endif

  </div>

@stop