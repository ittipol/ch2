@extends('layouts.blackbox.main')
@section('content')

@if(!empty(request()->get('shop')))
@include('pages.shop.layouts.fixed_top_nav')
@endif

<div class="container">
  <h3 class="title">{{$_modelData['name']}}</h3>
  <div class="line"></div>
</div>

<div class="detail container">

  <div class="row">

    <div class="image-gallery space-top-30 col-xs-12">

      <div class="image-gallary-display">

        <div class="image-description">
         <div class="image-description-inner">
          <div id="image_description"></div>
         </div>
         <div class="close-image-description-icon"></div>
         <div class="image-description-pagination clearfix">
            <div id="prev_image_description" class="prev-image-description-icon pull-left"></div>
            <div class="pull-left">
              <span id="current_image_description" class="current-page-number"></span>
              <span>/</span>
              <span id="total_image_description" class="total-page-number"></span>
            </div class="pull-left">
            <div id="next_image_description" class="next-image-description-icon pull-left"></div>
          </div>
        </div>

        <div class="image-gallary-display-inner">
          <div class="image-gallary-panel">
            <img id="image_display">
          </div>

          <div class="additional-option">
              <div class="dot"></div>
              <div class="dot"></div>
              <div class="dot"></div>
              <div class="additional-option-content">
                <a class="image-description-display-button">คำอธิบายรูปภาพ</a>
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
      @endif

      <div class="line space-top-bottom-20"></div>

    </div>

  </div>

  <div class="row">

    <div class="col-md-3 col-xs-12 space-top-10">
      <div class="item-info">
        <div class="item-info-row">
          <p>ประเภทโฆษณา</p>
          <h4>{{$_modelData['_advertisingType']}}</h4>
        </div>
      </div>
    </div>

    <div class="col-md-9 col-xs-12">
      <div class="detail-info-section no-margin">
        <h4>รายละเอียดโฆษณา{{$_modelData['_advertisingType']}}</h4>
        <div class="line"></div> 
        <div class="detail-info description">
          {!!$_modelData['description']!!}
        </div>
      </div>
    </div>

  </div>

  <div class="content-box content-box-bg" style="background-image:url({{$shopCoverUrl}})">
    <div class="content-box-inner">
      <div class="row">

        <div class="col-md-6 col-xs-12">
          <div class="content-box-panel overlay-bg">
            <div>
              <h5>บริษัทหรือร้านค้า</h5>
              <h3>
                <a href="{{URL::to($shopUrl)}}">{{$shop['name']}}</a>
              </h3>
              <div class="line space-top-bottom-20"></div>
              <p>{{$shop['_short_description']}}</p>
            </div>

            <a href="{{URL::to($shopUrl)}}" class="button wide-button">ไปยังบริษัทหรือร้านค้า</a>
          </div>
        </div>

        <div class="col-md-6 col-xs-12"></div>

      </div>
    </div>
  </div>

  @if($hasBranchLocation)
    <!-- <h4 class="article-title color-teal">สาขาที่ลงโฆษณานี้</h4> 
    <div class="line"></div>  
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
    </div> -->
  @endif

</div>

<script type="text/javascript">
  $(document).ready(function(){
    imageGallery = new ImageGallery(true);
    imageGallery.load({!!$_modelData['Image']!!});

    @if($hasBranchLocation)
    // const map = new Map(false,false,false);
    // map.setLocations({!!$branchLocations!!});
    @endif
    
  });
</script>

@stop