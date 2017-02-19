@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

<div class="top-header-wrapper">
  <div class="top-header">
    <div class="detail-title">
      <h4 class="sub-title">สาขา</h4>
      <h2 class="title">{{$_modelData['name']}}</h2>
    </div>
  </div>
</div>

<div class="detail container">

  <h4 class="title-with-icon location-pin">{{$_modelData['Address']['_short_address']}}</h4>

  <div class="image-gallery">

    <div class="row">

      <div class="col-sm-12 image-gallary-display">
        <div class="image-gallary-display-inner">

          <div class="image-gallary-panel">
            <img id="image_display">
          </div>

          <div class="image-description">
           <div id="image_description" class="image-description-inner"></div>
           <div class="close-image-description-icon"></div>
          </div>

          <div class="display-image-description-icon">
            คำอธิบายรูปนี้
          </div>

        </div>
      </div>

    </div>

    @if(!empty($_modelData['Image']))
    <div class="row">
      <div class="col-sm-12">
        <div id="image_gallery_list" class="image-gallery-list clearfix"></div>
      </div>
    </div>
    <div class="line space-top-bottom-20"></div>
    @endif

  </div>

  <div class="row">
    <div class="col-xs-12">
    <p>ชื่อบริษัทหรือร้านค้า</p>
    <h4>{{$shopName}}</h4>
    </div>
  </div>

  <div class="line space-top-bottom-20"></div>

  <div class="row">

    <div class="col-xs-12">

      <div class="item-info">

        <p>ติดต่อสาขา</p>

        <div class="row">
          <div class="col-md-3">
            <div class="item-info-row">
              @if(!empty($_modelData['Contact']['phone_number']))
              <h4 class="title-with-icon phone">{{$_modelData['Contact']['phone_number']}}</h4>
              @else
              <h4 class="title-with-icon phone">-</h4>
              @endif
            </div>
          </div>
          <div class="col-md-3">
            <div class="item-info-row">
              @if(!empty($_modelData['Contact']['email']))
              <h4 class="title-with-icon email">{{$_modelData['Contact']['email']}}</h4>
              @else
              <h4 class="title-with-icon email">-</h4>
              @endif
            </div>
          </div>
          <div class="col-md-3">
            <div class="item-info-row">
              @if(!empty($_modelData['Contact']['line']))
              <h4 class="title-with-icon line-app">{{$_modelData['Contact']['line']}}</h4>
              @else
              <h4 class="title-with-icon line-app">-</h4>
              @endif
            </div>
          </div>
        </div>

      </div>

    </div> 

  </div>

  <div class="line space-top-bottom-20"></div>

  <h4>ตำแหน่งบนแผนที่</h4>
  <div id="map"></div>

  <div class="line space-top-bottom-20"></div>
  <h4>งานที่กำลังเปิดรับสมัครในสาขานี้</h4>

  @if(!empty($jobs))

    <div class="grid-card">

      <div class="row">

        @foreach($jobs as $data)

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
          <div class="card">
            <div class="image">
              <a href="{{$data['detailUrl']}}">
                <div class="product-image" style="background-image:url({{$data['_imageUrl']}});"></div>
              </a>
            </div>
            <div class="product-detail">
              <a href="{{$data['detailUrl']}}">
                <div class="product-title">{{$data['_short_name']}}</div>
              </a>
              <div class="price">
                {{$data['_salary']}}
              </div>
            </div>
            <div>
  
              <a href="{{$data['detailUrl']}}">
                <div class="button wide-button">แสดง</div>
              </a>
      
            </div>
          </div>
        </div>

        @endforeach

      </div>

    </div>

  @else

    <div>ไม่พบงานที่กำลังเปิดรับในสาขานี้</div>

  @endif


</div>

<script type="text/javascript">
  $(document).ready(function(){
    imageGallery = new ImageGallery(true);
    imageGallery.load({!!$_modelData['Image']!!});

    const map = new Map(false,false,false);
    map.initialize();
    map.setLocation({!!$_modelData['Address']['_geographic']!!});
  });
</script>

@stop