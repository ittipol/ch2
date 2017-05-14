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

  <h3 class="article-titl space-bottom-50">แฟชั่นสุภาพสตรี</h3>

  <div class="product-banner clearfix">
    <div class="section-left">
      <div class="section-left-inner">
        เสื้อผ้าผู้หญิง
      </div>
    </div>
    <div class="section-right">
      
      <div class="row">
        @foreach($shirts as $data)
        <div class="product-banner-content col-md-6 col-xs-12">
          <div class="image-tile pull-left">
            <a href="{{$data['detailUrl']}}">
              <div class="product-banner-image" style="background-image:url({{$data['_imageUrl']}});"></div>
            </a>
          </div>
          <div class="product-banner-info pull-left">
            <div class="product-title">{{$data['name']}}</div>

            <div>
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
        @endforeach
      </div>

    </div>
  </div>
  <div class="row space-bottom-50">
    <div class="col-xs-12 text-right">
      <a href="{{$moreShirtUrl}}" class="flat-button">เพิ่มเติม</a>
    </div>
  </div>

  <div class="product-banner clearfix">
    <div class="section-left">
      <div class="section-left-inner">
        ชุดเดรส
      </div>
    </div>
    <div class="section-right">
      
      <div class="row">
        @foreach($dresses as $data)
        <div class="product-banner-content col-md-6 col-xs-12">
          <div class="image-tile pull-left">
            <a href="{{$data['detailUrl']}}">
              <div class="product-banner-image" style="background-image:url({{$data['_imageUrl']}});"></div>
            </a>
          </div>
          <div class="product-banner-info pull-left">
            <div class="product-title">{{$data['name']}}</div>

            <div>
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
        @endforeach
      </div>

    </div>
  </div>
  <div class="row space-bottom-50">
    <div class="col-xs-12 text-right">
      <a href="{{$moreDressUrl}}" class="flat-button">เพิ่มเติม</a>
    </div>
  </div>

  <div class="product-banner clearfix">
    <div class="section-left">
      <div class="section-left-inner">
        กระเป๋า
      </div>
    </div>
    <div class="section-right">
      
      <div class="row">
        @foreach($bags as $data)
        <div class="product-banner-content col-md-6 col-xs-12">
          <div class="image-tile pull-left">
            <a href="{{$data['detailUrl']}}">
              <div class="product-banner-image" style="background-image:url({{$data['_imageUrl']}});"></div>
            </a>
          </div>
          <div class="product-banner-info pull-left">
            <div class="product-title">{{$data['name']}}</div>

            <div>
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
        @endforeach
      </div>

    </div>
  </div>
  <div class="row space-bottom-50">
    <div class="col-xs-12 text-right">
      <a href="{{$moreBagUrl}}" class="flat-button">เพิ่มเติม</a>
    </div>
  </div>

  <div class="product-banner clearfix">
    <div class="section-left">
      <div class="section-left-inner">
        รองเท้า
      </div>
    </div>
    <div class="section-right">
      
      <div class="row">
        @foreach($shoes as $data)
        <div class="product-banner-content col-md-6 col-xs-12">
          <div class="image-tile pull-left">
            <a href="{{$data['detailUrl']}}">
              <div class="product-banner-image" style="background-image:url({{$data['_imageUrl']}});"></div>
            </a>
          </div>
          <div class="product-banner-info pull-left">
            <div class="product-title">{{$data['name']}}</div>

            <div>
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
        @endforeach
      </div>

    </div>
  </div>
  <div class="row space-bottom-50">
    <div class="col-xs-12 text-right">
      <a href="{{$moreShoeUrl}}" class="flat-button">เพิ่มเติม</a>
    </div>
  </div>

  <div class="line space-top-bottom-30"></div>

  <h3 class="article-titl space-bottom-50">ตกแต่งที่นอนของคุณด้วยชุดเครื่องนอนสวยๆ ลดสูงสุด 30%</h3>
  <div class="product-banner clearfix">
    <div class="section-left">
      <div class="section-left-inner">
        ชุดเครื่องนอน
      </div>
    </div>
    <div class="section-right">
      
      <div class="row">
        @foreach($bedSheets as $data)
        <div class="product-banner-content col-md-6 col-xs-12">
          <div class="image-tile pull-left">
            <a href="{{$data['detailUrl']}}">
              <div class="product-banner-image" style="background-image:url({{$data['_imageUrl']}});"></div>
            </a>
          </div>
          <div class="product-banner-info pull-left">
            <div class="product-title">{{$data['name']}}</div>

            <div>
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
        @endforeach
      </div>

    </div>
  </div>
  <div class="row space-bottom-50">
    <div class="col-xs-12 text-right">
      <a href="{{$moreBedSheetUrl}}" class="flat-button">เพิ่มเติม</a>
    </div>
  </div>

  <div class="line space-top-bottom-30"></div>

  <h4 class="space-bottom-20">ร้านค้าแนะนำ</h4>
  <h3>{{$shopName}}</h3>
  @if(!empty($products))
  <div class="content-panel row">

    @foreach($products as $data)
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
      <a href="{{$shopProductUrl}}" class="flat-button">เยี่ยมชมร้านค้า</a>
    </div>
  </div>

  <div class="line space-top-bottom-100"></div>

  <a href="" class="button wide-button">ดูสินค้าทั้งหมด</a>

</div>

@stop