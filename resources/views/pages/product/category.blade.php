@extends('layouts.blackbox.main')
@section('content')


<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">
        @if(!empty($categoryName))
        <div class="btn-group pull-right">
          <a href="{{$parentCategoryUrl}}" class="btn btn-secondary">กลับไปหน้าประเภทสินค้าหลักของหมวดนี้</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{URL::to('product/category')}}" class="btn btn-secondary">ไปยังหน้าภาพรวมประเภทสินค้า</a>
            </div>
          </button>
        </div>
        @else
        <div class="btn-group pull-right">
          <a href="{{URL::to('product/shelf')}}" class="btn btn-secondary">สินค้าจากร้านค้า</a>
        </div>
        @endif

      </div>
    </div>
  </div>
</div>


<!-- <div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <h5>หมวดสินค้า</h5>
      <h2>{{$categoryName}}</h2>
    </div>
  </div>
</div> -->

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        @if(!empty($categoryName))
        <h4 class="sub-title">หมวดสินค้า</h4>
        <h2 class="title">{{$categoryName}}</h2>
        @else
        <h2 class="title">หมวดสินค้า</h2>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="container">

  @if(!empty($categoryName))
    <div class="text-right">
      <a href="{{$productShelfUrl}}" class="button">แสดงสินค้าทั้งหมด</a>
    </div>
  @endif

  @if(!empty($categories))

    @foreach($categories as $category)
      <div class="shelf">

        <h3>{{$category['categoryName']}}</h3>
        <div class="space-bottom-10">
          <a href="{{$category['productShelfUrl']}}">แสดงสินค้าหมวด{{$category['categoryName']}}</a>
        </div>

        @if(!empty($category['subCategories']))
        <div class="row">

          <div class="col-xs-12">
            <div class="sub-category">
              <div class="row">
                @foreach($category['subCategories'] as $subCategory)
                  <div class="col-lg-4 col-sm-6 col-xs-12">
                    <a href="{{$subCategory['url']}}" class="sub-category-text">{{$subCategory['name']}}</a>
                  </div>
                @endforeach
              </div>
            </div>
          </div>

        </div>
        @endif

      </div>

      <div class="line space-top-bottom-20"></div>
    @endforeach

  @else

    <div class="list-empty-message text-center space-top-20">
      <div>
        <h4>ไม่มีหมวดสินค้าย่อย</h4>
      </div>
    </div>

  @endif

</div>

@stop