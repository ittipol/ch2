@extends('layouts.blackbox.main')
@section('content')

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUdwm6VWiEE-1ZrVgY3bh1sNX21_deHZw&libraries=places" type="text/javascript"></script> -->

<div class="container">

  @if(!empty($categoryPaths))
  <ol class="breadcrumb space-top-20 space-bottom-10">
    @foreach($categoryPaths as $path)
    <li class="breadcrumb-item">
      <a href="{{$path['url']}}">{{$path['name']}}</a>
    </li>
    @endforeach
  </ol>
  @endif
  <h3 class="title">{{$_modelData['name']}}</h3>
  <div class="line"></div>

</div>

<div class="container detail">

  <div class="row">

    <div class="image-gallery space-top-30 col-sm-8 col-xs-12">

      @if(!empty($_modelData['flag']))
        <div class="flag-wrapper">
          <div class="flag sale-promotion">{{$_modelData['flag']}}</div>
        </div>
      @endif

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

    <div class="col-sm-4 col-xs-12 space-top-30">

      <div class="item-info">

        <div class="item-info-row">
          <p>ราคา</p>
          @if(!empty($_modelData['promotion']))
            <span class="text-emphasize">{{$_modelData['promotion']['_reduced_price']}}<span class="sub-info-text"> / {{$_modelData['product_unit']}}</span></span>
            <span class="product-price-discount-tag">{{$_modelData['promotion']['percentDiscount']}}</span>
            <h5 id="_price" class="origin-price">{{$_modelData['_price']}}</h5>
          @else
            <span class="text-emphasize">
              <span id="_price">{{$_modelData['_price']}}</span>
              <span class="sub-info-text"> / {{$_modelData['product_unit']}}</span>
            </span>
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

      @if(!empty($productOptionValues))
      <div class="product-option-value">
        @foreach($productOptionValues as $productOptionValue)
          <h5>{{$productOptionValue['name']}}</h5>
          @foreach($productOptionValue['options'] as $value)
            <label>
              <?php
                echo Form::radio('product-option-value-'.$productOptionValue['id'], $value['id'], null, array(
                  'class' => 'product-option-rdobox',
                  'data-price' => $value['realPrice'],
                  'data-quantity-text' => $value['quantityText']
                ));
              ?>
              <div class="product-option-value-box product-option-display-{{$value['display_type']}}">
                @if(($value['display_type'] == 2) || ($value['display_type'] == 3))
                <div class="image-tile">
                  <div class="product-option-value-image" style="background-image:url({{$value['imageUrl']}});"></div>
                </div>
                @endif
                @if(($value['display_type'] == 1) || ($value['display_type'] == 3))
                <div class="product-option-value-name">{{$value['name']}}</div>
                @endif
              </div>
            </label>
          @endforeach
        @endforeach
      </div>
      @endif

      <div class="quantity-box">
      @if($_modelData['active'])

        <h5 id="_quantity_text">{{$_modelData['quantityText']}}</h5>
        <div class="clearfix">
          <input id="product_quantity" class="quantity-text-input pull-left" type="text" name="quantity" value="{{$_modelData['minimum']}}" autocomplete="off" placeholder="จำนวนสินค้าที่สั่งซื้อ" role="number" />
          <a id="add_to_cart_button" class="button add-to-cart-button pull-right">ใส่ตระกร้า</a>
        </div>

      @else
        <h4 class="error-message">ยังไม่เปิดขายสินค้า</h4>
        <p>สินค้านี้ถูกปิดการสั่งซื้อชั่วคราวจากผู้ขาย</p>
      @endif
      </div>

    </div>

  </div>

  <div class="row">
    
    <div class="col-sm-8 col-xs-12">

      <div class="detail-info-section">
        <h4>รายละเอียดสินค้า</h4>
        <div class="line"></div> 
        <div class="detail-info description">
          {!!$_modelData['description']!!}
        </div>
      </div>

      @if(!empty($_modelData['specifications']))
      <div class="detail-info-section">
        <h4>ข้อมูลจำเพาะ</h4>  
        <div class="line"></div> 
        <table class="table table-striped">
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

    </div>

    <div class="col-sm-4 col-xs-12">
      
      <div class="content-box content-box-bg" style="background-image:url({{$shopCoverUrl}})">
        <div class="content-box-inner">
          <div class="row">

            <div class="col-md-10 col-xs-12">
              <div class="content-box-panel overlay-bg">
                <div>
                  <h5>บริษัทหรือร้านค้าที่ขายสินค้านี้</h5>
                  <h3>
                    <a href="{{$shopUrl}}">{{$shop['name']}}</a>
                  </h3>
                  <div class="line space-top-bottom-20"></div>
                  <p>{{$shop['_short_description']}}</p>
                </div>

                <a href="{{$shopUrl}}" class="button wide-button">ไปยังบริษัทหรือร้านค้า</a>
              </div>
            </div>

          </div>
        </div>
      </div>

      @if(!empty($productCatalogs))
      <h4 class="article-title color-teal space-top-50">แคตตาล็อกสินค้าที่เกี่ยวข้อง</h4>
      <div class="line"></div> 
      <div class="list-h">
      @foreach($productCatalogs as $data)
        <div class="list-h-item list-h-sm no-border clearfix">

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

  </div>

  @if($hasBranchLocation)
  <h4>สาขาที่ขายสินค้า</h4>   
  <div class="line"></div>
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

  <div>
    <h4>รีวิวจากลูกค้า</h4>
    <div class="line"></div>

    <div class="row">
      <div class="col-md-6 col-xs-12">

        <div class="form-section">

          <div class="form-row">
            <?php
              echo Form::label('score', 'คะแนนสินค้าชิ้นนี้', array(
                'class' => 'required'
              ));
            ?>
            <label class="choice-box review-score-box">
              <?php
                echo Form::radio('review_score', null);
              ?>
              <div class="inner">1</div>
            </label>

            <label class="choice-box review-score-box">
              <?php
                echo Form::radio('review_score', null);
              ?>
              <div class="inner">2</div>
            </label>

            <label class="choice-box review-score-box">
              <?php
                echo Form::radio('review_score', null);
              ?>
              <div class="inner">3</div>
            </label>

            <label class="choice-box review-score-box">
              <?php
                echo Form::radio('review_score', null);
              ?>
              <div class="inner">4</div>
            </label>

            <label class="choice-box review-score-box">
              <?php
                echo Form::radio('review_score', null);
              ?>
              <div class="inner">5</div>
            </label>
          </div>

          <div class="form-row">
            <?php 
              echo Form::label('title', 'หัวข้อรีวิว');
              echo Form::text('title', null, array(
                'placeholder' => 'ชื่อสินค้า',
                'autocomplete' => 'off'
              ));
            ?>
          </div>

          <div class="form-row">
            <?php 
              echo Form::label('description', 'รายละเอียดรีวิว', array(
                'class' => 'required'
              ));
              echo Form::textarea('description');
            ?>
          </div>

          <?php
            echo Form::submit('ส่งรีวิว', array(
              'class' => 'button'
            ));
          ?>

        </div>

      </div>
    </div>

    <div class="row space-top-50">

      <div class="col-md-4 col-xs-12">

        <div class="rating-wrapper">
          <h4>คะแนนเฉลี่ย</h4>
          <div class="space-bottom-20">
            <span class="avg-review-score">4.9</span>
            <span> / 5 คะแนน</span>
          </div>

          <div class="rating-bar-list">
            <span class="rating-bar-label">5 คะแนน</span>
            <span class="rating-bar-item">
              <div class="rating-bar-box score-5">
                <div class="rating-count-text">10</div>
              </div>
            </span>
          </div>

          <div class="rating-bar-list">
            <span class="rating-bar-label">4 คะแนน</span>
            <span class="rating-bar-item">
              <div class="rating-bar-box score-4">
                <div class="rating-count-text">10</div>
              </div>
            </span>
          </div>

          <div class="rating-bar-list">
            <span class="rating-bar-label">3 คะแนน</span>
            <span class="rating-bar-item">
              <div class="rating-bar-box score-3">
                <div class="rating-count-text">10</div>
              </div>
            </span>
          </div>

          <div class="rating-bar-list">
            <span class="rating-bar-label">2 คะแนน</span>
            <span class="rating-bar-item">
              <div class="rating-bar-box score-2">
                <div class="rating-count-text">10</div>
              </div>
            </span>
          </div>

          <div class="rating-bar-list">
            <span class="rating-bar-label">1 คะแนน</span>
            <span class="rating-bar-item">
              <div class="rating-bar-box score-1">
                <div class="rating-count-text">10</div>
              </div>
            </span>
          </div>

        </div>

      </div>

      <div class="col-md-6 col-xs-12">

        <div id="review_wrapper" class="review-wrapper space-top-50">

          <div class="review-content">
            <div class="review-by">xxx yyy</div>
            <div class="review-rating-score">5 คะแนน</div>
            <div class="review-description">
              <span class="review-title">Title</span>
              Lorem Ipsum คือ เนื้อหาจำลองแบบเรียบๆ ที่ใช้กันในธุรกิจงานพิมพ์หรืองานเรียงพิมพ์ มันได้กลายมาเป็นเนื้อหาจำลองมาตรฐานของธุรกิจดังกล่าวมาตั้งแต่ศตวรรษที่ 16 เมื่อเครื่องพิมพ์โนเนมเครื่องหนึ่งนำรางตัวพิมพ์มาสลับสับตำแหน่งตัวอักษรเพื่อทำหนังสือตัวอย่าง Lorem Ipsum อยู่ยงคงกระพันมาไม่ใช่แค่เพียงห้าศตวรรษ แต่อยู่มาจนถึงยุคที่พลิกโฉมเข้าสู่งานเรียงพิมพ์ด้วยวิธีทางอิเล็กทรอนิกส์ และยังคงสภาพเดิมไว้อย่างไม่มีการเปลี่ยนแปลง มันได้รับความนิยมมากขึ้นในยุค ค.ศ. 1960 เมื่อแผ่น Letraset วางจำหน่ายโดยมีข้อความบนนั้นเป็น Lorem Ipsum และล่าสุดกว่านั้น คือเมื่อซอฟท์แวร์การทำสื่อสิ่งพิมพ์ (Desktop Publishing) อย่าง Aldus PageMaker ได้รวมเอา Lorem Ipsum เวอร์ชั่นต่างๆ เข้าไว้ในซอฟท์แวร์ด้วย
            </div>
          </div>

        </div>

      </div>

    </div>

  </div>

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