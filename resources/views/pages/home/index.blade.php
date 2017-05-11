@extends('layouts.blackbox.main')
@section('content')

<div class="primary-banner">
  <div class="container">
    <div class="banner-overlay-message">
      <h2 class="primary-banner-title">เริ่มต้นธุรกิจของคุณและเชื่อมต่อธุรกิจของคุณกับผู้คน</h2>
      <p class="banner-description">สร้างธุรกิจของคุณ จัดการธุรกิจของคุณไปในทิศทางที่คุณต้องการ และให้เราทำหน้าที่เชื่อมต่อธุรกิจของคุณกับผู้คนอีกมากมาย</p>
      <a href="{{URL::to('shop/create')}}" class="button">เริ่มต้นธุรกิจของคุณ</a>
      <a href="{{URL::to('product')}}" class="button">เลือกซื้อสินค้า</a>
    </div>
  </div>
</div>

<div class="container">

  <h2 class="article-title color-pink space-bottom-50">บริษัทและร้านค้า</h2>

  <h3 class="article-title color-teal space-bottom-20">สร้างร้านค้าของคุณ</h3>
  <div class="article-content space-bottom-20">
    <div class="paragraph">สร้างธุรกิจของคุณ จัดการธุรกิจของคุณไปในทิศทางที่คุณต้องการ และให้เราทำหน้าที่เชื่อมต่อธุรกิจของคุณกับผู้คนอีกมากมาย</div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <a href="{{URL::to('shop/create')}}" class="button wide-button">สร้างร้านค้า</a>
    </div>
  </div>

  <div class="line only-space space-bottom-120"></div>

  <h3 class="article-title color-teal space-bottom-20">สินค้าจากร้านค้า</h3>
  <div class="article-content space-bottom-20">
    <div class="paragraph">สินค้าแต่ละรายการจะถูกจัดวางไปยังหมวดมู่สินค้าโดยผู้ขายจะเป็นผู้กำหนดหมวดหมู่ให้กับสินค้า โดยมีหมวดหมู่สินค้ามากกว่า 2500 หมวดหมู่ที่จะทำให้การเลือกซื้อสินค้าสะดวกและรวดเร็ว</div>
  </div>

  @if(!empty($products))

  <div class="content-panel row">

    @foreach($products as $data)

    <div class="col-lg-3 col-sm-6 col-xs-6">
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
            <div class="button wide-button">แสดงรายละเอียด</div>
          </a>
        
        </div>

      </div>
    </div>

    @endforeach

  </div>

  @endif

  <div class="row">
    <div class="col-xs-12 text-right">
      <a href="{{URL::to('product')}}" class="button">เลือกซื้อสินค้า</a>
    </div>
  </div>

</div>

@stop