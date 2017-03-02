@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">{{$_modelData['name']}}</h2>
        <div class="tag-group">
          @if(!empty($_modelData['_categoryName']) && ($_modelData['_categoryName'] != '-'))
          <a class="tag-box">{{$_modelData['_categoryName']}}</a>
          @endif
          @foreach ($_modelData['Tagging'] as $tagging)
            <a class="tag-box">{{$tagging['_word']}}</a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

<div class="detail container">

  @if(!empty($_modelData['_categoryPaths']))
  <ol class="breadcrumb">
    @foreach($_modelData['_categoryPaths'] as $path)
    <li class="breadcrumb-item">{{$path['name']}}</li>
    @endforeach
  </ol>
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

    <div class="col-md-6 col-xs-12">
      <div class="item-info">

        <div class="item-info-row">
          <p>ราคา</p>
          <h4 class="price">{{$_modelData['_price']}}<span class="sub-info-text"> / {{$_modelData['product_unit']}}</span></h4>
        </div>

        <div class="item-info-row">
          <p>จำนวนการสั่งซื้อขั้นต่ำ</p>
          <h4 class="price">{{$_modelData['minimum']}}<span class="sub-info-text"> {{$_modelData['product_unit']}} / การสั่งซื้อ</span></h4>
        </div>

      </div>
    </div>

    @if($_modelData['active'])
      <div class="col-md-6 col-xs-12 quantity-box">
        <div class="clearfix">
          <input id="product_quantity" class="quantity-text-input pull-left" type="text" name="quantity" value="{{$_modelData['minimum']}}" placeholder="จำนวนสินค้าที่สั่งซื้อ" role="number" />
          <a id="add_to_cart_button" class="button add-to-cart-button pull-right">ใส่ตระกร้า</a>
        </div>
      </div>
    @else
      <div class="col-md-6 col-xs-12 quantity-box">
        <h4 class="error-message">ยังไม่เปิดขายสินค้า</h4>
        <p>สินค้านี้ถูกปิดการสั่งซื้อชั่วคราวจากผู้ขาย</p>
      </div>
    @endif
    
  </div>

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

  @if(!empty($_modelData['specifications']))
  <div class="row">
    <div class="col-xs-12">

      <h4>ข้อมูลจำเพาะของ {{$_modelData['name']}}</h4>  
      <table class="table table-striped  ">
        <tbody>
          @foreach($_modelData['specifications'] as $specification)
            <tr>
              <td>{{$specification['title']}}</td>
              <td>{{$specification['value']}}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

    </div>
  </div>
  @endif

</div>

<script type="text/javascript">
  $(document).ready(function(){

    const imageGallery = new ImageGallery(true);
    imageGallery.load({!!$_modelData['Image']!!});

    const cart = new Cart('{{ csrf_token() }}',{{$_modelData['id']}},{{$_modelData['minimum']}});
    cart.load();

  });
</script>

@stop