@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.top_nav')
@include('pages.shop.layouts.header')

<div class="shop-content-wrapper">

  <div class="container">

    <div class="box">

      <div class="box-header">
        <h4 class="box-header-title">
          <img class="icon-before-title" src="/images/icons/edit-blue.png">ปักหมุดข้อความ
        </h4>
      </div>

      <textarea class="pin-message-input"></textarea>

      <div class="box-footer text-right">
        <a href="" class="button">โพสต์</a>
      </div>

    </div>

    <div class="box">
      <div class="box-header">
        <h4 class="box-header-title">
          <img class="icon-before-title" src="/images/icons/edit-blue.png">สินค้า
        </h4>
      </div>
    </div>

    <a href="" class="button wide-button">แสดงสินค้าทั้งหมด</a>

  </div>

</div>

@stop