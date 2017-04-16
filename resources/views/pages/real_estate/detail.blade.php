@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h4 class="sub-title">ประกาศ{{$_modelData['_announcementTypeName']}}</h4>
        <h2 class="title">{{$_modelData['name']}}</h2>
        <div class="tag-group">
          <a class="tag-box">{{$_modelData['_realEstateTypeName']}}</a>
          @if($_modelData['need_broker'])
          <a class="tag-box">{{$_modelData['_need_broker']}}</a>
          @endif
          @foreach ($_modelData['Tagging'] as $tagging)
            <a class="tag-box">{{$tagging['_word']}}</a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

<div class="detail container">

  <h4 class="title-with-icon location-pin">{{$_modelData['Address']['_short_address']}}</h4>

  <div class="image-gallery">

    <div class="row">

      <div class="col-sm-12 image-gallary-display">

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

          <div class="display-image-description-icon additional-option icon">
            <img src="/images/icons/additional-white.png">
            <div class="additional-option-content">
              <a class="image-description-display-button">แสดงคำอธิบายรูปภาพ</a>
            </div>
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

    <div class="col-xs-6">
      <div class="item-info">

        <div class="item-info-row">
          <p>ราคา{{$_modelData['_announcementTypeName']}}</p>
          <h4 class="text-emphasize">{{$_modelData['_price']}}</h4>
        </div>

      </div>
    </div>

    <div class="col-xs-6">
      <div class="item-info">

        <p>ติดต่อผู้{{$_modelData['_announcementTypeName']}}</p>

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

  <div class="row">
    <div class="list-description col-md-6 col-sm-12">
      <dl class="list-description-item row">
        <dt class="col-sm-4">ประเภท</dt>
        <dd class="col-sm-8">{{$_modelData['_realEstateTypeName']}}</dd>
      </dl>

      <dl class="list-description-item row">
        <dt class="col-sm-4">พื้นที่ใช้สอย</dt>
        <dd class="col-sm-8">{{$_modelData['_homeArea']}}</dd>
      </dl>

      <dl class="list-description-item row">
        <dt class="col-sm-4">พื้นที่ที่ดิน</dt>
        <dd class="col-sm-8">{{$_modelData['_landArea']}}</dd>
      </dl>
    </div>

    <div class="list-description col-md-6 col-sm-12">
      <dl class="list-description-item row">
        <dt class="col-sm-4">เฟอร์นิเจอร์</dt>
        <dd class="col-sm-8">{{$_modelData['_furniture']}}</dd>
      </dl>
    </div>
  </div>

  <div class="line space-top-bottom-20"></div>

  <div class="row">
    <dt class="col-sm-3">คุณสมบัติ</dt>
    <dd class="col-sm-9">
    @foreach($_modelData['_indoors'] as $indoor)
      <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="title-with-icon space {{$indoor['room']}}"><b>{{$indoor['value']}}</b> {{$indoor['name']}}</div>
      </div>
    @endforeach
    </dd>
  </div>

  <div class="line space-top-bottom-20"></div>

  <div class="row">
    <dt class="col-sm-3">จุดเด่น</dt>
    <dd class="col-sm-9">
    @if(!empty($_modelData['_features']))
      @foreach($_modelData['_features'] as $feature)

      <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="title-with-icon space tick-green">{{$feature['name']}}</div>
      </div>

      @endforeach
    @else
      <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="">-</div>
      </div>
    @endif
    </dd>
  </div>

  <div class="line space-top-bottom-20"></div>

  <div class="row">
    <dt class="col-sm-3">สิ่งอำนวยความสะดวก</dt>
    <dd class="col-sm-9">
    @if(!empty($_modelData['_facilities']))
      @foreach($_modelData['_facilities'] as $facility)

      <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="title-with-icon space tick-green">{{$facility['name']}}</div>
      </div>

      @endforeach
    @else
      <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="">-</div>
      </div>
    @endif
    </dd>
  </div>

  <!-- <div class="line space-top-bottom-20"></div>

  <div class="row">

    <div class="col-sm-12 margin-section section-border-left">

      <div class="space-top-bottom-10 section-inner">
        
        <h4>รายละเอียดอสังหาริมทรัพย์</h4>
        <div class="line space-bottom-20"></div>
        <div>
          {!!$_modelData['description']!!}
        </div>

      </div>

    </div>

  </div> -->

  <div class="row detail-info-section space-bottom-30">
    <div class="col-xs-12">

      <h4>รายละเอียดอสังหาริมทรัพย์</h4>
      <div class="line"></div> 
      <div class="detail-info">
        {!!$_modelData['description']!!}
      </div>

    </div>
  </div>

  <h4>ตำแหน่งบนแผนที่</h4>
  <div id="map"></div>

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