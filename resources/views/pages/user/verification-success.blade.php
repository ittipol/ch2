@extends('layouts.default.main')
@section('content')

<div class="container">

  <div class="checkout-success">

    <div class="text-center">
      <img class="primary-image" src="/images/common/tick.png">
    </div>

    <div class="checkout-success-message text-center">

      <h2>การยืนยันบัญชีของคุณเรียบร้อยแล้ว</h2>
      <h4>บัญชีของคุณสามารถใช้งานได้แล้ว</h4>
      <a class="button" href="{{URL::to('login')}}">เข้าสู่ระบบ</a>

    </div>

  </div>

</div>

@stop