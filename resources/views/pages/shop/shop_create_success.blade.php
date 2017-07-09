@extends('layouts.blackbox.main')
@section('content')

<div class="container">

  <div class="checkout-success">

    <div class="text-center">
      <img class="primary-image" src="/images/common/tick.png">
    </div>

    <div class="checkout-success-message text-center">

      <h2>ร้านค้าของคุณพร้อมใช้งานแล้ว</h2>
      <p>สามารถเพิ่มสินค้าไปยังร้านค้าของคุณได้แล้ว รวมถึงเพิ่มข้อมูลและปรับแต่งร้านค้าของคุณเพื่อให้ลูกค้าเข้ามาเยี่ยมชมและเลือกซื้อสินค้าของคุณ</p>

      <div class="button-group">
        <a class="button" href="{{$shopUrl}}">หน้าหลักร้านค้าของคุณ</a>
        <a class="button" href="{{$addProductUrl}}">เพิ่มสินค้าของคุณ</a>
      </div>

      <div class="line space-top-bottom-30"></div>

      เรียนรู้วิธีการใช้งาน <a href="{{URL::to('manual')}}">วิธีการใช้งาน</a>

    </div>

  </div>

</div>

@stop