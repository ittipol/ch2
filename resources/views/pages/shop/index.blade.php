@extends('layouts.blackbox.main')
@section('content')

@include('pages.shop.layouts.header')

<div class="shop-content-wrapper">

  <div class="container">

    @if(!empty($permission['edit']) && $permission['edit'])
    <div class="box">
      <?php 
        echo Form::open([
          'url' => request()->get('shopUrl').'timeline/post',
          // 'id' => 'pinned_message_form',
          'method' => 'post'
        ]);
      ?>
      <div class="box-header">
        <h4 class="box-header-title">
          <img class="icon-before-title" src="/images/icons/edit-blue.png">ข้อความร้านค้า
        </h4>
      </div>

      <div>
        <?php 
          echo Form::textarea('message',null,array(
            'class' => 'timeline-message-input'
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
          <div class="box timeline">
            <div class="box-header">
              <h4 class="box-header-title">
                <div class="primary-title">
                  <span class="post-owner-name">{{$timeline['owner']}}</span>
                  {{$timeline['title']}}
                </div>
                <div class="secondary-title">{{$timeline['createdDate']}}</div>
              </h4>
            </div>

            <div class="line"></div>

            <div class="box-content padding-15">
              <div class="timeline-message">{!!$timeline['message']!!}</div>

              @if(!empty($timeline['relatedData']))
              <a href="{{$timeline['relatedData']['detailUrl']}}" class="timeline-content clearfix">
                <div class="image-tile pull-left">
                  <div class="timeline-content-image" style="background-image:url({{$timeline['relatedData']['image']}});"></div>
                </div>
                <div class="timeline-content-info pull-left">
                  <div class="title">{{$timeline['relatedData']['title']}}</div>
                  <div class="description">{{$timeline['relatedData']['description']}}</div>
                </div>
              </a>
              @endif

            </div>

            <div class="additional-option">
              <div class="dot"></div>
              <div class="dot"></div>
              <div class="dot"></div>
              <div class="additional-option-content">
                <a href="{{$timeline['cancelPinnedUrl']}}">ยกเลิกการตรึงข้อความ</a>
                <a href="{{$timeline['deleteUrl']}}"  data-modal="1" data-modal-title="ต้องการลบข้อความใช่หรือไม่">ลบข้อความ</a>
              </div>
            </div>

          </div>
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

  class Timeline {

    contructor() {

    }

    load() {
      this.bind();
    }

    bind() {

    }

  }

  $(document).ready(function(){

    const pinnedMessage = new Timeline();
    pinnedMessage.load();

  });

</script>

@stop