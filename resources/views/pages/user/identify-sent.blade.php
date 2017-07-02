@extends('layouts.default.main')
@section('content')

<div class="login-logo">
  <a href="{{URL::to('/')}}"><img src="/images/ss_logo/logo-login.png"></a>
</div>

<div class="container">

  <div class="fixed-center-message">

    <div class="fixed-center-message-content text-center">
      <h2>ส่งคำร้องขอไปยังอีเมลของคุณแล้ว</h2>
      <p>คุณควรได้รับอีเมลพร้อมรายละเอียดเพิ่มเติมเร็วๆนี้ หากยังไม่ถึงภายในไม่กี่นาทีให้ตรวจสอบโฟลเดอร์สแปมของคุณ</p>
      <a class="button" href="{{URL::to('login')}}">เข้าสู่ระบบ</a>
    </div>

  </div>

</div>

@stop