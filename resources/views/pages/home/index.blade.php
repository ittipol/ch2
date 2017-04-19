@extends('layouts.blackbox.main')
@section('content')

<div class="banner">
  <div class="container">
    <div class="banner-overlay-message">
      <h2 class="banner-primary-title">เริ่มต้นธุรกิจของคุณและเชื่อมต่อธุรกิจของคุณกับผู้คน</h2>
      <p class="banner-description">สร้างธุรกิจของคุณ จัดการธุรกิจของคุณไปในทิศทางที่คุณต้องการ และให้เราทำหน้าที่เชื่อมต่อธุรกิจของคุณกับผู้คนอีกมากมาย</p>
      <a href="{{URL::to('community/shop/create')}}" class="button">เริ่มต้นธุรกิจของคุณ</a>
    </div>
  </div>
</div>

<div class="container">

  <h3 class="article-title color-teal">คุณสมบัติที่น่าสนใจ</h3>
  <div class="line only-space space-bottom-20"></div>

  <div class="notice-box-wrapper">

    <div class="row">

      <div class="col-sm-6 col-xs-12">
        <div class="notice-box large">

          <div class="notice-box-image">
            <div class="image" style="background-image:url(/images/banners/WP_20161129_003.jpg)"></div>
          </div>

          <div class="notice-box-info">
            <h2>สร้างธุรกิจในแบบที่คุณต้องการ</h2>
            <div class="notice-box-description">
              สร้างธุรกิจของคุณ เพิ่มช่องทางการขายสินค้า ค้นหาพนักงานให้กับธุรกิจของคุณ รวมถึงการโฆษณาแบรนด์ ธุรกิจ หรืองานบริการของคุณ
            </div>
          </div>

          <a href="#" class="button float-button">
            เพิ่มร้านสินค้า
          </a>

        </div>
      </div>

      <div class="col-sm-6 col-xs-12">
        <div class="notice-box large">

          <div class="notice-box-image text-center">
            <div class="image" style="background-image:url(/images/banners/besiness.jpg)"></div>
          </div>

          <div class="notice-box-info">
            <h2>เลือกซื้อสินค้าจากร้านค้า</h2>
            <div class="notice-box-description">
              สินค้าต่างๆจากร้านค้าที่เปิดให้คุณเลือกซื้อได้ทุกเวลาที่ต้องการ
            </div>
          </div>

          <a href="#" class="button float-button">
            ไปยังหน้าสินค้า
          </a>

        </div>
      </div>

      <div class="col-sm-6 col-xs-12">
        <div class="notice-box large">

          <div class="notice-box-image text-center">
            <div class="image" style="background-image:url(/images/a1.jpg)"></div>
          </div>

          <div class="notice-box-info">
            <h2>ค้าหาตำแหน่งงานที่ต้องการ</h2>
            <div class="notice-box-description">
              ตำแหน่งงานมากมายจากบริษัทที่หลากหลายให้ผู้ที่กำลังค้าหางานได้มีโอกาสเลือกตำแหน่งงานที่ต้องการหรือเหมาะสมมากที่สุด
            </div>
          </div>

          <a href="#" class="button float-button">
            ไปยังหน้าประกาศงาน
          </a>

        </div>
      </div>

      <div class="col-sm-6 col-xs-12">
        <div class="notice-box large">

          <div class="notice-box-image text-center">
            <div class="image" style="background-image:url(/images/banners/WP_20161129_004.jpg)"></div>
          </div>

          <div class="notice-box-info">
            <h2>โฆษณาจากบริษัทและร้านค้า</h2>
            <div class="notice-box-description">
              ติดตามข่าวสารและการโฆษณาต่างๆจากบริษัทและร้านค้า ไม่ว่าจะเป็นสินค้า การบริการใหม่ๆ หรือร้านที่เปิดใหม่ และอื่นๆอีกมากมาย
            </div>
          </div>

          <a href="#" class="button float-button">
            ไปยังหน้าโฆษณาจากบริษัทและร้านค้า
          </a>

        </div>
      </div>

      <div class="col-sm-6 col-xs-12">
        <div class="notice-box large">

          <div class="notice-box-image text-center">
            <div class="image" style="background-image:url(/images/a2.jpg)"></div>
          </div>

          <div class="notice-box-info">
            <h2>ประกาศซื้อ-เช่า-ขายสินค้า</h2>
            <div class="notice-box-description">
              ไม่ว่าสินค้านั้นจะเป็นสินใหม่หรือเก่า และไม่ได้ใช้สินค้านั้นแล้วสามารถประกาศขายได้จากที่นี้ รวมถึงต้องการซื้อหรือเช่า
            </div>
          </div>

          <a href="#" class="button float-button">
            ไปยังหน้าประกาศซื้อ-เช่า-ขายสินค้า
          </a>

        </div>
      </div>

      <div class="col-sm-6 col-xs-12">
        <div class="notice-box large">

          <div class="notice-box-image text-center">
            <div class="image" style="background-image:url(/images/banners/WP_20161129_004.jpg)"></div>
          </div>

          <div class="notice-box-info">
            <h2>ประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์</h2>
            <div class="notice-box-description">
              อสังหาริมทรัพย์ที่ต้องการขาย หรือ ต้องการให้เช่า หรือ ต้องการประกาศซื้อ สามารถประกาศได้จากที่นี้
            </div>
          </div>

          <a href="#" class="button float-button">
            ไปยังหน้าประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์
          </a>

        </div>
      </div>

      <div class="col-sm-6 col-xs-12">
        <div class="notice-box large">

          <div class="notice-box-image text-center">
            <div class="image" style="background-image:url(/images/a3.jpg)"></div>
          </div>

          <div class="notice-box-info">
            <h2>เพิ่มโอกาสได้งานใหม่</h2>
            <div class="notice-box-description">
              เพิ่มประวัติการทำงานและทักษะ เพื่อให้บุลคลที่ต้องการหาพนักงานใหม่ๆ สามารถค้นหาประวัติการทำงานของคุณได้ เพื่อเพิ่มโอกาสการทำงานของคุณ
            </div>
          </div>

          <a href="#" class="button float-button">
            ไปยังหน้าเพิ่มประวัติการทำงาน
          </a>

        </div>
      </div>

      <div class="col-sm-6 col-xs-12">
        <div class="notice-box large">

          <div class="notice-box-image text-center">
            <div class="image" style="background-image:url(/images/banners/WP_20161129_004.jpg)"></div>
          </div>

          <div class="notice-box-info">
            <h2>งานฟรีแลนซ์</h2>
            <div class="notice-box-description">
              หากคุณคือฟรีแลนซ์และต้องการประกาศให้ผู้คนทราบถึงงานฟรีแลนซ์ของคุณ สามารถประกาศได้จจากที่นี้
            </div>
          </div>

          <a href="#" class="button float-button">
            ไปยังหน้างานฟรีแลนซ์
          </a>

        </div>
      </div>

    </div>

  </div>

  <div class="line grey space-top-bottom-120"></div>

  <h2 class="article-title color-pink">บริษัทและร้านค้า</h2>
  <div class="line only-space space-bottom-50"></div>

  <h3 class="article-title color-teal">สินค้าจากร้านค้า</h3>
  <div class="line only-space space-bottom-20"></div>

  <div class="article-content">
    <div class="paragraph">สินค้าแต่ละรายการจะถูกจัดวางไปยังหมวดมู่สินค้าโดยผู้ขายจะเป็นผู้กำหนดหมวดหมู่ให้กับสินค้า โดยมีหมวดหมู่สินค้ากว่า 2500 หมวดหมู่ ซึ่งจะทำให้สินค้าถูกจัดวางลงไปบนชั้นวางสินค้าอย่างเป็นระบบ และจะทำให้การค้นหาเลือกซื้อสินค้าเป็นเรื่องง่าย</div>
  </div>

  <div class="line only-space space-bottom-20"></div>

  <div class="notice-box-wrapper">

    <div class="row">

      <div class="col-xs-12">
        <div class="notice-box small">

          <div class="notice-box-image">
            <div class="image" style="background-image:url(/images/store.jpg)"></div>
          </div>

          <a href="#" class="button float-button">
            เลือกซื้อสินค้า
          </a>

        </div>
      </div>

    </div>

  </div>

  <div class="line only-space space-bottom-40"></div>

  <div class="content-panel row">

    <div class="col-md-12">
      <div class="content-section">

        <div class="row">

          @if(!empty($products))

            @foreach($products as $product)

            <div class="col-md-4 col-xs-12">
              <div class="info-box">
                <a href="{{$product['detailUrl']}}">
                  <div class="primary-image-tile" style="background-image:url({{$product['_imageUrl']}});"></div>
                </a>
                <div class="primary-info-section">
                  <a href="{{$product['detailUrl']}}">
                    <h4 class="title">{{$product['_short_name']}}</h4>
                  </a>
                  <div class="price">{{$product['_price']}}</div>
                </div>
              </div>
            </div>

            @endforeach

          @endif

        </div>

      </div>
    </div>

  </div>

  <div class="line only-space space-bottom-120"></div>

  <h3 class="article-title color-teal">ประกาศงาน</h3>
  <div class="line only-space space-bottom-20"></div>

  <div class="article-content">
    <div class="paragraph">งานที่ประกาศจะถูกแบ่งรูปแบบการจ้างงานอย่างชัดเจน รวมถึงรายละเอียดงาน คุณสมบัติผู้สมัคร และอื่นๆ และยังมีระบบสมัครงานพร้อมการแจ้งเตือนที่ผู้สมัครสามารถติดตามการสมัครงานได้ตลอดเวลา</div>
  </div>

  <div class="line only-space space-bottom-20"></div>

  <div class="notice-box-wrapper">

    <div class="row">

      <div class="col-xs-12">
        <div class="notice-box small">

          <div class="notice-box-image">
            <div class="image" style="background-image:url(/images/banners/WP_20161129_004.jpg)"></div>
          </div>

          <a href="#" class="button float-button">
            ค้นหางาน
          </a>

        </div>
      </div>

    </div>

  </div>

  <div class="line only-space space-bottom-40"></div>

  <div class="content-panel row">

    <div class="col-md-12">

      <div class="content-section">

        <div class="row">

          @if(!empty($jobs))

            @foreach($jobs as $job)

            <div class="col-md-4 col-xs-12">
              <div class="info-box">
                <a href="{{$job['detailUrl']}}">
                  <div class="primary-image-tile" style="background-image:url({{$job['_imageUrl']}});"></div>
                </a>
                <div class="primary-info-section">
                  <a href="{{$job['detailUrl']}}">
                    <h4 class="title">{{$job['name']}}</h4>
                  </a>
                </div>
              </div>
            </div>

            @endforeach

          @endif

        </div>

      </div>
    </div>

  </div>

  <div class="line only-space space-bottom-120"></div>

  <h3 class="article-title color-teal">โฆษณาจากบริษัทและร้านค้า</h3>
  <div class="line only-space space-bottom-20"></div>

  <div class="article-content">
    <div class="paragraph">ติดตามข่าวสารและการโฆษณาต่างๆจากบริษัทและร้านค้า เพื่อเป็นสื่อสารสิ่งต่างๆไปยังลูกค้า ไม่ว่าจะเป็นสินค้าใหม่ การบริการใหม่ๆ หรือร้านที่เพิ่มเปิดใหม่ และอื่นๆอีกมากมาย</div>
  </div>

  <div class="line only-space space-bottom-20"></div>

  <div class="notice-box-wrapper">

    <div class="row">

      <div class="col-xs-12">
        <div class="notice-box small">

          <div class="notice-box-image">
            <div class="image" style="background-image:url(/images/banners/WP_20161129_004.jpg)"></div>
          </div>

          <a href="#" class="button float-button">
            แสดงโฆษณา
          </a>

        </div>
      </div>

    </div>

  </div>

  <div class="line only-space space-bottom-40"></div>

  <div class="content-panel row">

    <div class="col-md-12">
      <div class="content-section">

        <div class="row">

          @if(!empty($advertisings))

            @foreach($advertisings as $advertising)

            <div class="col-md-4 col-xs-12">
              <div class="info-box">
                <a href="{{$advertising['detailUrl']}}">
                  <div class="primary-image-tile" style="background-image:url({{$advertising['_imageUrl']}});"></div>
                </a>
                <div class="primary-info-section">
                  <a href="{{$advertising['detailUrl']}}">
                    <h4 class="title">{{$advertising['name']}}</h4>
                  </a>
                </div>
              </div>
            </div>

            @endforeach

          @endif

        </div>

      </div>
    </div>

  </div>

  <div class="line grey space-top-bottom-120"></div>

  <h2 class="article-title color-pink">ประกาศซื้อ-เช่า-ขาย</h2>
  <div class="line only-space space-bottom-50"></div>

  <h3 class="article-title color-teal">ประกาศซื้อ-เช่า-ขายสินค้า</h3>
  <div class="line only-space space-bottom-20"></div>

  <div class="article-content">
    <div class="paragraph">สินใหม่หรือเก่าที่ถูกประกาศจะถูกแบ่งหมวดหมู่และจุดประสงค์ของการประกาศ เพื่อให้การประกาศสามารถบ่งบอกจุดประสงค์ไปยังผู้ที่เข้ามาดูประกาศ</div>
  </div>

  <div class="line only-space space-bottom-20"></div>

  <div class="notice-box-wrapper">

    <div class="row">

      <div class="col-xs-12">
        <div class="notice-box small">

          <div class="notice-box-image">
            <div class="image" style="background-image:url(/images/banners/WP_20161129_004.jpg)"></div>
          </div>

          <a href="#" class="button float-button">
            เลือกดูประกาศสินค้า
          </a>

        </div>
      </div>

    </div>

  </div>

  <div class="line only-space space-bottom-40"></div>

  <div class="content-panel row">

    <div class="col-md-12">
      <div class="content-section">

        <div class="row">

          @if(!empty($items))

            @foreach($items as $item)

            <div class="col-md-4 col-xs-12">
              <div class="info-box">
                <a href="{{$item['detailUrl']}}">
                  <div class="primary-image-tile" style="background-image:url({{$item['_imageUrl']}});"></div>
                </a>
                <div class="primary-info-section">
                  <a href="{{$item['detailUrl']}}">
                    <h4 class="title">{{$item['name']}}</h4>
                  </a>
                  <div class="price">{{$item['_price']}}</div>
                </div>
              </div>
            </div>

            @endforeach

          @endif

        </div>

      </div>
    </div>

  </div>

  <div class="line only-space space-bottom-120"></div>

  <h3 class="article-title color-teal">ประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์</h3>
  <div class="line only-space space-bottom-20"></div>

  <div class="article-content">
    <div class="paragraph">อสังหาริมทรัพย์ที่ประกาศจะถูกแบ่งประเภทอย่างชัดเจนและจุดประสงค์ของการประกาศที่บอกว่าการประกาศนี้ต้องการซื้อ เช่า หรือขาย</div>
  </div>

  <div class="line only-space space-bottom-20"></div>

  <div class="notice-box-wrapper">

    <div class="row">

      <div class="col-xs-12">
        <div class="notice-box small">

          <div class="notice-box-image">
            <div class="image" style="background-image:url(/images/banners/WP_20161129_004.jpg)"></div>
          </div>

          <a href="#" class="button float-button">
            เลือกดูประกาศอสังหาริมทรัพย์
          </a>

        </div>
      </div>

    </div>

  </div>

  <div class="line only-space space-bottom-40"></div>

  <div class="content-panel row">

    <div class="col-md-12">
      <div class="content-section">

        <div class="row">

          @if(!empty($realEstates))

            @foreach($realEstates as $realEstate)

            <div class="col-md-4 col-xs-12">
              <div class="info-box">
                <a href="{{$realEstate['detailUrl']}}">
                  <div class="primary-image-tile" style="background-image:url({{$realEstate['_imageUrl']}});"></div>
                </a>
                <div class="primary-info-section">
                  <a href="{{$realEstate['detailUrl']}}">
                    <h4 class="title">{{$realEstate['_short_name']}}</h4>
                  </a>
                  <div class="price">{{$realEstate['_price']}}</div>
                  <div class="secondary-info-section">
                    <h5>ประเภท</h5>
                    {{$realEstate['_realEstateTypeName']}}
                  </div>
                </div>
              </div>
            </div>

            @endforeach

          @endif

        </div>

      </div>
    </div>

  </div>

</div>

@stop