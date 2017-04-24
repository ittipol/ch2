@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}manage/product" class="btn btn-secondary">กลับไปยังหน้าหลักจัดการสินค้า</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}manage/job">ไปยังหน้าหลักจัดการประกาศงาน</a>
              <a href="{{request()->get('shopUrl')}}manage/advertising">กลับไปยังหน้าหลักจัดการโฆษณา</a>
            </div>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="top-header-wrapper">
  <div class="container">
    <div class="top-header">

      <div class="row">

        <div class="col-md-4 col-sm-12">
          <a href="{{request()->get('shopUrl')}}product/{{$_modelData['id']}}">
            <img class="header-primary-image" src="{{$imageUrl}}">
          </a>
        </div>

        <div class="header-info col-md-8 col-sm-12">
          <a href="{{request()->get('shopUrl')}}product/{{$_modelData['id']}}">
            <h2>{{$_modelData['name']}}</h2>
          </a>

          @if(!empty($_modelData['promotion']))
            <h4>
              {{$_modelData['promotion']['_reduced_price']}}
              <span class="product-discount-tag">{{$_modelData['promotion']['percentDiscount']}}</span>
            </h4>
            <h5 class="text-line-through">{{$_modelData['_price']}}</h5>
          @else
            <h4>{{$_modelData['_price']}}</h4>
          @endif

          <div class="line"></div>

          <div class="space-top-20">
            @if(!empty($categoryPaths))
            <ol class="breadcrumb">
              @foreach($categoryPaths as $path)
              <li class="breadcrumb-item">
                <a href="{{$path['url']}}">{{$path['name']}}</a>
              </li>
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
    <div class="secondary-message-box info space-bottom-30">
      <div class="secondary-message-box-inner">
        <h3>โปรดตรวจสอบและเพิ่มข้อมูลสินค้าให้ครบถ้วนก่อนการขายสินค้านี้</h3>
        <p>ข้อมูลบางส่วนที่จำเป็นของสินค้านี้อาจยังไม่ได้ถูกเพิ่ม โปรดตรวจสอบและเพิ่มข้อมูลของสินค้าก่อนการขายสินค้า</p>
      </div>
    </div>
  @endif

  @if($_modelData['active'])
    <div class="secondary-message-box success space-bottom-30">
      <div class="secondary-message-box-inner">
        <h3>สถานะการขาย: {{$_modelData['_active']}}</h3>
        <p>ลูกค้าสามารถสั่งซื้อสินค้านี้ได้แล้ว</p>
      </div>
    </div>
  @else
    <div class="secondary-message-box error space-bottom-30">
      <div class="secondary-message-box-inner">
        <h3>สถานะการขาย: {{$_modelData['_active']}}</h3>
        <p>เปิดการขายสินค้านี้โดยไปยังหน้า "สถานะสินค้า" และเลือก "เปิดการขายสินค้านี้" <a href="{{request()->get('shopUrl')}}product/status/edit/{{$_modelData['id']}}">ไปยังหน้าสถานะสินค้า</a></p>
      </div>
    </div>
  @endif

  @if($_modelData['quantity'] == 0)
  <div class="secondary-message-box error space-bottom-30">
    <div class="secondary-message-box-inner">
      <h3>สินค้าหมด</h3>
      <p>สินค้านี้จะไม่สามารถสั่งซื้อได้จนกว่าจะเพิ่มจำนวนสินค้า <a href="{{request()->get('shopUrl')}}product/stock/edit/{{$_modelData['id']}}">ไปยังหน้าปรับสินค้า</a></p>
    </div>
  </div>
  @elseif(false && $_modelData['quantity'] < 11)
  <div class="secondary-message-box warning space-bottom-30">
    <div class="secondary-message-box-inner">
      <h3>สินค้าใกล้หมด</h3>
      <p>โปรดเพิ่มจำนวนสินค้าของคุณ เพื่อให้ลูกค้าสามารถสั่งซื้อสินค้านี้ได้ <a href="{{request()->get('shopUrl')}}product/stock/edit/{{$_modelData['id']}}">ไปยังหน้าปรับสินค้า</a></p>
    </div>
  </div>
  @endif
  
  <div class="list-item-group">

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}product/status/edit/{{$_modelData['id']}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>สถานะสินค้า</h4>
      </a>
      <div class="list-item-group-info">
        <h5><b>สถานะการขาย</b>: {{$_modelData['_active']}}</h5>
      </div>
    </div>

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}product/edit/{{$_modelData['id']}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>ข้อมูลทั้วไปของสินค้า</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}product/specification/edit/{{$_modelData['id']}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>ข้อมูลจำเพาะของสินค้า</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}product/category/edit/{{$_modelData['id']}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>หมวดสินค้า</h4>
      </a>
      <div class="list-item-group-info">
        <h5><b>หมวดสินค้า</b>: {{$_modelData['_categoryPathName']}}</h5>
      </div>
    </div>

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}product/minimum/edit/{{$_modelData['id']}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>การสั่งซื้อขั้นต่ำ</h4>
      </a>
      <div class="list-item-group-info">
        <h5><b>จำนวนการซื้อขั้นต่ำ</b>: {{$_modelData['minimum']}} {{$_modelData['product_unit']}} / การสั่งซื้อ</h5>
      </div>
    </div>

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}product/stock/edit/{{$_modelData['id']}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>จำนวนสินค้า</h4>
      </a>
      <div class="list-item-group-info">
        <h5><b>จำนวนคงเหลือปัจจุบัน</b>: {{$_modelData['quantity']}} {{$_modelData['product_unit']}}</h5>
      </div>
    </div>

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}product/price/edit/{{$_modelData['id']}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>ราคาสินค้า</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}product/sale_promotion/edit/{{$_modelData['id']}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>โปรโมชั่นการขาย</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}product/shipping/edit/{{$_modelData['id']}}">
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
      <a href="{{request()->get('shopUrl')}}product/branch/edit/{{$_modelData['id']}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>สาขาที่ขายสินค้า</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}product/notification/edit/{{$_modelData['id']}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>ข้อความและการแจ้งเตือน</h4>
      </a>
    </div>

  </div>

</div>

@stop