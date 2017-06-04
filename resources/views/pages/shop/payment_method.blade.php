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

  <h4>รายการวิธีการชำระเงิน</h4>
  <div class="line space-bottom-20"></div>

  @foreach($paymentMethods as $paymentMethod)

    <div class="space-bottom-50">

      <h4>{{$paymentMethod['name']}}</h4>

      <div class="clearfix">
        <div class="tile-nav xs pull-left">
          <div class="tile-nav-image">
            <a href="{{$paymentMethod['addUrl']}}">
              <img src="/images/common/plus.png">
            </a>
          </div>
        </div>
        <a href="{{$paymentMethod['addUrl']}}" class="tile-nav-title pull-left">เพิ่มตัวเลือก{{$paymentMethod['name']}}</a>
      </div>

      @if(!empty($paymentMethod['data']))

        @foreach($paymentMethod['data'] as $data)

          

        @endforeach

      @else

        <h5 class="text-center">ยังไม่มีตัวเลือก{{$paymentMethod['name']}}</h5>

      @endif

    </div>

  @endforeach

</div>

@stop