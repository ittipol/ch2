@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">เลขที่การสั่งซื้อ {{$order['invoice_number']}}</h2>
      </div>
    </div>
  </div>
</div>

<div class="container">

  @if($order['order_status_id'] == 1)
  <div class="secondary-message-box info space-bottom-30">
    <div class="secondary-message-box-inner">
      <h3>รายการสั่งซื้อยังไม่ถูกยืนยันจากผู้ขาย</h3>
      <p>*** ค่าจัดส่งของการสั่งซื้อนี้ยังไม่ใช่จำนวนสุทธิที่ต้องชำระ</p>
      <p>*** โปรดรอการยืนยันการสั่งซื้อจากผู้ขายก่อนการชำระเงิน</p>
    </div>
  </div>
  @elseif($order['order_status_id'] == 2)

    @if($hasOrderPaymentConfirm)

      <div class="secondary-message-box success">
        <div class="secondary-message-box-inner">
          <h3>ยืนยันการชำระเงินเลขที่การสั่งซื้อ {{$order['invoice_number']}} แล้ว</h3>
          <p>*** โปรดรอการตรวจสอบการชำระเงินจากผู้ขาย</p>
        </div>
      </div>

    @else

      <div class="secondary-message-box success">
        <div class="secondary-message-box-inner">
          <h3>ผู้ขายยืนยันการสั่งซื้อแล้ว</h3>
          <p>*** การสั่งซื้อนี้สามารถชำระเงินได้แล้ว</p>
        </div>
      </div>

      <div class="secondary-message-box info space-bottom-30">
        <div class="secondary-message-box-inner">
          <div class="list-empty-message text-center">
            <img class="space-bottom-20" src="/images/common/payment.png">
            <div>
              <h3>จำนวนเงินที่ต้องชำระ {{$orderTotals['total']['value']}}</h3>
              <p>โปรดชำระเงินตามจำนวนที่ได้ระบุไว้</p>
            </div>
          </div>
        </div>
        <div class="message-box-button-group clearfix">
          <div class="flat-button">
            <a href="{{URL::to($paymentInformUrl)}}" class="button">แจ้งการชำระเงิน</a>
          </div>
        </div>
      </div>

      <div class="row">

        @if(!empty($orderConfirmMessage))
        <div class="col-sm-12 space-bottom-30">
          <div class="detail-info-section no-margin">
            <h4>ข้อความจากผู้ขาย</h4>
            <div class="line"></div>
            <div class="space-top-10">
              {!!$orderConfirmMessage!!}
            </div>
          </div>
        </div>
        @endif

        <div class="col-sm-12 space-bottom-30">

          <div class="detail-info-section no-margin">
            <h4>วิธีการชำระเงินการสั่งซื้อนี้</h4>
            <div class="line"></div>

            <div class="list-h">
              @foreach($paymentMethods as $paymentMethod)
              <div class="list-h-item clearfix">

                <div class="list-image pull-left">
                  <a data-right-side-panel="1" data-right-side-panel-target="#payment_method_{{$paymentMethod['id']}}">
                    <img src="/images/icons/payment-white.png">
                  </a>
                </div>

                <div class="col-md-11 col-xs-8">

                  <div class="row">

                    <div class="col-xs-12 list-content">
                      <a data-right-side-panel="1" data-right-side-panel-target="#payment_method_{{$paymentMethod['id']}}">
                        <h4 class="primary-info single-info">{{$paymentMethod['name']}}</h4>
                      </a>
                    </div>

                  </div>

                </div>

              </div>

              <div id="payment_method_{{$paymentMethod['id']}}" class="right-size-panel">
                <div class="right-size-panel-inner">
                    <h3>{{$paymentMethod['name']}}</h3>
                    <div class="line space-bottom-10"></div>
                    <h5>รายละเอียดการชำระเงิน</h5>
                    {!!$paymentMethod['description']!!}
                  <div class="right-size-panel-close-icon"></div>
                </div>
              </div>

              @endforeach
            </div>
            
          </div>

        </div>
      </div>

    @endif

  @endif

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
        <div class="line"></div>
        <div class="detail-group-info-section">

          <div class="detail-group-info">
            <h5 class="title">ชื้อบริษัทหรือร้านค้าที่ขายสินค้า</h5>
            <a href="{{URL::to($shopUrl)}}">
              <p>{{$order['shopName']}}</p>
            </a>
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

    <div class="col-md-4 col-sm-12">

      <div class="detail-group">
        <h4>วิธีการจัดส่งสินค้า</h4>
        <div class="line"></div>

        @if(!empty($orderShippingMethod))

        <div class="detail-group-info-section">
          <div class="detail-group-info">
            <h5 class="title">การจัดส่งสินค้า</h5>
            <p>{{$orderShippingMethod['shipping_method_name']}}</p>
          </div>

          <div class="detail-group-info">
            <h5 class="title">ผู้ให้บริการการจัดส่ง</h5>
            <p>{{$orderShippingMethod['shippingService']}}</p>
          </div>

          <div class="detail-group-info">
            <h5 class="title">รูปแบบการคิดค่าจัดส่ง</h5>
            <p>{{$orderShippingMethod['shippingServiceCostType']}}</p>
          </div>

          <div class="detail-group-info">
            <h5 class="title">ระยะเวลาจัดส่ง</h5>
            <p>{{$orderShippingMethod['shipping_time']}}</p>
          </div>
        </div>

        @else

          <div class="detail-info">
            -
          </div>

        @endif

      </div>

    </div>

    <div class="col-md-4 col-sm-12">

      @if($order['pick_up_order'])
      <div class="detail-info-section no-margin">
        <h4>สาขาที่เข้ารับสินค้า</h4>
        <div class="line"></div> 
        <div class="detail-info">
          @foreach($branches as $branch)
            <a href="{{$branch['detailUrl']}}">{{$branch['name']}}</a>
          @endforeach
        </div>
      </div>
      @endif

      <div class="detail-info-section no-margin">
        <h4>ที่อยู่สำหรับการจัดส่ง</h4>
        <div class="line"></div> 
        <div class="detail-info">
          {{$order['shipping_address']}}
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

      <div class="shipping-cost-summary">
        <h4>ค่าจัดส่งสินค้าของการสั่งซื้อนี้</h4>
        <h5>ค่าจัดส่งต่อการสั่งซื้อ: <strong>{{$orderShippingCosts['orderShippingCost']}}</strong></h5>
        <h5>ค่าจัดส่งรวมของสินค้าแต่ละรายการ: <strong>{{$orderShippingCosts['productsShippingCost']}}</strong></h5>
        @if($order['order_status_id'] == 1)
          <p class="error-message">*** ค่าจัดส่งของการสั่งซื้อนี้ยังไม่ใช่จำนวนสุทธิที่ต้องชำระ</p>
          <p class="error-message">*** อาจมีการเปลี่ยนแปลงหลังจากรายการสั่งซื้อถูกยืนยันจากผู้ขาย</p>
        @endif
        @if(!empty($order['shipping_cost_detail']))
          <a data-right-side-panel="1" data-right-side-panel-target="#shipping_cost_detail" role="button"><strong>แสดงรายละเอียดค่าจัดส่ง</strong></a>
          <div id="shipping_cost_detail" class="right-size-panel">
            <div class="right-size-panel-inner">
              <h3>รายละเอียดค่าจัดส่ง</h3>
              <div class="line space-bottom-10"></div>
              {!!$order['shipping_cost_detail']!!}
            </div>
            <div class="right-size-panel-close-icon"></div>
          </div>
        @endif
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

  <div>
    <h4>ประวัติการสั่งซื้อ</h4>
    <div class="line"></div>

    @foreach($orderHistories as $orderHistory)
      <div class="space-bottom-20">
        <h4>{{$orderHistory['orderStatus']}}</h4>
        <h5>{{$orderHistory['createdDate']}}</h5>
        @if(!empty($orderHistory['message']))
        <div>
          {!!$orderHistory['message']!!}
        </div>
        @endif
      </div>
    @endforeach

  </div>

</div>

<script type="text/javascript">

  $(document).ready(function(){
    const orderProgressBar = new OrderProgressBar({{$percent}});
    orderProgressBar.load();
  });

</script>

@stop