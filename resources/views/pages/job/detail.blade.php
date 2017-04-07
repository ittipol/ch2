@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h4 class="sub-title">ประกาศงาน</h4>
        <h2 class="title">{{$_modelData['name']}}</h2>
        <div class="tag-group">
          <a class="tag-box">{{$shop['name']}}</a>
          <a class="tag-box">{{$_modelData['_employmentTypeName']}}</a>
          @foreach ($_modelData['Tagging'] as $tagging)
            <a class="tag-box">{{$tagging['_word']}}</a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

<div class="detail container">

  @if($personApplyJob)
    <h4 class="sign info wide space-bottom-20">สมัครงานนี้แล้ว</h4>
  @else
    <div class="row space-bottom-30">
      <a class="button pull-right" href="{{$jobApplyUrl}}">
        <img src="/images/icons/edit-white.png">
        สมัครงานนี้ผ่าน CHONBURI SQUARE
      </a>
    </div>
  @endif

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
              <a class="open-description">แสดงคำอธิบายรูปภาพ</a>
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

  <div class="content-box content-box-bg" style="background-image:url({{$shopCoverUrl}})">
    <div class="content-box-inner">
      <div class="row">

        <div class="col-md-6 col-sm-12">
          <div class="content-box-panel overlay-bg">
            <div>
              <h5>บริษัทหรือร้านค้า</h5>
              <h3>
                <a href="{{URL::to($shopUrl)}}">{{$shop['name']}}</a>
              </h3>
              <div class="line space-top-bottom-20"></div>
              <p>{{$shop['_short_description']}}</p>
            </div>

            <a href="{{URL::to($shopUrl)}}" class="button wide-button">ไปยังร้านค้านี้</a>
          </div>
        </div>

        <div class="col-md-6 col-sm-12"></div>

      </div>
    </div>
  </div>

  <div class="row">

    <div class="col-xs-4">
      <div class="item-info">

        <div class="item-info-row">
          <p>เงินเดือน (บาท)</p>
          <h4 class="price">{{$_modelData['_salary']}}</h4>
        </div>

      </div>
    </div>

    <div class="col-xs-8">

      <p>รูปแบบงาน</p>
      <h4>{{$_modelData['_employmentTypeName']}}</h4>

    </div>

  </div>

  <div class="row">

    <div class="col-sm-12 margin-section section-border-left">

      <div class="space-top-bottom-10 section-inner">
        
        <h4>คุณสมบัติผู้สมัคร</h4>   
        <div>
          {!!$_modelData['qualification']!!}
        </div>

      </div>

    </div>

  </div>

  <div class="row">

    <div class="col-sm-12 margin-section section-border-left">

      <div class="space-top-bottom-10 section-inner">
        
        <h4>รายละเอียดงาน</h4>   
        <div>
          {!!$_modelData['description']!!}
        </div>

      </div>

    </div>

  </div>

  <div class="row">

    <div class="col-sm-12 margin-section section-border-left">

      <div class="space-top-bottom-10 section-inner">
        
        <h4>สวัสดิการ</h4>   
        <div>
          {!!$_modelData['benefit']!!}
        </div>

      </div>

    </div>

  </div>

  @if($hasBranchLocation)
  <h4>สาขาที่กำลังเปิดรับสมัครงานนี้</h4>
  <div class="line space-top-bottom-20"></div>
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

  @endif

  <div class="space-top-20">

    <h4>สมัครงานนี้ได้ที่</h4>

    <div class="text-center space-top-bottom-20">
      @if($personApplyJob)
        <h4 class="sign info">สมัครงานนี้แล้ว</h4>
      @else
      <a class="button" href="{{$jobApplyUrl}}">
        <img src="/images/icons/edit-white.png">
        สมัครงานนี้ผ่าน CHONBURI SQUARE
      </a>
      @endif
    </div>

    @if(!empty($_modelData['_recruitment_custom']) && !empty($_modelData['recruitment_custom_detail']))

    <div class="text-strike">
      <span>หรือ</span>
      <div class="line"></div>
    </div>
    
    <div class="space-top-30">
      {!!$_modelData['recruitment_custom_detail']!!}
    </div>
    @endif

  </div>


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