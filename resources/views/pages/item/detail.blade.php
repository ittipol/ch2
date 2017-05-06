@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{URL::to('item/board')}}/{{$_modelData['_categoryId']}}" class="btn btn-secondary">{{$_modelData['_categoryName']}}</a>
          <button class="btn btn-secondary additional-option">
            ...
            <div class="additional-option-content">
              <a href="{{URL::to('item/board')}}" class="btn btn-secondary">ไปยังหน้าภาพรวมประกาศ</a>
              <a href="{{URL::to('item/post')}}">เพิ่มประกาศ</a>
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
        <div class="flag">{{$_modelData['_used']}}</div>
      </div>

      <div class="detail-info-section no-margin">
        <h4>รายละเอียด {{$_modelData['name']}}</h4>
        <div class="line"></div> 
        <div class="detail-info description">
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