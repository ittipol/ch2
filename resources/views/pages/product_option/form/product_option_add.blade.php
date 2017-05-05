@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}product/option/{{request()->product_id}}" class="btn btn-secondary">กลับไปหน้าตัวเลือกสินค้า</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}manage/product/{{request()->product_id}}">ไปยังหน้าจัดการสินค้า</a>
              <a href="{{request()->get('shopUrl')}}manage/product">ไปยังหน้าหลักจัดการสินค้า</a>
            </div>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="container-header">
    <div class="row">
      <div class="col-lg-6 col-sm-12">
        <div class="title">เพิ่มตัวเลือกสินค้า</div>
      </div>
    </div>
  </div>
</div>

<div class="container">

  <div class="secondary-message-box info space-bottom-30">
    <div class="secondary-message-box-inner">
      <h3>โปรดอ่านก่อนการกำหนดระยะเวลาโปรโมขั่น</h3>
      <p>*** โปรโมชั่นจะถูกใช้งานทันทีเมื่อถึงระยะที่ได้กำหนดไว้</p>
      <p>*** ไม่สามารถเพิ่มโปรโมชั่นในระยะเวลาที่ได้เคยกำหนดไว้แล้วได้</p>
      <p>*** เมื่อโปรโมชั่นถูกใช้งานจะไม่สามารถ <strong>แก้ไข</strong> หรือ <strong>ลบ</strong> โปรโมชั่นนั้นได้</p>
    </div>
  </div>

</div>

@stop