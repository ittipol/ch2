@extends('layouts.blackbox.main')
@section('content')

  <div class="container space-top-30">

    <h3 class="space-bottom-50">การแจ้งเตือน</h3>

    <div class="row">

      <div class="col-xs-12">

        <div class="overflow-hidden">

          การแจ้งเตือนจะเป็นแจ้งเตือนภายในเว็บไชต์ เช่น การแจ้งเตือนเมื่อมีการสั่งซื้อสินค้า หรือ การแจ้งเตือนเมื่อลูกค้าแจ้งการชำระเงิน เป็นต้น 
          <br><br>

          <strong>ข้อความการแจ้งเตือน</strong><br>
          เมนูด้านบนคลิกรูประฆัง จะแสดงหน้าการแจ้งเตือน
          <br>
          ตัวเลขที่อยู่บนรูประฆัง จะเป็นตัวบ่งบอกการเตือนใหม่เข้ามา<br><br>
          <img src="/images/manual/notification/top-bar.png">
          <br><br>

          การแจ้งเตือนจะถูกโดยเรียกตามวันที่ใหม่สุดไปเก่าสุด<br><br>
          <img src="/images/manual/notification/notification-display.png">
          <br><br>

        </div>

      </div>

    </div>

  </div>

@stop