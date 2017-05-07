@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}manage/product_catalog" class="btn btn-secondary">กลับไปหน้ารายการแคตตาล็อกสินค้า</a>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="top-header-wrapper no-margin">
  <div class="container">
    <div class="top-header">

      <div class="row">
        <div class="header-info col-sm-12 no-margin">
          <a href="{{request()->get('shopUrl')}}product_catalog/{{$_modelData['id']}}">
            <h3>{{$_modelData['name']}}</h3>
          </a>
        </div>
      </div>

      <div class="additional-option">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="additional-option-content">
          <a href="{{request()->get('shopUrl')}}product_catalog/{{$_modelData['id']}}">แสดงแคตตาล็อกนี้</a>
          <a href="{{request()->get('shopUrl')}}product_catalog/delete/{{$_modelData['id']}}" data-modal="1" data-modal-title="ต้องการลบแคตตาล็อก {{$_modelData['name']}} ใช่หรือไม่">ลบแคตตาล็อก</a>
        </div>
      </div>
      
    </div>

    <div class="line"></div>

  </div>
</div>

<div class="container">

  <div class="list-item-group no-padding">

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}product_catalog/edit/{{$_modelData['id']}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>แก้ไขข้อมูลทั่วไป</h4>
      </a>
    </div>

    <div class="list-item">
      <a href="{{request()->get('shopUrl')}}product_catalog/product_list/edit/{{$_modelData['id']}}">
        <img class="icon" src="/images/common/pencil.png" >
        <h4>เพิ่ม/ลบสินค้าในแคตตาล็อก</h4>
      </a>
    </div>

  </div>

  <div>
    <h3>สินค้าในแคตตาล็อก</h3>

    <h2>{{$totalProduct}}</h2>
    <h5>รายการสินค้าในแคตตาล็อก</h5>

    @if(!empty($products))
      <div class="list-h">
      @foreach($products as $data)
        <div class="list-h-item list-h-sm clearfix">

          <a href="{{$data['detailUrl']}}" class="list-image pull-left">
            <img src="/images/icons/tag-white.png">
          </a>

          <div class="col-md-11 col-xs-8">
            <a href="{{$data['detailUrl']}}">
              <h4 class="primary-info single-info">{{$data['name']}}</h4>
            </a>
          </div>

          <div class="additional-option">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="additional-option-content">
              <a href="{{$data['deleteUrl']}}" data-modal="1" data-modal-title="ต้องการนำสินค้า {{$data['name']}} ออกใช่หรือไม่">นำสินค้าออก</a>
            </div>
          </div>

        </div>
      @endforeach
      </div>
    @else
      <div class="list-empty-message text-center space-top-20">
        <img src="/images/common/not-found.png">
        <div>
          <h3>ยังไม่มีสินค้าในแคตตาล็อก</h3>
          <a href="{{request()->get('shopUrl')}}product_catalog/product_list/edit/{{$_modelData['id']}}" class="button">เพิ่มสินค้าในแคตตาล็อก</a>
        </div>
      </div>
    @endif

  </div>

</div>

@stop