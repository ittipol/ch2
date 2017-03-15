@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">รายการสั่งซื้อสินค้า</h2>
      </div>
    </div>
  </div>
</div>

<div class="container list">

  @if(!empty($_pagination['data']))

    <div class="list-h">
    @foreach($_pagination['data'] as $data)
      <div class="list-h-item clearfix">

        <div class="list-image pull-left">
          <a href="{{$data['detailUrl']}}">
            <img src="/images/icons/bag-white.png">
          </a>
        </div>

        <div class="col-md-11 col-xs-8">

          <div class="row">

            <div class="col-md-4 col-xs-12 list-content">
              <a href="{{$data['detailUrl']}}">
                <h4 class="primary-info">{{$data['shopName']}}</h4>
              </a>
              <div class="secondary-info">เลขที่การสั่งซื้อ: {{$data['invoice_number']}}</div>
              <div class="secondary-info">สั่งซื้อเมื่อ: {{$data['orderedDate']}}</div>
            </div>

            <div class="col-md-4 col-xs-12 list-content">
              <h4 class="primary-info">สถานะการสั่งซื้อ</h4>
              <div class="secondary-info">{{$data['OrderStatusName']}}</div>
            </div>

            <div class="col-md-4 col-xs-12 list-content">
              <h4 class="primary-info">ยอดสุทธิ</h4>
              <div class="secondary-info">{{$data['_total']}}</div>
            </div>

          </div>

        </div>
        
        <div class="additional-option">
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="additional-option-content">
            <a href="{{$data['detailUrl']}}">แสดงรายละเอียด</a>
            <a href="">ลบ</a>
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
      <h3>ยังไม่มีรายการสั่งซื้อสินค้า</h3>
      <a href="{{URL::to('product/list')}}" class="button">เลือกซื้อสินค้า</a>
    </div>
  </div>

  @endif

</div>

@stop