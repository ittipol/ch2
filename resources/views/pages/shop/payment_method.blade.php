@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.fixed_top_nav_admin')

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

  @if(!$hasPaymentMethod)
    <div class="secondary-message-box warning space-bottom-30">
      <div class="secondary-message-box-inner">
        <h4>ยังไม่มีวิธีการชำระเงิน</h4>
        <p>เพิ่มวิธีการชำระเงินอย่างน้อย 1 วิธี เพื่อใช่เป็นตัวเลือกการชำระเงินให้กับลูกค้า</p>
      </div>
    </div>
  @endif

  @foreach($paymentMethods as $paymentMethod)

    <div class="space-bottom-50">

      <h4>{{$paymentMethod['name']}}</h4>

      <div class="clearfix">
        <div class="tile-nav xs transparent-bg pull-left">
          <div class="tile-nav-image">
            <a href="{{$paymentMethod['addUrl']}}">
              <img src="/images/common/plus.png">
            </a>
          </div>
        </div>
        <a href="{{$paymentMethod['addUrl']}}" class="tile-nav-title pull-left">เพิ่มวิธี{{$paymentMethod['name']}}</a>
      </div>

      <!-- <div class="line grey space-top-20"></div> -->

      @if(!empty($paymentMethod['data']))

        <div class="list-h">

          @foreach($paymentMethod['data'] as $data)

            <div class="list-h-item clearfix">

              <a class="list-image pull-left">
                <img src="/images/icons/payment-white.png">
              </a>

              <div class="col-md-11 col-xs-10">

                <div class="row">

                  <div class="col-xs-12 list-content">
                    <h4 class="primary-info single-info">{{$data['name']}}</h4>
                  </div>

                </div>

              </div>
              
              <div class="additional-option">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="additional-option-content">
                  <a href="{{$data['editUrl']}}">แก้ไข</a>
                  <a href="{{$data['deleteUrl']}}" data-modal="1" data-modal-title="ต้องการลบใช่หรือไม่">ลบ</a>
                </div>
              </div>

            </div>

          @endforeach

        </div>

      @else
        <h5 class="text-center space-top-20">ยังไม่มีวิธี{{$paymentMethod['name']}}</h5>
        <div class="line grey space-top-20"></div>
      @endif

    </div>

  @endforeach

</div>

@stop