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

              <h4>การสั่งซื้อหมายเลข: {{$data['id']}}</h4>

              <div class="card-sub-info">
                <h5>ยอดสุทธิ</h5>
                {{$data['_total']}}
              </div>

              <div class="card-sub-info">
                <h5>สถานะการสั่งซื้อ</h5>
                {{$data['OrderStatusName']}}
              </div>

              <div class="card-sub-info">
                <h5>สั่งซื้อเมื่อ</h5>
                {{$data['orderedDate']}}
              </div>
              
              <div class="button-group">

                <a href="{{$data['detailUrl']}}">
                  <div class="button wide-button">แสดงรายละเอียด</div>
                </a>
              
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