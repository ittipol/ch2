@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

<div class="top-header-wrapper">
  <div class="top-header">
    <div class="detail-title">
      <h4 class="sub-title">ฟรีแลนซ์</h4>
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

            <a href="{{$experienceDetailUrl}}" class="button wide-button">แสดงประวัติการทำงานฟรีแลนซ์</a>

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

            @if(!empty($profile['Contact']['line']))
            <dl>
              <dt>Line ID</dt>
              <dd>{{$profile['Contact']['line']}}</dd>
            </dl>
            @endif

          </div>

        </div>

      </div>
    </div>
  </div>

  <div class="row">
    <div class="list-description col-md-6 col-sm-12">
      <dl class="list-description-item row">
        <dt class="col-sm-4">ประเภทงานฟรีแลนซ์</dt>
        <dd class="col-sm-8">{{$_modelData['_freelanceType']}}</dd>
      </dl>
    </div>
  </div>

  <div class="row">

    <div class="col-sm-12 margin-section section-border-left">

      <div class="space-top-bottom-10 section-inner">
        
        <h4>รายละเอียดข้อตกลง</h4>
        <div>
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