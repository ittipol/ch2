@extends('layouts.blackbox.main')
@section('content')

<div class="banner">
  <div class="container">
    <div class="banner-overlay-message">
      <h2 class="banner-primary-title">เริ่มต้นธุรกิจของคุณและเชื่อมต่อธุรกิจของคุณกับผู้คนในชลบุรี</h2>
      <p class="banner-description">เพิ่มธุรกิจของคุณ และให้เราทำหน้าที่เชื่อมต่อธุรกิจของคุณกับผู้คนในขลบุรี</p>
      <a href="{{URL::to('community/shop_create')}}" class="button">เริ่มต้นธุรกิจของคุณ</a>
    </div>
  </div>
</div>

<div class="container">

  <div class="row">
    <div class="col-xs-12">
      <h3>ชุมชน</h3>
      <p>เชื่อมต่อคุณหรือธุรกิจของคุณกับชุมชนและผู้คนในชลบุรี เพื่อสะดวกต่อการเข้าถึงบิษัท ร้านค้า สินค้า แบรนด์ งานบริการ ตำแหน่งงาน และอื่นๆ อีกมากมาย</p>
    </div>

    <div class="site-map col-xs-12">

      <div class="row">

        <div class="col-md-3">
          <h4 class="site-map-title">บริษัทและร้านค้า</h4>
          <div class="site-map-content">
            <div class="site-map-link">
              <a href="{{URL::to('community/shop_create')}}">เพิ่มร้านค้าของคุณ</a>
            </div>
            <div class="site-map-link">
              <a href="{{URL::to('product')}}">สินค้าในร้านค้า</a>
            </div>
            <div class="site-map-link">
              <a href="{{URL::to('job')}}">ประกาศงานจากบริษัทและร้านค้า</a>
            </div>
            <div class="site-map-link">
              <a href="{{URL::to('advertising')}}">ประกาศโฆษณาจากบริษัทและร้านค้า</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <h4 class="site-map-title">บอร์ดประกาศ</h4>
          <div class="site-map-content">
            <div class="site-map-link">
              <a href="{{URL::to('item')}}">ประกาศซื้อ-เช่า-ขายสินค้า</a>
            </div>
            <div class="site-map-link">
              <a href="{{URL::to('real-estate')}}">ประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <h4 class="site-map-title">ประวัติการทำงาน</h4>
          <div class="site-map-content">
            <div class="site-map-link">
              <a href="{{URL::to('person/experience')}}">ประวัติการทำงานของคุณ</a>
            </div>
            <div class="site-map-link">
              <a href="{{URL::to('experience/profile/list')}}">แสดงประวัติการทำงาน</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <h4 class="site-map-title">ฟรีแลนซ์</h4>
          <div class="site-map-content">
            <div class="site-map-link">
              <a href="{{URL::to('person/freelance')}}">เพิ่มงานฟรีแลนซ์ของคุณ</a>
            </div>
            <div class="site-map-link">
              <a href="{{URL::to('freelance/list')}}">ค้นหาฟรีแลนซ์</a>
            </div>
          </div>
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
                <a href="URL::to('community/shop_create')" class="button">เพิ่มบริษัทหรือร้านค้าและเพิ่มสินค้าลงในร้านค้าของคุณ</a>
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
                    <h5>เงินเดือน</h5>
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
                <a href="URL::to('community/shop_create')" class="button">เพิ่มบริษัทหรือร้านค้าและเพิ่มการประกาศงานของคุณ</a>
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
                <a href="URL::to('community/shop_create')" class="button">เพิ่มบริษัทหรือร้านค้าและเพิ่มการประกาศโฆษณาของคุณ</a>
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