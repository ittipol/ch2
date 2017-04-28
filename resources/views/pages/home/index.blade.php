@extends('layouts.blackbox.main')
@section('content')

<div class="primary-banner">
  <div class="container">
    <div class="banner-overlay-message">
      <h2 class="primary-banner-title">เริ่มต้นธุรกิจของคุณและเชื่อมต่อธุรกิจของคุณกับผู้คน</h2>
      <p class="banner-description">สร้างธุรกิจของคุณ จัดการธุรกิจของคุณไปในทิศทางที่คุณต้องการ และให้เราทำหน้าที่เชื่อมต่อธุรกิจของคุณกับผู้คนอีกมากมาย</p>
      <a href="{{URL::to('community/shop/create')}}" class="button">เริ่มต้นธุรกิจของคุณ</a>
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
      <a href="{{URL::to('community/shop/create')}}" class="button wide-button">สร้างร้านค้า</a>
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

    <div class="col-sm-4 col-xs-12">
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
      <a href="{{URL::to('product/shelf')}}" class="button">เลือกซื้อสินค้า</a>
    </div>
  </div>

  <div class="line only-space space-bottom-120"></div>

  <h3 class="article-title color-teal space-bottom-20">ประกาศงาน</h3>
  <div class="article-content space-bottom-20">
    <div class="paragraph">งานที่ประกาศจะถูกแบ่งรูปแบบการจ้างงานอย่างชัดเจน รวมถึงรายละเอียดงาน คุณสมบัติผู้สมัคร และอื่นๆ และยังมีระบบสมัครงานพร้อมการแจ้งเตือนที่ผู้สมัครสามารถติดตามการสมัครงานได้ตลอดเวลา</div>
  </div>

  @if(!empty($jobs))

  <div class="content-panel row">

    @foreach($jobs as $data)

    <div class="col-sm-4 col-xs-12">
      <div class="card">
        <div class="image-tile">
          <a href="{{$data['detailUrl']}}">
            <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
          </a>
        </div>

        <div class="card-info">
          <a href="{{$data['detailUrl']}}">
            <div class="card-title">{{$data['_short_name']}}</div>
          </a>
          <div class="card-sub-info">
            <h5>อัตราค่าจ้าง</h5>
            <div class="text-emphasize">{{$data['_wage']}}</div>
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
      <a href="{{URL::to('job/board')}}" class="button">ค้นหางาน</a>
    </div>
  </div>

  <div class="line only-space space-bottom-120"></div>

  <h3 class="article-title color-teal space-bottom-20">โฆษณาจากบริษัทและร้านค้า</h3>
  <div class="article-content  space-bottom-20">
    <div class="paragraph">ติดตามข่าวสารและการโฆษณาต่างๆจากบริษัทและร้านค้า เพื่อเป็นสื่อสารสิ่งต่างๆไปยังลูกค้า ไม่ว่าจะเป็นสินค้าใหม่ การบริการใหม่ๆ หรือร้านที่เพิ่มเปิดใหม่ และอื่นๆอีกมากมาย</div>
  </div>

  @if(!empty($advertisings))

  <div class="content-panel row">

    @foreach($advertisings as $data)

    <div class="col-sm-4 col-xs-12">
      <div class="card">
        <div class="image-tile">
          <a href="{{$data['detailUrl']}}">
            <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
          </a>
        </div>

        <div class="card-info">
          <a href="{{$data['detailUrl']}}">
            <div class="card-title">{{$data['_short_name']}}</div>
          </a>
          <div class="card-sub-info">
            <div>ประเภทโฆษณา</div>
            {{$data['_advertisingType']}}
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
      <a href="{{URL::to('advertising/board')}}" class="button">แสดงโฆษณา</a>
    </div>
  </div>

  <div class="line grey space-top-bottom-120"></div>

  <h2 class="article-title color-pink space-bottom-50">ประกาศซื้อ-เช่า-ขาย</h2>

  <h3 class="article-title color-teal space-bottom-20">ประกาศซื้อ-เช่า-ขายสินค้า</h3>
  <div class="article-content space-bottom-20">
    <div class="paragraph">สินใหม่หรือเก่าที่ถูกประกาศจะถูกแบ่งหมวดหมู่และจุดประสงค์ของการประกาศ เพื่อให้การประกาศสามารถบ่งบอกจุดประสงค์ไปยังผู้ที่เข้ามาดูประกาศ</div>
  </div>

  @if(!empty($items))

  <div class="content-panel row">

    @foreach($items as $data)

    <div class="col-sm-4 col-xs-12">
      <div class="card">

        <div class="flag-wrapper">
          <div class="flag">{{$data['_announcementTypeName']}}</div>
        </div>

        <div class="image-tile">
          <a href="{{$data['detailUrl']}}">
            <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
          </a>
        </div>
        
        <div class="card-info">
          <a href="{{$data['detailUrl']}}">
            <div class="card-title">{{$data['_short_name']}}</div>
          </a>
          <div class="card-sub-info">
            <h5>ราคา{{$data['_announcementTypeName']}}</h5>
            <div class="text-emphasize">{{$data['_price']}}</div>
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
      <a href="{{URL::to('item/board')}}" class="button">แสดงประกาศสินค้า</a>
    </div>
  </div>

  <div class="line only-space space-bottom-120"></div>

  <h3 class="article-title color-teal space-bottom-20">ประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์</h3>
  <div class="article-content space-bottom-20">
    <div class="paragraph">อสังหาริมทรัพย์ที่ประกาศจะถูกแบ่งประเภทอย่างชัดเจนและจุดประสงค์ของการประกาศที่บอกว่าการประกาศนี้ต้องการซื้อ เช่า หรือขาย</div>
  </div>

  @if(!empty($realEstates))

  <div class="content-panel row">

    @foreach($realEstates as $realEstate)

    <div class="col-sm-4 col-xs-12">
      <div class="card">

        <div class="flag-wrapper">
          <div class="flag">{{$data['_announcementTypeName']}}</div>
          @if(!empty($data['need_broker']))
          <div class="flag">{{$data['_need_broker']}}</div>
          @endif
        </div>

        <div class="image-tile">
          <a href="{{$data['detailUrl']}}">
            <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
          </a>
        </div>

        <div class="card-info">
          <a href="{{$data['detailUrl']}}">
            <div class="card-title">{{$data['_short_name']}}</div>
          </a>
          <div class="card-sub-info">
            <h5>ราคา{{$data['_announcementTypeName']}}</h5>
            <div class="text-emphasize">{{$data['_price']}}</div>
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
      <a href="{{URL::to('real-estate/board')}}" class="button">แสดงประกาศอสังหาริมทรัพย์</a>
    </div>
  </div>

</div>

@stop