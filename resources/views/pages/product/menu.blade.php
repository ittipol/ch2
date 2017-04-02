@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper">
  <div class="container">
    <div class="top-header">
      <div class="row">
        <div class="col-md-4 col-sm-12">
          <a href="{{URL::to($productDetailUrl)}}">
            <img class="header-primary-image" src="{{$imageUrl}}">
          </a>
        </div>
        <div class="header-info col-md-8 col-sm-12">
          <a href="{{URL::to($productDetailUrl)}}">
            <h2>{{$_modelData['name']}}</h2>
          </a>
          @if(!empty($_modelData['promotion']))
            <h4>{{$_modelData['promotion']['_reduced_price']}}</h4>
            <h5 class="text-line-through">{{$_modelData['_price']}}</h5>
          @else
            <h4>{{$_modelData['_price']}}</h4>
          @endif
          <div class="line"></div>
          <div class="space-top-20">
            @if(!empty($_modelData['_categoryPaths']))
            <ol class="breadcrumb">
              @foreach($_modelData['_categoryPaths'] as $path)
              <li class="breadcrumb-item">{{$path['name']}}</li>
              @endforeach
            </ol>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container">

  @if(Session::has('product_added'))
    <div class="alert alert-info">
      <h4><strong>โปรดตรวจสอบและเพิ่มข้อมูลสินค้าให้ครบถ้วนก่อนการขายสินค้านี้</strong></h4> 
      ข้อมูลบางส่วนที่จำเป็นของสินค้านี้อาจยังไม่ได้ถูกเพิ่ม โปรดตรวจสอบและเพิ่มข้อมูลของสินค้าก่อนการขายสินค้า
    </div>
  @endif

  @if($_modelData['active'])
    <div class="alert alert-success">
      <h4><strong>สถานะการขาย</strong>: {{$_modelData['_active']}}</h4> 
      สามารถสั่งซื้อสินค้านี้ได้แล้ว
    </div>
  @else
    <div class="alert alert-danger">
      <h4><strong>สถานะการขาย</strong>: {{$_modelData['_active']}}</h4>
      <p>ไม่สามารถสั่งซื้อสินค้านี้ได้เมื่ออยู่ในสถานะ "ปิดการขาย"</p>
      <br/>
      <p>เปิดการขายสินค้านี้โดยไปยังหน้า "สถานะสินค้า" และเลือก "เปิดการขายสินค้านี้" <a href="{{$productStatusUrl}}">ไปยังหน้าสถานะสินค้า</a></p>
    </div>
  @endif

  @if($_modelData['quantity'] == 0)
  <div class="alert alert-danger">
    <h4>สินค้าหมด</h4>
    สินค้านี้จะไม่สามารถสั่งซื้อได้จนกว่าจะเพิ่มจำนวนสินค้า <a href="{{$productStockEditUrl}}">ไปยังหน้าปรับสินค้า</a>
  </div>
  @elseif(false && $_modelData['quantity'] < 11)
  <div class="alert alert-warning">
    <h4>สินค้าใกล้หมด</h4>
    โปรดเพิ่มจำนวนสินค้าของคุณ เพื่อให้ลูกค้าสามารถสั่งซื้อสินค้านี้ได้ <a href="{{$productStockEditUrl}}">ไปยังหน้าปรับสินค้า</a>
  </div>
  @endif
  
  <div class="list-item-group">

    <div class="list-item">
      <a href="{{$productStatusUrl}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>สถานะสินค้า</h4>
      </a>
      <div class="list-item-group-info">
        <h5><b>สถานะการขาย</b>: {{$_modelData['_active']}}</h5>
      </div>
    </div>

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
        <h4>หมวดสินค้า</h4>
      </a>
      <div class="list-item-group-info">
        <h5><b>หมวดสินค้า</b>: {{$_modelData['_categoryPathName']}}</h5>
      </div>
    </div>

    <div class="list-item">
      <a href="{{$productMinimumEditUrl}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>การสั่งซื้อขั้นต่ำ</h4>
      </a>
      <div class="list-item-group-info">
        <h5><b>จำนวนการซื้อขั้นต่ำ</b>: {{$_modelData['minimum']}} {{$_modelData['product_unit']}} / การสั่งซื้อ</h5>
      </div>
    </div>

    <div class="list-item">
      <a href="{{$productStockEditUrl}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>จำนวนสินค้า</h4>
      </a>
      <div class="list-item-group-info">
        <h5><b>จำนวนคงเหลือปัจจุบัน</b>: {{$_modelData['quantity']}} {{$_modelData['product_unit']}}</h5>
      </div>
    </div>

    <div class="list-item">
      <a href="{{$productPriceEditUrl}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>ราคาสินค้า</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{$productSalePromotionUrl}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>โปรโมชั่นการขาย</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{$productShippingUrl}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>การคำนวณขนส่งสินค้า</h4>
      </a>
      <div class="list-item-group-info">
        <h5><b>วิธีการคำนวณค่าขนส่ง</b>: {{$_modelData['_shipping_calculate_from']}}</h5>
        @if($_modelData['shipping_calculate_from'] == 2)
        <h5>
          <b>ค่าใช้จ่ายในการจัดส่งสินค้า</b>: 
          {{$_modelData['shippingCostText']}}
          @if(!empty($_modelData['shippingCostAppendText']))
            / {{$_modelData['shippingCostAppendText']}}
          @endif
        </h5>
        @endif
      </div>
    </div>

    <div class="list-item">
      <a href="{{$productNotificationEditUrl}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>ข้อความและการแจ้งเตือน</h4>
      </a>
    </div>

<!--     <div class="list-item">
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
        <h4>รายละเอียดการจ่ายเงินสินค้า</h4>
      </a>
    </div> -->

  </div>

</div>

@stop