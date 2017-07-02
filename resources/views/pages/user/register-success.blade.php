@extends('layouts.default.main')
@section('content')

<div class="container">

  <div class="checkout-success">

    <div class="text-center">
      <img class="primary-image" src="/images/common/tick.png">
    </div>

    <div class="checkout-success-message text-center">

      <h2>การสมัครสมาชิกของคุณเรียบร้อยแล้ว</h2>
      <h4>เราได้ส่งรายละเอียดการยันบัญชีไปยังอีเมลของคุณแล้ว โปรดยืนยันบัญชีของคุณเพื่อยืนยันว่านี่เป็นบัญชีที่ถูกต้อง</h4>
      <h4>คุณควรได้รับอีเมลพร้อมรายละเอียดเพิ่มเติมเร็วๆนี้ หากยังไม่ถึงภายในไม่กี่นาทีให้ตรวจสอบโฟลเดอร์สแปมของคุณ</h4>

      <a class="button" href="{{URL::to('login')}}">เข้าสู่ระบบ</a>

    </div>

  </div>

</div>

@stop