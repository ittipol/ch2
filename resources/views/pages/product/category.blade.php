@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <h4>สินค้า</h4>
      <h2>หมวดสินค้า</h2>
    </div>
  </div>
</div>

<div class="container">

  @if(!empty($categoryName))
    <h3>{{$categoryName}}</h3>
    <div class="line"></div>
    <div class="text-right">
      <a href="{{$productShelfUrl}}" class="button space-top-bottom-20">แสดงสินค้าทั้งหมด</a>
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
        <h4>ไม่มีหมวดสินค้าย่อยของ{{$categoryName}}</h4>
      </div>
    </div>

  @endif

</div>

@stop