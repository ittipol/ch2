@extends('layouts.blackbox.main')
@section('content')

  @include('pages.product.layouts.fixed_top_nav')

  <div class="container">

    <div class="container-header">
      <div class="row">
        <div class="col-lg-6 col-sm-12">
          <div class="title">
            หัวข้อคุณลักษณะสินค้า
          </div>
        </div>
      </div>

    </div>

    <div class="list-empty-message text-center space-top-20">
      <img src="/images/common/not-found.png">
      <div>
        <h3>หัวข้อคุณลักษณะสินค้า</h3>
        <p>ยังไม่มีหัวข้อคุณลักษณะสินค้า ต้องระบุหัวข้อของตัวเลือกก่อนการเพิ่มตัวเลือกสินค้า</p>
        <a href="{{$productOptionAdd}}" class="button">เพิ่มหัวข้อคุณลักษณะสินค้า</a>
      </div>
    </div>
    
  </div>

@stop