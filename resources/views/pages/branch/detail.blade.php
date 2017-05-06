@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}branch" class="btn btn-secondary">กลับไปหน้าหลักสาขา</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{request()->get('shopUrl')}}">ไปยังหน้าหลักร้านค้า</a>
              <a href="{{request()->get('shopUrl')}}product">ไปยังหน้าหลักสินค้า</a>
              <a href="{{request()->get('shopUrl')}}job">ไปยังหน้าหลักประกาศงาน</a>
              <a href="{{request()->get('shopUrl')}}advertising">ไปยังหน้าหลักโฆษณา</a>
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

      <div class="line only-space space-top-bottom-20"></div>

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

  <div class="row">

    <div class="col-md-3 col-xs-12 space-top-20">

      <div class="item-info">
        <div class="item-info-row">
          <p>ชื่อสาขา</p>
          <h4>{{$_modelData['name']}}</h4>
        </div>
      </div>

      <div class="item-info">
        <p>ติดต่อสาขา</p>
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

      <div class="detail-info-section">
        <h4>รายละเอียดสาขา</h4>
        <div class="line"></div> 
        <div class="detail-info">
          {!!$_modelData['description']!!}
        </div>
      </div>

    </div>

  </div>

  <h4>ตำแหน่งบนแผนที่</h4>
  <div class="line"></div>
  <div id="map"></div>

  <div class="line space-top-bottom-20"></div>
  <h4>งานที่กำลังเปิดรับสมัครในสาขานี้</h4>

  @if(!empty($jobs))

    <div class="grid-card">

      <div class="row">

        @foreach($jobs as $data)

        <div class="col-lg-3 col-xs-6">
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
                {{$data['_wage']}}
              </div>
            </div>
   
          </div>
        </div>

        @endforeach

      </div>

    </div>

  @else

    <div class="list-empty-message text-center space-top-20">
      <img class="space-bottom-20" src="/images/common/not-found.png">
      <div>
        <p>ไม่พบงานที่กำลังเปิดรับในสาขานี้</p>
      </div>
    </div>

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