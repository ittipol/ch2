@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">
        <div class="btn-group pull-right">
          <a href="{{URL::to('product/category')}}" class="btn btn-secondary">หมวดสินค้า</a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">สินค้าจากบริษัทและร้านค้า</h2>
      </div>
    </div>
  </div>
</div>

<div class="container">

  <div class="search sm space-bottom-50">

    <?php 
      echo Form::open(['url' => 'search', 'id' => 'search_form','method' => 'get', 'enctype' => 'multipart/form-data']);
    ?>

    <div class="search-box-panel">
      <input type="text" name="search_query" placeholder="ค้นหาสินค้า..." autocomplete="off" class="search-box">
      <button class="button-search">
        <i class="fa fa-search"></i>
      </button>
    </div>

    <input type="hidden" name="fq" value="model:Product">
    <input type="hidden" name="sort" value="name:asc">

    <?php
      echo Form::close();
    ?>

  </div>

  @foreach($shelfs as $shelf)
    <div class="shelf">

      <h3>{{$shelf['categoryName']}}</h3>

      <h2>{{$shelf['total']}}</h2>
      <h5>รายการสินค้า</h5>

      <div class="row">

        <div class="col-xs-12">

          @if(!empty($shelf['products']))
          <div class="row">

            @foreach($shelf['products']['items'] as $product)
              <div class="col-md-3 col-xs-6">

                <div class="card sm">

                  @if(!empty($product['flag']))
                  <div class="flag-wrapper">
                    <div class="flag sale-promotion">{{$product['flag']}}</div>
                  </div>
                  @endif
                  
                  <div class="image-tile">
                    <a href="{{$product['detailUrl']}}">
                      <div class="card-image" style="background-image:url({{$product['_imageUrl']}});"></div>
                    </a>
                  </div>
                  
                  <div class="card-info">
                    <a href="{{$product['detailUrl']}}">
                      <div class="card-title">{{$product['name']}}</div>
                    </a>
                    <div class="card-sub-info">

                      <div class="card-sub-info-row product-price-section">
                        @if(!empty($product['promotion']))
                          <span class="product-price">{{$product['promotion']['_reduced_price']}}</span>
                          <span class="product-price-discount-tag">{{$product['promotion']['percentDiscount']}}</span>
                          <h5 class="origin-price">{{$product['_price']}}</h5>
                        @else
                          <span class="product-price">{{$product['_price']}}</span>
                        @endif
                      </div>

                    </div>
                  </div>

                </div>

              </div>
            @endforeach

            @if(!empty($shelf['products']['all']))
              <div class="col-xs-12">
                <a href="{{$shelf['productShelfUrl']}}" class="product-all-tile">
                  <span>
                    แสดงสินค้าทั้งหมด<br>
                    {{$shelf['products']['all']['title']}}
                  </span>
                </a>
              </div>
            @endif

          </div>

          @else

            <div class="list-empty-message text-center space-top-20">
              <div>
                <h4>ยังไม่มีสินค้าหมวด{{$shelf['categoryName']}}</h4>
              </div>
            </div>
          
          @endif

        </div>

        <div class="col-xs-12">
          <a href="{{$shelf['categoryUrl']}}" class="bottom-wide-button space-top-20">แสดงหมวดสินค้าที่เกี่ยวข้อง</a>
        </div>

      </div>
    </div>

    <div class="line space-top-bottom-20"></div>
  @endforeach

</div>

@stop