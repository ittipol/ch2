@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

<div class="shop-wrapper">

  @include('pages.shop.layouts.top_nav') 
  @include('pages.shop.layouts.header') 

  <div class="shop-notice">

    <div class="container">
    
      <div class="shop-notice-header">
        <h3>ยินดีต้อนรับเข้าสู่ชุมชน</h3>
        <p>กรุณาเพิ่มข้อมูลต่างๆของร้านค้าของคุณ ก่อนการใช้งาน</p>
      </div>

      <div class="shop-notice-content">
        <div class="shop-notice-row">
          <a href="{{$settingUrl}}"><h4 class="text-center">เพิ่มข้อมูลของร้านค้า</h4></a>
        </div>
      </div>

    </div>

  </div>

  <div class="line"></div>

  <div class="container">

    <div class="row">

      <div class="col-md-4 col-xs-12">
        <div class="box">
          <div class="box-inner">
            <h3>สินค้า</h3>

            @if(!empty($products))

              <h4>จำนวนสินค้าทั้งหมด: {{$totalProduct}} รายการ</h4>

              <h5 class="space-top-20">สินค้าล่าสุด</h5>
              <div class="line"></div>

              @foreach($products as $product)

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

              @endforeach

            @else

            <div class="notice text-center space-top-40">
              <img class="space-bottom-20" src="/images/common/tag.png">
              <div>
                <h3>สินค้า</h3>
                <p>ยังไม่มีสินค้า เพิ่มสินค้า เพื่อขายสินค้าของคุณ</p>
                <a href="{{$productPostUrl}}" class="button">เพิ่มสินค้า</a>
              </div>
            </div>

            @endif

          </div>
        </div>
      </div>

      <div class="col-md-4 col-xs-12">
        <div class="box">
          <div class="box-inner">
            <h3>ประกาศงาน</h3>

            @if(!empty($jobs))

            <h4>จำนวนประกาศทั้งหมด: {{$totalJob}} รายการ</h4>

            <h5 class="space-top-20">ประกาศล่าสุด</h5>
            <div class="line"></div>

              @foreach($jobs as $job)

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

              @endforeach

            @else

              <div class="notice text-center space-top-40">
                <img class="space-bottom-20" src="/images/common/career.png">
                <div>
                  <h3>ลงประกาศงาน</h3>
                  <p>ยังไม่มีประกาศงานของคุณ เพิ่มประกาศงานของคุณเพื่อค้นหาพนักงานใหม่</p>
                  <a href="" class="button">ลงประกาศงาน</a>
                </div>
              </div>

            @endif

          </div>
        </div>
      </div>

      <div class="col-md-4 col-xs-12">
        <div class="box">
          <div class="box-inner">
            <h3>โฆษณา</h3>

            @if(!empty($advertisings))

              <h4>จำนวนประกาศทั้งหมด: {{$totalAdvertising}} รายการ</h4>

              <h5 class="space-top-20">ประกาศล่าสุด</h5>
              <div class="line"></div>

              @foreach($advertisings as $advertising)

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

              @endforeach

            @else

              <div class="notice text-center space-top-40">
                <img class="space-bottom-20" src="/images/common/megaphone.png">
                <div>
                  <h3>โฆษณา</h3>
                  <p>ยังไม่มีประกาศงานของคุณ เพิ่มประกาศงานของคุณเพื่อค้นหาพนักงานใหม่</p>
                  <a href="" class="button">ลงโฆษณา</a>
                </div>
              </div>

            @endif

          </div>
        </div>
      </div>

      @if(!empty($_modelData['Address']))
      <div class="col-xs-12">
        <div class="box">
          <div class="box-inner">
            <h3>ที่อยู่และการติดต่อ</h3>

            <div id="map" class="map-small"></div>

            <div>
              <h5><strong>ที่อยู่</strong></h5>
              {{$_modelData['Address']['_long_address']}}
            </div>

            <div>
              <h5><strong>หมายเลขโทรศัพท์</strong></h5>
              {{$_modelData['Contact']['phone_number']}}
            </div>

            <div>
              <h5><strong>อีเมล</strong></h5>
              {{$_modelData['Contact']['email']}}
            </div>

          </div>
        </div>
      </div>
      @endif

    </div>

  </div>

</div>

<script type="text/javascript">
  $(document).ready(function(){

    @if(!empty($_modelData['Address']['_geographic']))
    const map = new Map(false,false,false);
    map.initialize();
    map.setLocation({!!$_modelData['Address']['_geographic']!!});
    @endif

  });
</script>

@stop