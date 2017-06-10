@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.fixed_top_nav')

<div class="container list space-top-30">

  <h3>วิธีการชำระเงิน</h3>
  <div class="line space-bottom-20"></div>

  @if(!empty($paymentMethods))

    @foreach($paymentMethods as $paymentMethod)

      <div class="payment-method-list">
        <h4 class="space-bottom-20"><img src="{{$paymentMethod['image']}}">{{$paymentMethod['name']}}</h4>
        @include('pages.payment_method.layouts.display')
      </div>

      <div class="line grey space-top-bottom-40"></div>

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