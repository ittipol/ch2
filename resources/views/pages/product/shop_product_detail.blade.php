@extends('layouts.blackbox.main')
@section('content')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

@include('pages.shop.layouts.fixed_top_nav')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <div class="row">
          <div class="col-xs-11">
            <h2 class="title">{{$_modelData['name']}}</h2>
            @if(!empty($_modelData['Tagging']))
              <div class="tag-group">
                @foreach ($_modelData['Tagging'] as $tagging)
                  <a class="tag-box">{{$tagging['_word']}}</a>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container">

  @if(!empty($categoryPaths))
  <ol class="breadcrumb">
    @foreach($categoryPaths as $path)
    <li class="breadcrumb-item">
      <a href="{{$path['url']}}">{{$path['name']}}</a>
    </li>
    @endforeach
  </ol>
  @endif

</div>

<div class="container detail">

  @if(!empty($_modelData['flag']))
    <div class="flag-wrapper">
      <div class="flag sale-promotion">{{$_modelData['flag']}}</div>
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

    <div class="col-md-6 col-xs-12">
      <div class="item-info">

        <div class="item-info-row">
          <p>ราคา</p>
          @if(!empty($_modelData['promotion']))
            <span class="text-emphasize">{{$_modelData['promotion']['_reduced_price']}}<span class="sub-info-text"> / {{$_modelData['product_unit']}}</span></span>
            <span class="product-price-discount-tag">{{$_modelData['promotion']['percentDiscount']}}</span>
            <h5 class="origin-price">{{$_modelData['_price']}}</h5>
          @else
            <span class="text-emphasize">{{$_modelData['_price']}}<span class="sub-info-text"> / {{$_modelData['product_unit']}}</span></span>
          @endif
        </div>

        <div class="item-info-row">
          <p>จำนวนการสั่งซื้อขั้นต่ำ</p>
          <h4 class="text-emphasize">{{$_modelData['minimum']}}<span class="sub-info-text"> {{$_modelData['product_unit']}} / การสั่งซื้อ</span></h4>
        </div>

        @if($_modelData['shipping_calculate_from'] == 2)
        <div class="item-info-row">
          <p>ค่าจัดส่งสินค้า</p>
          <h4 class="text-emphasize">{{$_modelData['shippingCostText']}}
            @if(!empty($_modelData['shippingCostAppendText']))
              <span class="sub-info-text"> / {{$_modelData['shippingCostAppendText']}}</span>
            @endif
          </h4>
          <p class="text-emphasize-green space-top-10">{{$_modelData['freeShippingMessage']}}</p>
        </div>
        @endif

      </div>
    </div>

    @if($_modelData['active'] && ($_modelData['quantity'] == 0))
      <div class="col-md-6 col-xs-12 quantity-box">
        <h4 class="error-message">{{$_modelData['message_out_of_order']}}</h4>
      </div>
    @elseif($_modelData['active'])

      <div class="col-md-6 col-xs-12 quantity-box">

        @if($_modelData['quantity'] < 11)
          <h5 class="text-warning">เหลือเพียง {{$_modelData['quantity']}} {{$_modelData['product_unit']}}</h5>
        @else
          <h5 class="text-success">มีสินค้า</h5>
        @endif

        <div class="clearfix">
          <input id="product_quantity" class="quantity-text-input pull-left" type="text" name="quantity" value="{{$_modelData['minimum']}}" autocomplete="off" placeholder="จำนวนสินค้าที่สั่งซื้อ" role="number" />
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

  <div class="detail-info-section">
    <h4>รายละเอียด {{$_modelData['name']}}</h4>
    <div class="line"></div> 
    <div class="detail-info">
      {!!$_modelData['description']!!}
    </div>
  </div>

  @if(!empty($_modelData['specifications']))
  <div class="detail-info-section">
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
  @endif

  @if($hasBranchLocation)
  <h4>สาขาที่ขายสินค้า</h4>
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

  @if(!empty($productCatalogs))
  <h4 class="space-top-50">แคตตาล็อกสินค้าที่เกี่ยวข้อง</h4>
  <div class="list-h">
  @foreach($productCatalogs as $data)
    <div class="list-h-item list-h-sm clearfix">

      <a href="{{$data['detailUrl']}}" class="list-image pull-left">
        <img src="/images/icons/tag-white.png">
      </a>

      <div class="col-md-11 col-xs-8">
        <a href="{{$data['detailUrl']}}">
          <h4 class="primary-info single-info">{{$data['name']}}</h4>
        </a>
      </div>

    </div>
  @endforeach
  </div>
  @endif

</div>

<script type="text/javascript">
  $(document).ready(function(){

    const imageGallery = new ImageGallery(true);
    imageGallery.load({!!$_modelData['Image']!!});

    const product = new Product('{{ csrf_token() }}',{{$_modelData['id']}});
    product.load();

    @if($hasBranchLocation)
    const map = new Map(false,false,false);
    map.setLocations({!!$branchLocations!!});
    @endif

  });
</script>

@stop