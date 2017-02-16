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

  <!-- <div class="detail-title">
    <h4 class="sub-title">ประกาศ{{$_modelData['_announcementTypeName']}}</h4>
    <h2 class="title">{{$_modelData['name']}}</h2>
    <div class="tag-group">
      <a class="tag-box">{{$_modelData['_used']}}</a>
      <a class="tag-box">{{$_modelData['_categoryName']}}</a>
      @foreach ($_modelData['Tagging'] as $tagging)
        <a class="tag-box">{{$tagging['_word']}}</a>
      @endforeach
    </div>
  </div> -->

  <h4 class="title-with-icon location-pin">{{$_modelData['Address']['_short_address']}}</h4>

  <div class="image-gallery">

    <div class="row">

      <div class="col-lg-8 col-sm-12">

        <div class="image-gallary-display">
          <div class="image-gallary-display-inner">
            <div class="image-gallary-panel">
              <img id="image_display">
            </div>
          </div>
        </div>

      </div>

      <div class="col-lg-4 col-sm-12">

        @if(!empty($_modelData['Image']))
        <div class="image-gallery-list clearfix">
          <div id="image_gallery_list" class="image-gallery-list clearfix"></div>
        </div>
        <div class="line space-top-bottom-20"></div>
        @endif

        <div class="item-info">

          <div class="item-info-row">
            <p>ราคา{{$_modelData['_announcementTypeName']}}</p>
            <h4 class="price">{{$_modelData['_price']}}</h4>
          </div>

        </div>
          
        <div class="line space-top-bottom-20"></div>

        <div class="item-info">

          <div class="item-info-row">
            <h4 class="title-with-icon phone">{{$_modelData['Contact']['phone_number']}}</h4>
          </div>

          <div class="item-info-row">
            <h4 class="title-with-icon email">{{$_modelData['Contact']['email']}}</h4>
          </div>

          <div class="item-info-row">
            <h4 class="title-with-icon line-app">{{$_modelData['Contact']['line']}}</h4>
          </div>

        </div>
        
      </div>

    </div>

  </div>

  <div class="line space-top-bottom-20"></div>

  <h4><b>รายละเอียด {{$_modelData['name']}}</b></h4>
  <div>
    {!!$_modelData['description']!!}
  </div>

  <div class="line space-top-bottom-20"></div>

  <h4>สินค้าที่คล้ายกัน</h4>
  <p>ไม่พบสินค้าที่คล้ายกัน</p>

</div>

<script type="text/javascript">
  $(document).ready(function(){
    imageGallery = new ImageGallery();
    imageGallery.load({!!$_modelData['Image']!!});
  });
</script>

@stop