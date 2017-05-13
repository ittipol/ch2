@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{URL::to('product')}}" class="btn btn-secondary">กลับไปหน้าภาพรวมสินค้า</a>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="container list space-top-30">

  @if(!empty($categoryPaths))
  <ol class="breadcrumb">
    @foreach($categoryPaths as $path)
    <li class="breadcrumb-item">
      <a href="{{$path['url']}}">{{$path['name']}}</a>
    </li>
    @endforeach
  </ol>
  @endif

  <h3>{{$title}}</h3>
  <div class="line"></div>
  <div class="text-right space-top-bottom-20">
    <a class="button" data-right-side-panel="1" data-right-side-panel-target="#category_expand_panel">หมวดสินค้า</a>
    @if(!empty($_pagination['data']))
    <a class="button" data-right-side-panel="1" data-right-side-panel-target="#filter_expand_panel">ตัวกรอง</a>
    @endif
  </div>

  <div id="category_expand_panel" class="right-size-panel category">

    <div class="right-size-panel-inner">

      <h5>หมวดสินค้า</h5>
      @if(!empty($parentCategoryName))
      <h4 class="category-name">{{$parentCategoryName}}</h4>
      @else
      <h4 class="category-name">{{$title}}</h4>
      @endif
      <div class="line space-bottom-10"></div>

      @foreach($categories as $category)
        <div class="category-item">
          <a href="{{$category['url']}}">
            {{$category['name']}}
          </a>
          <span class="category-product-total">({{$category['total']}})</span>
        </div>
        @if(!empty($category['subCategories']))
          <div class="sub-category">
          @foreach($category['subCategories'] as $subCategories)
            <div class="sub-category-item">
              <a href="{{$subCategories['url']}}">
                {{$subCategories['name']}}
              </a>
              <span class="category-product-total">({{$subCategories['total']}})</span>
            </div>
            
          @endforeach
          </div>
        @endif
      @endforeach
      <div class="right-size-panel-close-button"></div>
    </div>
  </div>

  @if(!empty($_pagination['data']))

    <div id="filter_expand_panel" class="right-size-panel filter">
      <div class="right-size-panel-inner">
        @include('components.filter_expand_panel')
        <div class="right-size-panel-close-button"></div>
      </div>
    </div>

    <div class="row">

      @foreach($_pagination['data'] as $data)

      <div class="col-lg-4 col-xs-6">
        <div class="card">

          @if(!empty($data['flag']))
          <div class="flag-wrapper">
            <div class="flag sale-promotion">{{$data['flag']}}</div>
          </div>
          @endif
          
          <div class="image-tile">
            <a href="{{$data['detailUrl']}}">
              <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
            </a>
          </div>
          
          <div class="card-info">
            <a href="{{$data['detailUrl']}}">
              <div class="card-title">{{$data['name']}}</div>
            </a>
            <div class="card-sub-info">

              <div class="card-sub-info-row product-price-section">
                @if(!empty($data['promotion']))
                  <span class="product-price">{{$data['promotion']['_reduced_price']}}</span>
                  <span class="product-price-discount-tag">{{$data['promotion']['percentDiscount']}}</span>
                  <h5 class="origin-price">{{$data['_price']}}</h5>
                @else
                  <span class="product-price">{{$data['_price']}}</span>
                @endif
              </div>

            </div>
          </div>

          <div class="button-group">

            <a href="{{$data['detailUrl']}}">
              <div class="button wide-button">รายละเอียดสินค้า</div>
            </a>
          
          </div>

        </div>
      </div>

      @endforeach

    </div>

    @include('components.pagination') 

  @else

  <div class="list-empty-message text-center space-top-20">
    <img class="space-bottom-20" src="/images/common/not-found.png">
    <div>
      <h3>ยังไม่มีสินค้า</h3>
    </div>
  </div>

  @endif

</div>

<script type="text/javascript">

  $(document).ready(function(){

    const filter = new Filter(true);
    filter.load();

  });

</script>

@stop