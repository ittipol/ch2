@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.fixed_top_nav')

<div class="container list space-top-30">

  <h3>วิธีการชำระเงิน</h3>
  <div class="line space-bottom-20"></div>

  @if(!empty($paymentMethods))

    @foreach($paymentMethods as $paymentMethod)

      <div class="space-bottom-20">

        <h4>{{$paymentMethod['name']}}</h4>

        <div class="list-h">

          @foreach($paymentMethod['data'] as $data)

            <div class="list-h-item no-border clearfix">

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
                  <a data-right-side-panel="1" data-right-side-panel-target="#payment_method_{{$data['id']}}">แสดงรายละเอียด</a>
                </div>
              </div>

            </div>

            <div id="payment_method_{{$data['id']}}" class="right-size-panel">
              <div class="right-size-panel-inner">
                  <h4>{{$paymentMethod['name']}}</h4>
                  <h4>{{$data['name']}}</h4>
                  <div class="line space-bottom-10"></div>
                  <h5 class="space-top-20">รายละเอียดการชำระเงิน</h5>
                  @if(empty($data['description']))
                  -
                  @else
                  {!!$data['description']!!}
                  @endif
                <div class="right-size-panel-close-button"></div>
              </div>
            </div>

          @endforeach

        </div>

      </div>

    @endforeach

  @else

    <div class="list-empty-message text-center space-top-20">
      <img class="space-bottom-20 not-found-image" src="/images/common/not-found.png">
      <div>
        <h3>ยังไม่มีวิธีการชำระเงิน</h3>
      </div>
    </div>

  @endif

</div>

@stop