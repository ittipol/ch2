@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

@include('pages.shop.layouts.fixed_top_nav')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h4 class="sub-title">ประกาศงาน</h4>
        <h2 class="title">{{$_modelData['name']}}</h2>
        <div class="tag-group">
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

  @if($alreadyApply)
    
    @if(($personApplyJob['job_applying_status_id'] == 4) || ($personApplyJob['job_applying_status_id'] == 5))
      <div class="secondary-message-box space-bottom-30">
        <div class="secondary-message-box-inner">
          <div class="text-center">
            <h3>คุณเคยสมัครงานนี้แล้ว</h3>
            <p>ดูเหมือนว่าผลลัพธ์การสมัครอาจจยังไม่เป็นตามที่คุณต้องกการ คุณต้องกการสมัครงานนี้อีกครั้งหรือไม่?</p>
          </div>
        </div>
        <div class="message-box-button-group clearfix">
          <div class="flat-button">
            <a href="{{$jobApplyUrl}}" class="button">
              สมัครงานนี้
            </a>
          </div>
        </div>
      </div>
    @elseif($personApplyJob['job_applying_status_id'] == 3)
      <h4 class="sign success wide space-bottom-20">ยินดีด้วยคุณผ่านการสมัครงานนี้แล้ว</h4>
      <a class="button pull-right" href="{{$jobApplyUrl}}">
        <img src="/images/icons/edit-white.png">
        ไปยังหน้าสมัครงาน
      </a>
    @else
      <h4 class="sign info wide space-bottom-20">สมัครงานนี้แล้ว</h4>
    @endif

  @else
    <div class="row space-bottom-30">
      <a class="button pull-right" href="{{$jobApplyUrl}}">
        <img src="/images/icons/edit-white.png">
        สมัครงานนี้
      </a>
    </div>
  @endif

  <div class="image-gallery">

    <div class="row">

      <div class="col-xs-12 image-gallary-display">

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
          <p>อัตราค่าจ้าง (บาท)</p>
          <span class="text-emphasize">{{$_modelData['_wage']}}<span class="sub-info-text"> / {{$_modelData['_wageType']}}</span></span>
        </div>

      </div>
    </div>

    <div class="col-xs-8">

      <p>รูปแบบการจ้างงาน</p>
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
      @if($alreadyApply)
        
        @if(($personApplyJob['job_applying_status_id'] == 4) || ($personApplyJob['job_applying_status_id'] == 5))
          <div class="secondary-message-box space-bottom-30">
            <div class="secondary-message-box-inner">
              <div class="text-center">
                <h3>คุณเคยสมัครงานนี้แล้ว</h3>
                <p>ดูเหมือนว่าผลลัพธ์การสมัครอาจจยังไม่เป็นตามที่คุณต้องกการ คุณต้องกการสมัครงานนี้อีกครั้งหรือไม่?</p>
              </div>
            </div>
            <div class="message-box-button-group clearfix">
              <div class="flat-button">
                <a href="{{$jobApplyUrl}}" class="button">
                  สมัครงานนี้
                </a>
              </div>
            </div>
          </div>
        @elseif($personApplyJob['job_applying_status_id'] == 3)
          <h4 class="sign success wide space-bottom-20">ยินดีด้วยคุณผ่านการสมัครงานนี้แล้ว</h4>
          <a class="button pull-right" href="{{$jobApplyUrl}}">
            <img src="/images/icons/edit-white.png">
            ไปยังหน้าสมัครงาน
          </a>
        @else
          <h4 class="sign info wide space-bottom-20">สมัครงานนี้แล้ว</h4>
        @endif

      @else
      <a class="button wide-button" href="{{$jobApplyUrl}}">
        <img src="/images/icons/edit-white.png">
        สมัครงานนี้
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