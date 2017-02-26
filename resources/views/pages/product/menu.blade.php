@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper">
  <div class="top-header">
    <img src="{{$imageUrl}}">
    <h2>{{$_modelData['name']}}</h2>
  </div>
</div>

<div class="container">
  
  <div class="list-item-group">

    <div class="list-item">
      <a href="{{$productEditUrl}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>ข้อมูลทั้วไปของสินค้า</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{$productSpecificationEditUrl}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>ข้อมูลจำเพาะของสินค้า</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{$productCategoryEditUrl}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>ประเภทสินค้า</h4>
      </a>
      <p>กำหนดประเภทสินค้าที่เหมาะสมกับสินค้า และจะทำให้ผู้ซื้อสามารถหาสินค้าได้ง่ายและสามรถสื่อความหมายของสินค้านั้นได้</p>
    </div>

    <div class="list-item">
      <a href="">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>กำหนดสต็อกสินค้า</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>กำหนดราคาและโปรโมชั่นของสินค้า</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>การรับประกันสินค้า</h4>
        <p>การรับประกันภายในประเทศจากผู้ผลิต</p>
      </a>
    </div>

    <div class="list-item">
      <a href="">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>รายละเอียดการคืนสินค้า</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>รายละเอียดการส่งสินค้า</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>รายละเอียดการหีบห่อสินค้า</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>รายละเอียดการจ่ายเงินสินค้าสินค้า</h4>
      </a>
    </div>

  </div>

</div>

@stop