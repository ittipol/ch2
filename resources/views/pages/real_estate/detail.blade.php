@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{URL::to('real-estate/board')}}/{{$_modelData['announcement_type_id']}}" class="btn btn-secondary">กลับไปหน้าประกาศ{{$_modelData['_realEstateTypeName']}}</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{URL::to('real-estate/board')}}" class="btn btn-secondary">ไปยังหน้าภาพรวมประกาศ</a>
              <a href="{{URL::to('real-estate/post')}}">เพิ่มประกาศ</a>
            </div>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="container">
  <h3 class="title">{{$_modelData['name']}}</h3>
  <div class="line"></div>
</div>

<div class="detail container">

  <h4 class="title-with-icon location-pin">{{$_modelData['Address']['_short_address']}}</h4>

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

  @if (Auth::check() && (Session::get('Person.id') == $_modelData['created_by']))
  <div class="text-right">
    <a href="{{$deleteUrl}}" class="button" data-modal="1" data-modal-title="ต้องการยกเลิกประกาศ {{$_modelData['name']}} ใช่หรือไม่">ยกเลิกประกาศ</a>
  </div>
  @endif

  <div class="row">

    <div class="col-md-3 col-xs-12">

      <div class="item-info">

        <div class="item-info-row">
          <p>ราคา{{$_modelData['_announcementTypeName']}}</p>
          <h4 class="text-emphasize">{{$_modelData['_price']}}</h4>
        </div>

      </div>

      <div class="item-info">

        <p>ติดต่อผู้{{$_modelData['_announcementTypeName']}}</p>

        <div class="row">
          <div class="col-xs-12">
            <div class="item-info-row">
              @if(!empty($_modelData['Contact']['phone_number']))
              <h4 class="title-with-icon phone">{{$_modelData['Contact']['phone_number']}}</h4>
              @else
              <h4 class="title-with-icon phone">-</h4>
              @endif
            </div>
          </div>
          <div class="col-xs-12">
            <div class="item-info-row">
              @if(!empty($_modelData['Contact']['email']))
              <h4 class="title-with-icon email">{{$_modelData['Contact']['email']}}</h4>
              @else
              <h4 class="title-with-icon email">-</h4>
              @endif
            </div>
          </div>
        </div>

      </div>

    </div>

    <div class="col-md-9 col-xs-12">

      <div class="flag-group space-bottom-20">
        <div class="flag">ประกาศ{{$_modelData['_announcementTypeName']}}</div>
        <div class="flag">{{$_modelData['_realEstateTypeName']}}</div>
      </div>

      <div class="row">
        <div class="list-description col-md-6 col-xs-12">
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

        <div class="list-description col-md-6 col-xs-12">
          <dl class="list-description-item row">
            <dt class="col-sm-4">เฟอร์นิเจอร์</dt>
            <dd class="col-sm-8">{{$_modelData['_furniture']}}</dd>
          </dl>
        </div>
      </div>

    </div>

  </div>

  <div class="space-top-20">
    <h4>คุณสมบัติ</h4>
    <div class="line space-bottom-20"></div>
    <div class="row">
    @foreach($_modelData['_indoors'] as $indoor)
      <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="title-with-icon space {{$indoor['room']}}"><b>{{$indoor['value']}}</b> {{$indoor['name']}}</div>
      </div>
    @endforeach
    </div>
  </div>

  <div class="line only-space space-top-bottom-30"></div>

  <div class="space-top-20">
    <h4>จุดเด่น</h4>
    <div class="line space-bottom-20"></div>
    <div class="row">
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
    </div>
  </div>

  <div class="line only-space space-top-bottom-30"></div>

  <div class="space-top-20">
    <h4>สิ่งอำนวยความสะดวก</h4>
    <div class="line space-bottom-20"></div>
    <div class="row">
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
    </div>
  </div>

  <div class="row detail-info-section space-bottom-30">
    <div class="col-xs-12">

      <h4>รายละเอียดอสังหาริมทรัพย์</h4>
      <div class="line"></div> 
      <div class="detail-info description">
        {!!$_modelData['description']!!}
      </div>

    </div>
  </div>

  @if(!empty($_modelData['Address']['_geographic']))
  <h4>ตำแหน่งบนแผนที่</h4>
  <div class="line"></div>
  <div id="map"></div>
  @endif

</div>

<script type="text/javascript">
  $(document).ready(function(){
    imageGallery = new ImageGallery(true);
    imageGallery.load({!!$_modelData['Image']!!});

    @if(!empty($_modelData['Address']['_geographic']))
    const map = new Map(false,false,false);
    map.initialize();
    map.setLocation({!!$_modelData['Address']['_geographic']!!});
    @endif
  });
</script>

@stop