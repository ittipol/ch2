@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.header')
@include('pages.shop.layouts.main-nav')

<div class="shop-content-wrapper">

  <div class="container">

    @if(!empty($permission))

      <div class="box">

        <div class="tile-nav-group padding-15 clearfix">

          <h4 class="padding-left-right-15">จัดการ...</h4>
          <div class="line"></div>

          <a href="{{request()->get('shopUrl')}}manage/product" class="tile-flat">
            <div class="tile-nav-image">
              <img src="/images/icons/tag-white.png">
            </div>
            <div class="tile-nav-info">
              <h4 class="tile-nav-title">สินค้า</h4>
            </div>
          </a>

          <a href="{{request()->get('shopUrl')}}manage/job" class="tile-flat">
            <div class="tile-nav-image">
              <img src="/images/icons/document-white.png">
            </div>
            <div class="tile-nav-info">
              <h4 class="tile-nav-title">งาน</h4>
            </div>
          </a>

          <a href="{{request()->get('shopUrl')}}manage/advertising" class="tile-flat">
            <div class="tile-nav-image">
              <img src="/images/icons/megaphone-white.png">
            </div>
            <div class="tile-nav-info">
              <h4 class="tile-nav-title">โฆษณา</h4>
            </div>
          </a>

          <a href="{{request()->get('shopUrl')}}manage/product_catalog" class="tile-flat">
            <div class="tile-nav-image">
              <img src="/images/icons/book-white.png">
            </div>
            <div class="tile-nav-info">
              <h4 class="tile-nav-title">แคตตาล็อกสินค้า</h4>
            </div>
          </a>

        </div>

      </div>

    @endif

    @if(!empty($permission['edit']) && $permission['edit'])
    <div class="box">
      <?php 
        echo Form::open([
          'url' => request()->get('shopUrl').'timeline/post',
          'id' => 'timeline_post_form',
          'method' => 'post'
        ]);
      ?>
      <div class="box-header">
        <h4 class="box-header-title">
          <img class="icon-before-title" src="/images/icons/edit-blue.png">ข้อความร้านค้า
          <div class="secondary-title">ระบุรายละเอียดธุรกิจของคุณ เพื่อให้คนเข้าใจสิ่งที่คุณนำเสนอ</div>
        </h4>
      </div>

      <div>
        <?php 
          echo Form::textarea('message',null,array(
            'id' => 'timeline_post_input'
          ));
        ?>
      </div>

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

    <div id="timelines_panel">

      @if(!empty($pinnedMessages))

        @foreach($pinnedMessages as $timeline)
          @include('pages.shop.layouts.timeline_post_box')
        @endforeach

        <div class="line space-top-bottom-100"></div>

      @endif

    </div>

    <div class="box">
      <div class="box-header">
        <h4 class="box-header-title">
          <img class="icon-before-title" src="/images/icons/tag-blue.png">
          สินค้า
        </h4>
      </div>

      @if(!empty($products))

      <div class="box-content padding-left-right-15">

        <div class="row">

          @foreach($products as $data)

          <div class="col-sm-3 col-xs-6">
            <div class="card sm">

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

            </div>
          </div>

          @endforeach

        </div>

        <div class="text-right space-bottom-20">
          <a href="{{request()->get('shopUrl')}}product" class="flat-button">แสดงทั้งหมด</a>
        </div>

      </div>

      @else

      <div class="list-empty-message text-center space-top-bottom-20">
        <img class="sm" src="/images/icons/tag-blue.png">
        <div class="space-top-20">
          <h4>ยังไม่มีสินค้า</h4>
        </div>
      </div>

      @endif

    </div>
    

    <div class="box">
      <div class="box-header">
        <h4 class="box-header-title">
          <img class="icon-before-title" src="/images/icons/book-blue.png">แคตตาล็อกสินค้า
        </h4>
      </div>

      @if(!empty($productCatalogs))

      <div class="box-content padding-left-right-15">

        <div class="row">

          @foreach($productCatalogs as $data)

          <div class="col-md-3 col-xs-12">
            <div class="card sm">

              <div class="image-tile">
                <a href="{{$data['detailUrl']}}">
                  <div class="card-image cover" style="background-image:url({{$data['_imageUrl']}});"></div>
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

        <div class="text-right space-bottom-20">
          <a href="{{request()->get('shopUrl')}}product_catalog" class="flat-button">แสดงทั้งหมด</a>
        </div>

      </div>
      
      @else

      <div class="list-empty-message text-center space-top-bottom-20">
        <img class="sm" src="/images/icons/book-blue.png">
        <div class="space-top-20">
          <h4>ยังไม่มีแคตตาล็อกสินค้า</h4>
        </div>
      </div>

      @endif

    </div>

  </div>

</div>

<script type="text/javascript">

  class Timeline {

    contructor() {}

    load() {
      this.bind();
    }

    bind() {

      $('#timeline_post_form').on('submit',function(){

        let message = document.getElementById('timeline_post_input').value.trim();

        if(message.length == 0) {
          const notificationBottom = new NotificationBottom();
          notificationBottom.setTitle('ไม่พบข้อความที่ต้องการโพสต์');
          notificationBottom.setDesc('กรุณากรอกข้อความ');
          notificationBottom.setType('error');
          notificationBottom.display();
          return false;
        }

      });

    }

    // loadPinnedMessage() {

    //   let request = $.ajax({
    //     url: "/api/get_pinned_post/",
    //     type: "get",
    //     dataType:'json'
    //   });

    //   request.done(function (response, textStatus, jqXHR){

    //   });

    // }

  }

  $(document).ready(function(){

    const timeline = new Timeline();
    timeline.load();

  });

</script>

@stop