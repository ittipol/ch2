@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.header')
@include('pages.shop.layouts.main-nav')

<div class="shop-content-wrapper">

  <div class="container">

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
          <img class="icon-before-title" src="/images/icons/tag-blue.png">สินค้า
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
        <div>
          <h3>ยังไม่มีสินค้า</h3>
        </div>
      </div>

      @endif

    </div>
    

    <div class="box">
      <div class="box-header">
        <h4 class="box-header-title">
          <img class="icon-before-title" src="/images/icons/tag-blue.png">แคตตาล็อกสินค้า
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
        <img class="sm" src="/images/icons/tag-blue.png">
        <div>
          <h3>ยังไม่มีแคตตาล็อกสินค้า</h3>
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
          const notificationBottom = new NotificationBottom('ไม่พบข้อความที่ต้องการโพสต์','กรุณากรอกข้อความ','error');
          notificationBottom.load();
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