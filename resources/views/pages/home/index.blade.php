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

  <div>
    <h3>มีอะไรบ้างที่น่าสนใจ</h3>
    <!-- <div class="line"></div> -->
  </div>

  <div class="notice-box-wrapper">

    <div class="row">

      <div class="col-md-6 col-xs-12">
        <div class="notice-box">

          <div class="notice-box-image text-center">
            <img src="/images/banners/WP_20161129_004.jpg">
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

      <div class="col-md-6 col-xs-12">
        <div class="notice-box">

          <div class="notice-box-image text-center">
            <img src="/images/banners/WP_20161129_004.jpg">
          </div>

          <div class="notice-box-info">
            <h2>สินค้าจากร้านค้า</h2>
            <div class="notice-box-description">
              สินค้าต่างๆจากร้านค้าที่เปิดให้คุณเลือกซื้อได้ทุกเวลาที่ต้องการ
            </div>
          </div>

          <a href="#" class="button float-button">
            ไปยังหน้าสินค้า
          </a>

        </div>
      </div>

      <div class="col-md-6 col-xs-12">
        <div class="notice-box">

          <div class="notice-box-image text-center">
            <img src="/images/banners/WP_20161129_004.jpg">
          </div>

          <div class="notice-box-info">
            <h2>ค้าหาตำแหน่งงานที่ต้องการ</h2>
            <div class="notice-box-description">
              ตำแหน่งงานมากมายจากบริษัทที่หลากหลายให้ผู้ที่กำลังค้าหางานได้มีโอกาสเลือกตำแหน่งงานที่ต้องการมากที่สุด
            </div>
          </div>

          <a href="#" class="button float-button">
            ไปยังหน้าประกาศงาน
          </a>

        </div>
      </div>

      <div class="col-md-6 col-xs-12">
        <div class="notice-box">

          <div class="notice-box-image text-center">
            <img src="/images/banners/WP_20161129_004.jpg">
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

      <div class="col-md-6 col-xs-12">
        <div class="notice-box">

          <div class="notice-box-image text-center">
            <img src="/images/banners/WP_20161129_004.jpg">
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

      <div class="col-md-6 col-xs-12">
        <div class="notice-box">

          <div class="notice-box-image text-center">
            <img src="/images/banners/WP_20161129_004.jpg">
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

      <div class="col-md-6 col-xs-12">
        <div class="notice-box">

          <div class="notice-box-image text-center">
            <img src="/images/banners/WP_20161129_004.jpg">
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

      <div class="col-md-6 col-xs-12">
        <div class="notice-box">

          <div class="notice-box-image text-center">
            <img src="/images/banners/WP_20161129_004.jpg">
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




  <h4>สินค้าในร้านค้า</h4>
  <div class="content-panel row">

    <div class="col-md-12">
      <div class="content-section">

        <div class="row">

          @if(!empty($products))
            @foreach($products as $product)

            <div class="col-md-4">
              <div class="info-box">
                <a href="{{$product['detailUrl']}}">
                  <div class="primary-image-tile" style="background-image:url({{$product['_imageUrl']}});"></div>
                </a>
                <div class="primary-info-section">
                  <a href="{{$product['detailUrl']}}">
                    <h4 class="title">{{$product['name']}}</h4>
                  </a>
                  <div class="secondary-info-section">
                    <div class="price">{{$product['_price']}}</div>
                  </div>
                </div>
              </div>
            </div>

            @endforeach

          @else

            <div class="image-section">
              <img class="icon" src="/images/common/tag.png">
            </div>

            <div class="list-empty-message text-center space-top-bottom-20">
              <div>
                <h3>ยังไม่มีสินค้า</h3>
                <a href="URL::to('community/shop/create')" class="button">เพิ่มบริษัทหรือร้านค้าและเพิ่มสินค้าลงในร้านค้าของคุณ</a>
              </div>
            </div>

          @endif

        </div>

      </div>
    </div>

  </div>

  <div class="clearfix text-right space-top-20">
    <a  href="URL::to('product')" class="button">ไปยังหน้าแสดงสินค้าของร้านค้า</a>
  </div>

  <h4>ประกาศงานจากบริษัทและร้านค้า</h4>
  <div class="content-panel row">

    <div class="col-md-12">
      <div class="content-section">

        <div class="row">

          @if(!empty($jobs))

            @foreach($jobs as $job)

            <div class="col-md-4">
              <div class="info-box">
                <a href="{{$job['detailUrl']}}">
                  <div class="primary-image-tile" style="background-image:url({{$job['_imageUrl']}});"></div>
                </a>
                <div class="primary-info-section">
                  <a href="{{$job['detailUrl']}}">
                    <h4 class="title">{{$job['name']}}</h4>
                  </a>
                  <div class="secondary-info-section">
                    <strong>{{$job['_salary']}}</strong>
                  </div>
                </div>
              </div>
            </div>

            @endforeach

          @else

            <div class="image-section">
              <img class="icon" src="/images/common/career.png">
            </div>

            <div class="list-empty-message text-center space-top-bottom-20">
              <div>
                <h3>ยังไม่มีประกาศงาน</h3>
                <a href="URL::to('community/shop/create')" class="button">เพิ่มบริษัทหรือร้านค้าและเพิ่มการประกาศงานของคุณ</a>
              </div>
            </div>

          @endif

        </div>

      </div>
    </div>

  </div>

  <div class="clearfix text-right space-top-20">
    <a href="URL::to('job')" class="button">ไปยังหน้าแสดงประกาศงาน</a>
  </div>

  <h4>ประกาศโฆษณาจากบริษัทและร้านค้า</h4>
  <div class="content-panel row">

    <div class="col-md-12">
      <div class="content-section">

        <div class="row">

          @if(!empty($advertisings))

            @foreach($advertisings as $advertising)

            <div class="col-md-4">
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

          @else

            <div class="image-section">
              <img class="icon" src="/images/common/megaphone.png">
            </div>

            <div class="list-empty-message text-center space-top-bottom-20">
              <div>
                <h3>ยังไม่มีประกาศโฆษณา</h3>
                <a href="URL::to('community/shop/create')" class="button">เพิ่มบริษัทหรือร้านค้าและเพิ่มการประกาศโฆษณาของคุณ</a>
              </div>
            </div>

          @endif

        </div>

      </div>
    </div>

  </div>

  <div class="clearfix text-right space-top-20">
    <a href="URL::to('advertising')" class="button">ไปยังหน้าแสดงประกาศโฆษณา</a>
  </div>

  <h4>ประกาศซื้อขายสินค้า</h4>
  <div class="content-panel row">

    <div class="col-md-12">
      <div class="content-section">

        <div class="row">

          @if(!empty($items))

            @foreach($items as $item)

            <div class="col-md-4">
              <div class="info-box">
                <a href="{{$item['detailUrl']}}">
                  <div class="primary-image-tile" style="background-image:url({{$item['_imageUrl']}});"></div>
                </a>
                <div class="primary-info-section">
                  <a href="{{$item['detailUrl']}}">
                    <h4 class="title">{{$item['name']}}</h4>
                  </a>
                  <div class="secondary-info-section">
                    <div class="price">{{$item['_price']}}</div>
                  </div>
                </div>
              </div>
            </div>

            @endforeach

          @else

            <div class="image-section">
              <img class="icon" src="/images/common/tag.png">
            </div>

            <div class="list-empty-message text-center space-top-bottom-20">
              <div>
                <h3>ยังไม่มีประกาศซื้อขายสินค้า</h3>
                <a href="URL::to('item/post')" class="button">เพิ่มประกาศซื้อขายสินค้า</a>
              </div>
            </div>

          @endif

        </div>

      </div>
    </div>

  </div>

  <div class="clearfix text-right space-top-20">
    <a href="URL::to('item')" class="button">ไปยังแสดงหน้าประกาศซื้อขายสินค้า</a>
  </div>

  <h4>ประกาศซื้อขายอสังหาทรัพย์</h4>
  <div class="content-panel row">

    <div class="col-md-12">
      <div class="content-section">

        <div class="row">

          @if(!empty($realEstates))

            @foreach($realEstates as $realEstate)

            <div class="col-md-4">
              <div class="info-box">
                <a href="{{$realEstate['detailUrl']}}">
                  <div class="primary-image-tile" style="background-image:url({{$realEstate['_imageUrl']}});"></div>
                </a>
                <div class="primary-info-section">
                  <a href="{{$realEstate['detailUrl']}}">
                    <h4 class="title">{{$realEstate['name']}}</h4>
                  </a>
                  <div class="secondary-info-section">
                    <div class="price">{{$realEstate['_price']}}</div>
                  </div>
                  <div class="secondary-info-section">
                    <h5>ประเภท</h5>
                    <strong>{{$realEstate['_realEstateTypeName']}}</strong>
                  </div>
                </div>
              </div>
            </div>

            @endforeach

          @else

            <div class="image-section">
              <img class="icon" src="/images/common/building.png">
            </div>

            <div class="list-empty-message text-center space-top-bottom-20">
              <div>
                <h3>ยังไม่มีประกาศซื้อขายอสังหาทรัพย์</h3>
                <a href="URL::to('real-estate/post')" class="button">เพิ่มประกาศซื้อขายอสังหาทรัพย์</a>
              </div>
            </div>

          @endif

        </div>

      </div>
    </div>

  </div>

  <div class="clearfix text-right space-top-20">
    <a href="URL::to('real-estate')" class="button">ไปยังหน้าแสดงประกาศซื้อขายอสังหาทรัพย์</a>
  </div>

</div>

@stop