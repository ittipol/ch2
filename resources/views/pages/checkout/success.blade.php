@extends('layouts.blackbox.main')
@section('content')

<div class="container">

  <div class="checkout-success">

    <div class="text-center">
      <img class="primary-image" src="/images/common/tick.png">
    </div>

    <div class="checkout-success-message text-center">

      <h2>การสั่งซื้อสินค้าของคุณเรียบร้อยแล้ว</h2>
      <p>การสั่งซื้อของคุณถูกส่งไปยังผู้ขายแล้ว โปรดรอการยืนยันการสั่งซื้อจากผู้ขายก่อนการชำระเงิน</p>
      <a class="button" href="{{URL::to('account/order')}}">แสดงรายการสั่งซื้อสินค้าของคุณ</a>

    </div>

  </div>

</div>

@stop