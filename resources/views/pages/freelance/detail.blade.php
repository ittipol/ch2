@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{URL::to('freelance/board')}}" class="btn btn-secondary">กลับไปหน้าภาพรวมงานฟรีแลนซ์</a>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h4 class="sub-title">งานฟรีแลนซ์</h4>
        <h2 class="title">{{$_modelData['name']}}</h2>
        <div class="tag-group">
          <a class="tag-box">{{$_modelData['_freelanceType']}}</a>
          @foreach ($_modelData['Tagging'] as $tagging)
            <a class="tag-box">{{$tagging['_word']}}</a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

<div class="detail container">

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

  <div class="content-box">
    <div class="content-box-inner">
      <div class="row">

        <div class="col-md-6 col-sm-12">
          <div class="content-box-panel overlay-bg">
            <h5>ฟรีแลนซ์</h5>

            <div class="row">

              <div class="col-sm-12">
                <div class="image-frame elem-center">
                  @if(!empty($profileImageUrl))
                  <div class="content-box-main-image" style="background-image:url({{$profileImageUrl}});"></div>
                  @endif
                </div>
              </div>

              <div class="col-sm-12">
                <div class="profile-info text-center space-top-20">
                  <h3><a href="{{$experienceDetailUrl}}">{{$profile['name']}}</a></h3>
                </div>
              </div>

            </div>

            <div class="line space-top-bottom-20"></div>

            <a href="{{$experienceDetailUrl}}" class="button wide-button">แสดงประวัติการทำงาน</a>

          </div>
        </div>

        <div class="col-md-6 col-sm-12">

          <div class="profile-contact-info">

            <h4>ติดต่อฟรีแลนซ์</h4>
            <div class="line space-top-bottom-10"></div>

            @if(!empty($profile['Contact']['phone_number']))
            <dl>
              <dt>หมายเลขโทรศัพท์</dt>
              <dd>{{$profile['Contact']['phone_number']}}</dd>
            </dl>
            @endif

            @if(!empty($profile['Contact']['email']))
            <dl>
              <dt>อีเมล</dt>
              <dd>{{$profile['Contact']['email']}}</dd>
            </dl>
            @endif

          </div>

        </div>

      </div>
    </div>
  </div>

  <div class="row">

    <div class="col-md-4 col-xs-12">

      <div class="item-info">

        <div class="item-info-row">
          <p>อัตราค่าจ้างเริ่มต้น</p>
          <h4 class="text-emphasize">{{$_modelData['defaultWage']}}</h4>
        </div>

        <div class="item-info-row">
          <p>ประเภทงานฟรีแลนซ์</p>
          <h4>{{$_modelData['_freelanceType']}}</h4>
        </div>

      </div>

    </div>

    <div class="col-md-8 col-xs-12">

      <div class="detail-info-section no-margin">
        <h4>รายละเอียดข้อตกลง</h4>
        <div class="line"></div> 
        <div class="detail-info">
          {!!$_modelData['description']!!}
        </div>
      </div>

    </div>

  </div>

</div>

<script type="text/javascript">
  $(document).ready(function(){
    imageGallery = new ImageGallery(true);
    imageGallery.load({!!$_modelData['Image']!!});
  });
</script>

@stop