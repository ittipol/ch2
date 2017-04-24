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

        <div class="header-info col-md-8 col-sm-12">
          <h3>{{$_modelData['name']}}</h3>
        </div>

        // banner

        // description

      </div>
      
    </div>
  </div>
</div>

<div class="container">

</div>

@stop