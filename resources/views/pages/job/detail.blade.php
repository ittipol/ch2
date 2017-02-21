@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

<div class="top-header-wrapper">
  <div class="top-header">
    <div class="detail-title">
      <h4 class="sub-title">ประกาศงาน</h4>
      <h2 class="title">{{$_modelData['name']}}</h2>
      <div class="tag-group">
        <a class="tag-box">{{$shopName}}</a>
        <a class="tag-box">{{$_modelData['_employmentTypeName']}}</a>
        @foreach ($_modelData['Tagging'] as $tagging)
          <a class="tag-box">{{$tagging['_word']}}</a>
        @endforeach
      </div>
    </div>
  </div>
</div>

<div class="detail container">

  @if($personApplyJob)
    <h4 class="sign info wide space-bottom-20">สมัครงานนี้แล้ว</h4>
  @else
    <div class="row space-bottom-30">
      <a class="pull-right" href="{{$jobApplyUrl}}">
        <span class="button">สมัครงานนี้ผ่าน CHONBURI SQUARE</span>
      </a>
    </div>
  @endif

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
            <img src="/images/icons/additional-white.png">
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

    <div class="col-xs-4">
      <div class="item-info">

        <div class="item-info-row">
          <p>เงินเดือน (บาท)</p>
          <h4 class="price">{{$_modelData['_salary']}}</h4>
        </div>

      </div>
    </div>

    <div class="col-xs-8">
      <p>ชื่อบริษัทหรือร้านค้า</p>
      <h4>{{$shopName}}</h4>

      <p>รูปแบบงาน</p>
      <h4>{{$_modelData['_employmentTypeName']}}</h4>

    </div>

  </div>

<!--   <div class="row">
    <div class="col-md-6 col-sm-12">
      <div class="item-info">

        <div class="item-info-row">
          <p>เงินเดือน (บาท)</p>
          <h4 class="price">{{$_modelData['_salary']}}</h4>
        </div>

      </div>
    </div> 
  </div> -->

  <!-- <div class="line space-top-bottom-20"></div> -->

  <!-- <div class="row">
    <div class="col-xs-12">
    <p>ชื่อบริษัทหรือร้านค้า</p>
    <h4>{{$shopName}}</h4>
    </div>
  </div>

  <div class="row">
    <div class="list-description col-md-6 col-sm-12">
      <dl class="list-description-item row">
        <dt class="col-sm-4">รูปแบบงาน</dt>
        <dd class="col-sm-8">{{$_modelData['_employmentTypeName']}}</dd>
      </dl>
    </div>
  </div> -->

  <!-- <div class="line space-top-bottom-20"></div> -->

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

  <!-- <div class="line space-top-bottom-20"></div> -->

  @if($hasBranchLocation)
  <h4>สาขาที่กำลังเปิดรับสมัครงานนี้</h4>   
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

  <h4>สมัครงานนี้ได้ที่</h4>

  <div class="text-center space-top-bottom-20">
    @if($personApplyJob)
      <h4 class="sign info">สมัครงานนี้แล้ว</h4>
    @else
    <a href="{{$jobApplyUrl}}">
      <span class="button">สมัครงานนี้ผ่าน CHONBURI SQUARE</span>
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

<script type="text/javascript">
  $(document).ready(function(){
    imageGallery = new ImageGallery(true);
    imageGallery.load({!!$_modelData['Image']!!});

    const map = new Map(false,false,false);
    map.setLocations({!!$branchLocations!!});
  });
</script>

@stop