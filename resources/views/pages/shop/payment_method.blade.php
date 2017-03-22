@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.top_nav')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">วิธีการชำระเงิน</h2>
      </div>
    </div>
  </div>
</div>

<div class="container">

  <div class="tile-nav-group space-top-bottom-20 clearfix">

    <div class="tile-nav small">
      <div class="tile-nav-image">
          <a href="{{$paymentMethodAddUrl}}">
            <img src="/images/common/payment.png">
          </a>
      </div>
      <div class="tile-nav-info">
        <a href="{{$paymentMethodAddUrl}}">
          <h4 class="tile-nav-title">เพิ่มวิธีการชำระเงิน</h4>
        </a>
      </div>
    </div>

  </div>

  <div class="line"></div>

  @if(!empty($_pagination['data']))

    <div class="list-h">
    @foreach($_pagination['data'] as $data)
      <div class="list-h-item clearfix">

        <div class="list-image pull-left">
          <a href="{{$data['detailUrl']}}">
            <img src="/images/icons/payment-white.png">
          </a>
        </div>

        <div class="col-md-11 col-xs-8">

          <div class="row">

            <div class="col-xs-12 list-content">
              <a href="{{$data['detailUrl']}}">
                <h4 class="primary-info single-info">{{$data['name']}}</h4>
              </a>
            </div>

          </div>

        </div>
        
        <div class="additional-option">
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="additional-option-content">
            <a href="{{$data['editUrl']}}">แก้ไข</a>
            <a href="{{$data['deleteUrl']}}" data-modal="1" data-modal-action="delete">ลบ</a>
          </div>
        </div>

      </div>
    @endforeach
    </div>

    @include('components.pagination')

  @else

    <div class="list-empty-message text-center space-top-20">
      <img class="space-bottom-20" src="/images/common/payment.png">
      <div>
        <h3>วิธีการชำระเงิน</h3>
        <p>ยังไม่มีวิธีการชำระเงิน เพิ่มวิธีการชำระเงินของคุณ เพื่อใช่ในการกำหนดวิธีการชำระเงินให้กับลูกค้าในหน้าการยืนยันการสั่งซื้อ</p>
        <a href="{{$paymentMethodAddUrl}}" class="button">เพิ่มวิธีการชำระเงิน</a>
      </div>
    </div>

  @endif

</div>

@stop