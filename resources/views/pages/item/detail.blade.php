@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
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
</div>

<div class="detail container">

  <h4 class="title-with-icon location-pin">{{$_modelData['Address']['_short_address']}}</h4>

  @if (Auth::check() && (Session::get('Person.id') == $_modelData['person_id']))
  <div class="text-right">
    <a href="" class="button">ปิดการประกาศ</a>
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

  </div class="row">

    <div class="col-md-4 col-xs-12">

      <div class="item-info">

        <div class="item-info-row">
          <p>ราคา{{$_modelData['_announcementTypeName']}}</p>
          <h4 class="text-emphasize">{{$_modelData['_price']}}</h4>
        </div>

      </div>

      <div class="space-top-bottom-20"></div>

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
          <div class="col-xs-12">
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

      @if (Auth::check() && (Session::get('Person.id') == $_modelData['person_id']))
      <div class="space-top-20">
        <div class="line space-bottom-20"></div>
        <a href="" class="button">ปิดการประกาศ</a>
      </div>
      @endif

    </div>

    <div class="col-md-8 col-xs-12">

      <div class="detail-info-section no-margin">
        <h4>รายละเอียด {{$_modelData['name']}}</h4>
        <div class="line"></div> 
        <div class="detail-info">
          {!!$_modelData['description']!!}
        </div>
      </div>

    </div>

  <div>

  </div>

  <!-- <div class="row">

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

  </div> -->

  <!-- <div class="detail-info-section">
    <h4>รายละเอียด {{$_modelData['name']}}</h4>
    <div class="line"></div> 
    <div class="detail-info">
      {!!$_modelData['description']!!}
    </div>
  </div> -->

</div>

<script type="text/javascript">
  $(document).ready(function(){
    imageGallery = new ImageGallery(true);
    imageGallery.load({!!$_modelData['Image']!!});
  });
</script>

@stop