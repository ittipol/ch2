@extends('layouts.blackbox.main')
@section('content')

<div class="primary-banner">
  <div class="container">
    <div class="banner-overlay-message">
      <h2 class="primary-banner-title">สร้างร้านค้าของคุณหรือเลือกซื้อสินค้าที่คุณต้องการ</h2>
      <p class="banner-description">เว็บไซต์ที่คุณสามารถสร้างธุรกิจของคุณได้ฟรีบนโลกออนไลน์ โดยไม่มีค่าใช้จ่าย
และเรายังทำการตลาด เพื่อให้ธุรกิจของคุณเชื่อมต่อไปยังคนนับล้านบนอินเตอร์เน็ต</p>
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
        เสื้อผ้าผู้หญิง ลดสูงสุด 21%
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
        ชุดเดรส ลดสูงสุด 22%
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
        กระเป๋า ลดสูงสุด 25%
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
        รองเท้า ลดสูงสุด 17%
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

  <h3 class="article-titl space-bottom-20">ตกแต่งที่นอนของคุณด้วยชุดเครื่องนอนสวยๆ ลดสูงสุด 20%</h3>
  <div class="content-panel row">

    @foreach($bedSheets as $data)
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
  <div class="row space-bottom-50">
    <div class="col-xs-12 text-right">
      <a href="{{$moreBedSheetUrl}}" class="flat-button">เพิ่มเติม</a>
    </div>
  </div>

  <div class="line space-top-bottom-30"></div>







  <h3 class="space-bottom-20">ร้านค้าแนะนำ</h3>



  <h3><img class="icon" src="/images/icons/building-header.png">{{$shopName3}}</h3>
  <p>อุปกรณ์เสริมเครื่องเกม ราคาถูกสุดๆ ลดกระหน่ำยิ่งกว่าล้างสต็อก รับประกันสินค้าคุณภาพ ที่นี้ที่เดียวที่ HappyPlay</p>
  <div class="space-bottom-10">
    <a href="{{$shopUrl3}}" class="flat-button">เยี่ยมชมร้านค้า</a>
  </div>
  @if(!empty($products3))
  <div class="content-panel row">

    @foreach($products3 as $data)
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
      <a href="{{$shopProductUrl3}}" class="flat-button">เพิ่มเติม</a>
    </div>
  </div>



  <h3><img class="icon" src="/images/icons/building-header.png">{{$shopName2}}</h3>
  <p>รองเท้าดีไซน์เก๋ๆ คุณภาพดี ลดราคา 100 บาททั้งร้าน</p>
  <div class="space-bottom-10">
    <a href="{{$shopProductUrl2}}" class="flat-button">เยี่ยมชมร้านค้า</a>
  </div>
  @if(!empty($products2))
  <div class="content-panel row">

    @foreach($products2 as $data)
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
      <a href="{{$shopProductUrl2}}" class="flat-button">เพิ่มเติม</a>
    </div>
  </div>





  <h3><img class="icon" src="/images/icons/building-header.png">{{$shopName1}}</h3>
  <p>ขนมเปี้ยะอร่อยๆ ราคาถูก ส่งฟรีในชลบุรี</p>
  <div class="space-bottom-10">
    <a href="{{$shopProductUrl1}}" class="flat-button">เยี่ยมชมร้านค้า</a>
  </div>
  @if(!empty($products1))
  <div class="content-panel row">

    @foreach($products1 as $data)
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
      <a href="{{$shopProductUrl1}}" class="flat-button">เพิ่มเติม</a>
    </div>
  </div>

</div>

@stop