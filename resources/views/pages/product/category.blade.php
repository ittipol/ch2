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
          <a href="{{URL::to('product')}}" class="btn btn-secondary">สินค้าจากบริษัทและร้านค้า</a>
        </div>
        @endif

      </div>
    </div>
  </div>
</div>

<div class="container space-top-30">

  <div class="space-bottom-50">
    @if(!empty($categoryName))
    <h3>{{$categoryName}}</h3>
    @else
    <h3>หมวดสินค้า</h3>
    @endif

    @if(!empty($categoryName))
      <a href="{{$productShelfUrl}}">ดูสินค้าทั้งหมด</a>
    @endif
  </div>

  @if(!empty($categories))

    @foreach($categories as $category)
      <div class="shelf space-bottom-50">

        <h4><strong>{{$category['categoryName']}}</strong></h4>
        <div class="space-bottom-10">
          <a href="{{$category['productShelfUrl']}}">ดูสินค้าหมวดนี้</a>
        </div>

        @if(!empty($category['subCategories']))
        <div class="row">

          <div class="col-xs-12">
            <div class="sub-category">
              <div class="row">
                @foreach($category['subCategories'] as $subCategory)
                  <div class="col-lg-4 col-sm-6 col-xs-6">
                    <a href="{{$subCategory['url']}}" class="sub-category-text">{{$subCategory['name']}}</a>
                  </div>
                @endforeach
              </div>
            </div>
          </div>

        </div>
        @endif

      </div>

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