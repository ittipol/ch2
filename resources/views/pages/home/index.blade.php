@extends('layouts.blackbox.main')
@section('content')

<div class="primary-banner">
  <div class="container">
    <div class="banner-overlay-message">
      <!-- <h2 class="primary-banner-title">เริ่มต้นธุรกิจของคุณและเชื่อมต่อธุรกิจของคุณกับผู้คน</h2> -->
      <h2 class="primary-banner-title">สร้างร้านค้าของคุณหรือเลือกซื้อสินค้าที่คุณต้องการ</h2>
      <p class="banner-description">สร้างธุรกิจของคุณ จัดการธุรกิจของคุณไปในทิศทางที่คุณต้องการ และให้เราทำหน้าที่เชื่อมต่อธุรกิจของคุณกับผู้คนอีกมากมาย</p>
      <a href="{{URL::to('shop/create')}}" class="button">สร้างร้านค้า</a>
      <a href="{{URL::to('product')}}" class="button">เลือกซื้อสินค้า</a>
    </div>
  </div>
</div>

<div class="container">

  <h2 class="article-title color-teal space-bottom-50">สินค้ายอดนิยมสำหรับสุภาพสตรี</h2>

  <h4 class="space-bottom-20">เสื้อผ้าและชุดเซ็ท</h4>
  @if(!empty($shirts))
  <div class="content-panel row">

    @foreach($shirts as $data)
    <div class="col-md-3 col-xs-6">
      <div class="card sm">

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

      </div>
    </div>
    @endforeach

  </div>
  @endif
  <div class="row">
    <div class="col-xs-12 text-right">
      <a href="{{$moreShirtUrl}}" class="flat-button">เพิ่มเติม</a>
    </div>
  </div>

  <h4 class="space-bottom-20">ชุดเดรส</h4>
  @if(!empty($dresses))
  <div class="content-panel row">

    @foreach($dresses as $data)
    <div class="col-md-3 col-xs-6">
      <div class="card sm">

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

      </div>
    </div>
    @endforeach
    
  </div>
  @endif
  <div class="row">
    <div class="col-xs-12 text-right">
      <a href="{{$moreDressUrl}}" class="flat-button">เพิ่มเติม</a>
    </div>
  </div>

  <h4 class="space-bottom-20">กระเป๋า</h4>
  @if(!empty($bags))
  <div class="content-panel row">

    @foreach($bags as $data)
    <div class="col-md-3 col-xs-6">
      <div class="card sm">

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

      </div>
    </div>
    @endforeach
    
  </div>
  @endif
  <div class="row">
    <div class="col-xs-12 text-right">
      <a href="{{$moreBagUrl}}" class="flat-button">เพิ่มเติม</a>
    </div>
  </div>

  <h4 class="space-bottom-20">รองเท้า</h4>
  @if(!empty($shoes))
  <div class="content-panel row">

    @foreach($shoes as $data)
    <div class="col-md-3 col-xs-6">
      <div class="card sm">

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

      </div>
    </div>
    @endforeach
    
  </div>
  @endif
  <div class="row">
    <div class="col-xs-12 text-right">
      <a href="{{$moreShoeUrl}}" class="flat-button">เพิ่มเติม</a>
    </div>
  </div>

</div>

@stop