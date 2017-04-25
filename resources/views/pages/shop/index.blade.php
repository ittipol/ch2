@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.top_nav')
@include('pages.shop.layouts.header')

<div class="shop-content-wrapper">

  <div class="container">

    <div class="row">

      <div class="col-sm-3 col-sm-12">
        
        <div class="box">
          <div>สินค้า</div>
          <div>งาน</div>
          <div>โฆษณา</div>
          <div class="line"></div>
          <div>แคตตาล็อกสินค้า</div>
        </div>

      </div>

      <div class="col-sm-9 col-sm-12">


      </div>

    </div>

    @if(!empty($permission['edit']) && $permission['edit'])
    <div class="box">
      <?php 
        echo Form::open([
          'url' => request()->get('shopUrl').'pinned_message/add',
          // 'id' => 'pinned_message_form',
          'method' => 'post'
        ]);
      ?>
      <div class="box-header">
        <h4 class="box-header-title">
          <img class="icon-before-title" src="/images/icons/edit-blue.png">ปักหมุดข้อความ
        </h4>
      </div>
      <textarea class="pin-message-input"></textarea>
      <div class="box-footer text-right">
        <?php
          echo Form::submit('โพสต์' , array(
            'class' => 'button'
          ));
        ?>
      </div>
      <?php
        echo Form::close();
      ?>
    </div>
    @endif

    <div class="box">
      <div class="box-header">
        <h4 class="box-header-title">
          <img class="icon-before-title" src="/images/icons/tag-blue.png">สินค้า
        </h4>
      </div>

      <div class="box-content padding-15">

        <div class="row">

          @foreach($products as $data)

          <div class="col-lg-4 col-sm-4 col-xs-12">
            <div class="card">

              @if(!empty($data['flag']))
              <div class="flag-wrapper">
                <div class="flag sale-promotion">{{$data['flag']}}</div>
              </div>
              @endif
              
              <div class="image-tile">
                <a href="{{$data['detailUrl']}}">
                  <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
                </a>
              </div>
              
              <div class="card-info">
                <a href="{{$data['detailUrl']}}">
                  <div class="card-title">{{$data['name']}}</div>
                </a>
                <div class="card-sub-info">

                  <div class="card-sub-info-row product-price-section">
                    @if(!empty($data['promotion']))
                      <span class="product-price">{{$data['promotion']['_reduced_price']}}</span>
                      <span class="product-price-discount-tag">{{$data['promotion']['percentDiscount']}}</span>
                      <h5 class="origin-price">{{$data['_price']}}</h5>
                    @else
                      <span class="product-price">{{$data['_price']}}</span>
                    @endif
                  </div>

                </div>
              </div>

              <div class="button-group">

                <a href="{{$data['detailUrl']}}">
                  <div class="button wide-button">รายละเอียดสินค้า</div>
                </a>
              
              </div>

            </div>
          </div>

          @endforeach

        </div>

      </div>

    </div>
    <a href="{{request()->get('shopUrl')}}product" class="button wide-button">แสดงสินค้าทั้งหมด</a>

    <div class="box">
      <div class="box-header">
        <h4 class="box-header-title">
          <img class="icon-before-title" src="/images/icons/tag-blue.png">แคตตาล็อกสินค้า
        </h4>
      </div>

      <div class="box-content padding-15">
        @if(!empty($productCatalogs))
        <div class="row">

          @foreach($productCatalogs as $data)

          <div class="col-md-6 col-xs-12">
            <div class="card">

              <div class="image-tile cover">
                <a href="{{$data['detailUrl']}}">
                  <div class="card-image" style="background-image:url({{$data['_imageUrl']}});"></div>
                </a>
              </div>
              
              <div class="card-info">
                <a href="{{$data['detailUrl']}}">
                  <div class="card-title">{{$data['name']}}</div>
                </a>
              </div>

              <div class="button-group">

                <a href="{{$data['detailUrl']}}">
                  <div class="button wide-button">แสดงสินค้าในแคตตาล็อก</div>
                </a>
              
              </div>
              
            </div>
          </div>

          @endforeach

        </div>
        @endif
      </div>

    </div>
    <a href="{{request()->get('shopUrl')}}product_catalog" class="button wide-button">แสดงแคตตาล็อกสินค้าทั้งหมด</a>

  </div>

</div>

<script type="text/javascript">

  class PinnedMessage {

    contructor() {

    }

    load() {
      this.bind();
    }

    bind() {

    }

  }

  $(document).ready(function(){

    const pinnedMessage = new PinnedMessage();
    pinnedMessage.load();

  });

</script>

@stop