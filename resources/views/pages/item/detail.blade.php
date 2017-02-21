@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper">
  <div class="top-header">
    <div class="detail-title">
      <h4 class="sub-title">ประกาศ{{$_modelData['_announcementTypeName']}}</h4>
      <h2 class="title">{{$_modelData['name']}}</h2>
      <div class="tag-group">
        <a class="tag-box">{{$_modelData['_used']}}</a>
        <a class="tag-box">{{$_modelData['_categoryName']}}</a>
        @foreach ($_modelData['Tagging'] as $tagging)
          <a class="tag-box">{{$tagging['_word']}}</a>
        @endforeach
      </div>
    </div>
  </div>
</div>

<div class="detail container">

  <h4 class="title-with-icon location-pin">{{$_modelData['Address']['_short_address']}}</h4>

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
          <p>ราคา{{$_modelData['_announcementTypeName']}}</p>
          <h4 class="price">{{$_modelData['_price']}}</h4>
        </div>

      </div>
    </div>

    <div class="col-xs-8">
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

  <!-- <div class="line space-top-bottom-20"></div> -->

  <!-- <div class="row">

    <div class="col-xs-12">
      <div class="item-info">

        <div class="item-info-row">
          <p>ราคา{{$_modelData['_announcementTypeName']}}</p>
          <h4 class="price">{{$_modelData['_price']}}</h4>
        </div>

      </div>
    </div> 

  </div> -->

  <!-- <div class="line space-top-bottom-20"></div> -->

  <!-- <div class="row">

    <div class="col-xs-12">

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

  </div> -->

  <!-- <div class="line space-top-bottom-20"></div> -->

  <div class="row">

    <div class="col-sm-12 margin-section section-border-left">

      <div class="space-top-bottom-10 section-inner">
        
        <h4>รายละเอียด {{$_modelData['name']}}</h4>  
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