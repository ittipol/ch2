@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

<div class="top-header-wrapper">
  <div class="top-header">
    <div class="detail-title">
      <h4 class="sub-title">งาน</h4>
      <h2 class="title">{{$_modelData['name']}}</h2>
      <div class="tag-group">
        <a class="tag-box">{{$shopName}}</a>
        <a class="tag-box">{{$_modelData['_advertisingType']}}</a>
        @foreach ($_modelData['Tagging'] as $tagging)
          <a class="tag-box">{{$tagging['_word']}}</a>
        @endforeach
      </div>
    </div>
  </div>
</div>

<div class="detail container">

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

  <div class="row">
    <div class="list-description col-md-6 col-sm-12">
      <dl class="list-description-item row">
        <dt class="col-sm-4">ประเภทโฆษณา</dt>
        <dd class="col-sm-8">{{$_modelData['_advertisingType']}}</dd>
      </dl>
    </div>
  </div>

  <div class="line space-top-bottom-20"></div>

  <h4>รายละเอียดโฆษณา</h4>   
  <div>
    {!!$_modelData['description']!!}
  </div>

  <div class="line space-top-bottom-20"></div>

  @if($hasBranchLocation)
  <h4>สาขาที่ลงโฆษณานี้</h4>   
  <div class="row">
    <div class="col-xs-12">

      <div id="map_panel" class="map-panel">

        <div id="map"></div>
        <div class="side-panel">
          <div class="nano">
            <div id="location_items" class="nano-content"></div>
          </div>
        </div>

      </div>

    </div> 
  </div>

  <div class="line space-top-bottom-20"></div>

  @endif

</div>

<script type="text/javascript">
  $(document).ready(function(){
    imageGallery = new ImageGallery(true);
    imageGallery.load({!!$_modelData['Image']!!});

    const map = new Map(false,false,false);
    map.setLocations({!!$branchLocations!!});
  });
</script>

@stop