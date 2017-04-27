@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{URL::to('product/shelf')}}" class="btn btn-secondary">กลับไปหน้าหลักสินค้า</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{URL::to('product/category')}}">ไปยังหน้าแสดงหมวดสินค้า</a>
            </div>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="container list space-top-30">

  <h4 class="no-margin">แคตตาล็อกสินค้า</h4>
  <h3>{{$productCatalog['name']}}</h3>

  @if(!empty($imageUrl))
    <div class="product-catalog-banner" style="background-image:url({{$imageUrl}});"></div>
  @else
    <div class="line"></div>
  @endif

  <div class="text-right space-top-bottom-20">
    @if(!empty($productCatalog['description']))
    <a class="button" data-right-side-panel="1" data-right-side-panel-target="#catalog_description">คำอธิบายแคตตาล็อก</a>
    @endif
    <a class="button" data-right-side-panel="1" data-right-side-panel-target="#filter_expand_panel">ตัวกรอง</a>
  </div>

  @if(!empty($productCatalog['description']))
  <div id="catalog_description" class="right-size-panel description">
    <div class="right-size-panel-inner">
        <h4>คำอธิบายแคตตาล็อก</h4>
        <div class="line space-bottom-10"></div>
        <div class="right-size-panel-description">
          {!!$productCatalog['description']!!}
        </div>
      <div class="right-size-panel-close-button"></div>
    </div>
  </div>
  @endif

  <div id="filter_expand_panel" class="right-size-panel filter">
    <div class="right-size-panel-inner">
      @include('components.filter_expand_panel')
      <div class="right-size-panel-close-button"></div>
    </div>
  </div>

  @if(!empty($_pagination['data']))

    <div class="row">

      @foreach($_pagination['data'] as $data)

      <div class="col-lg-3 col-sm-4 col-xs-12">
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
      <h3>ยังไม่มีสินค้าในแคตตาล็อก</h3>
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