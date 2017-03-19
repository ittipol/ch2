@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">เลขที่การสั่งซื้อ {{$order['id']}}</h2>
      </div>
    </div>
  </div>
</div>

<div class="container">

  <div class="row">

    <div class="col-sm-12">
      
      <div class="order-progress-bar">
        <div class="status"></div>
      </div>

      <div class="order-status clearfix">
        @foreach($orderStatuses as $orderStatus)
          <div class="order-status-box {{$orderStatus['alias']}} {{$orderStatus['position']}}">

            <div class="image">
              <div class="status-image"></div>
            </div>
            <h5>{{$orderStatus['name']}}</h5>
            
          </div>
        @endforeach
      </div>

      <div class="line space-top-bottom-20"></div>

    </div>

  </div>

  <div class="row">

    <div class="col-md-4 col-sm-12">

      <div class="detail-group">
        <h4>รายละเอียดการสั่งซื้อ</h4>
        <!-- <div class="line"></div> -->
        <div class="detail-group-info-section">

          <div class="detail-group-info">
            <h5 class="title">ชื้อบริษัทหรือร้านค้าที่ขายสินค้า</h5>
            <p>{{$order['shopName']}}</p>
          </div>

          <div class="detail-group-info">
            <h5 class="title">ชื้อผู้ซื้อ</h5>
            <p>{{$order['person_name']}}</p>
          </div>

          <div class="detail-group-info">
            <h5 class="title">วันที่สั่งซื้อ</h5>
            <p>{{$order['orderedDate']}}</p>
          </div>

          <div class="detail-group-info">
            <h5 class="title">สถานะการสั่งซื้อ</h5>
            <p>{{$order['orderStatusName']}}</p>
          </div>

        </div>
      </div>

    </div>

    <div class="col-md-8 col-sm-12">

      <div class="content-box content-box-bg no-margin" style="background-image:url({{$shopCoverUrl}})">
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

    </div>

  </div>

  <div class="row">

    <div class="col-md-4 col-sm-12 space-top-30">
      
      <div class="detail-info-section no-margin">
        <h4>ที่อยู่สำหรับการจัดส่ง</h4>
        <div class="line"></div> 
        <div class="detail-info">
          {{$order['shipping_address']}}
        </div>
      </div>

    </div>

    <div class="col-md-8 col-sm-12 space-top-30">

      <div class="detail-info-section no-margin">
          <h4>รายละเอียดและวิธีการชำระเงิน</h4>
          <div class="line"></div> 
          <div class="detail-info">
            ยังไม่มีรายละเอียดและวิธีการชำระเงิน
          </div>
      </div>

    </div>
  </div>

  <div class="cart space-top-30">

    <div class="product-list-table">

      <h4>รายการสินค้า</h4>
      <div class="line"></div>

      <div class="product-list-wrapper">
        @foreach($orderProducts as $product)
        <div class="product-list-table-row">

          @if(!empty($product['hasError']))
            <p class="product-error-message">
              {{$product['errorMessage']}}
            </p>
          @endif

          <div class="product-list-box clearfix">

            <div class="product-image pull-left">
              <a href="{{$product['productDetailUrl']}}">
                <img src="{{$product['imageUrl']}}">
              </a>
            </div>

            <div class="col-md-10 col-xs-8 product-info">

              <div class="col-md-4 col-xs-12 product-info-container">
                <a href="{{$product['productDetailUrl']}}">
                  <h4 class="product-text">{{$product['product_name']}}</h4>
                </a>
              </div>

              <div class="col-md-3 col-xs-12 product-info-container">
                <div class="product-text">
                  <h5>ราคาสินค้า</h5>
                  <h4>{{$product['_price']}} x {{$product['quantity']}}</h4>
                </div>
              </div>

              <div class="col-md-3 col-xs-12 product-info-container">
                <div class="product-text">
                  <h5>ค่าจัดส่งสินค้า</h5>
                  <h4>{{$product['shippingCostText']}}</h4>
                </div>
              </div>

              <div class="col-md-2 col-xs-12 product-info-container">
                <div class="product-text">
                  <h5>มูลค่าสินค้า</h5>
                  <h4>{{$product['_total']}}</h4>
                </div>
              </div>

            </div>

          </div>

        </div>
        @endforeach
      </div>

    </div>

    <div class="cart-summary clearfix">

      <div class="pull-right">

        @foreach($orderTotals as $orderTotal)
          <div class="text-right">
            <h5 class="{{$orderTotal['class']}}">{{$orderTotal['title']}}: <span class="amount">{{$orderTotal['value']}}</span></h5>
          </div>
        @endforeach

      </div>

    </div>

  </div>

</div>

<script type="text/javascript">

  $(document).ready(function(){
    const orderProgressBar = new OrderProgressBar({{$percent}});
    orderProgressBar.load();
  });

</script>

@stop