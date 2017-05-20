@extends('layouts.blackbox.main')
@section('content')

<div class="sub-header-nav">
  <div class="sub-header-nav-fixed-top">
    <div class="row">
      <div class="col-xs-12">

        <div class="btn-group pull-right">
          <a href="{{request()->get('shopUrl')}}order" class="btn btn-secondary">กลับไปหน้ารายการสั่งซื้อสินค้า</a>
        </div>

      </div>
    </div>
  </div>
</div>

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

  @if(!$hasPaymentMethod)
    <div class="secondary-message-box error space-bottom-30">
      <div class="secondary-message-box-inner">
        <h3>ไม่พบวิธีการชำระเงินชองคุณ</h3>
        <p>กรุณาเพิ่มวิธีการชำระเงินของคุณอย่างน้อย 1 วิธี เพื่อใช่ในการกำหนดวิธีการชำระเงินให้กับลูกค้า</p>
      </div>
      <div class="message-box-button-group clearfix">
        <div class="flat-button">
          <a href="{{$PaymentMethodAddUrl}}" class="button">เพิ่มวิธีการชำระเงินชองคุณ</a>
        </div>
      </div>
    </div>
  @endif

  @if($order['order_status_id'] == 1)

    <div class="secondary-message-box info space-bottom-30">
      <div class="secondary-message-box-inner">
        <h3>การสั่งซื้อใหม่</h3>
      </div>
      <div class="message-box-button-group two-button clearfix">
        <div class="flat-button">
          <a href="{{$orderConfirmUrl}}" class="button">ยืนยันการสั่งซื้อ</a>
        </div>
        <div class="flat-button">
          <a class="button danger" data-right-side-panel="1" data-right-side-panel-target="#order_cancel_panel">
            ยกเลิกการสั่งซื้อ
          </a>
        </div>
      </div>
    </div>

    <div id="order_cancel_panel" class="right-size-panel form">
      <div class="right-size-panel-inner">
        
        <h3>ยกเลิกการสั่งซื้อ</h3>
        <div class="line space-bottom-20"></div>
        <?php 
          echo Form::model([], [
            'url' => $orderCancelUrl,
            'method' => 'PATCH',
            'enctype' => 'multipart/form-data'
          ]);
        ?>

        <div class="form-row">
          <h5>ข้อความถึงผู้ซื้อ</h5>
          <?php
            echo Form::textarea('message');
          ?>
        </div>
        <?php 
          echo Form::submit('บันทึก' , array(
            'class' => 'button space-top-20'
          ));
          echo Form::close();
        ?>

        <div class="right-size-panel-close-button"></div>
      </div>
    </div>

  @elseif($order['order_status_id'] == 2)

    @if($hasOrderPaymentConfirm)

      <?php 
        echo Form::model([], [
          'url' => $paymentConfirmUrl,
          'method' => 'PATCH',
          'enctype' => 'multipart/form-data'
        ]);
      ?>

      <div>
        <h3>ลูกค้าแจ้งการชำระเงินแล้ว</h3>
      </div>

      <div class="secondary-message-box info space-bottom-30">
 
        <div class="message-box-button-group two-button clearfix">
          <div class="flat-button">
            <a href="{{$paymentDetailUrl}}" class="button">รายละเอียดแจ้งการชำระเงิน</a>
          </div>
          <div class="flat-button">
            <?php
              echo Form::submit('ยืนยันการชำระเงิน' , array(
                'class' => 'button',
                'data-modal' => 1,
                'data-modal-title' => 'ต้องการยืนยันการชำระเงินเลขที่การสั่งซื้อ '.$order['invoice_number'].' ใช่หรือไม่'
              ));
            ?>
          </div>
        </div>

      </div>

      <?php
        echo Form::close();
      ?>

    @else

      <div class="secondary-message-box success space-bottom-30">
        <div class="secondary-message-box-inner">
          <h3>ยืนยันการสั่งซื้อแล้ว</h3>
        </div>
      </div>

      <div class="secondary-message-box info space-bottom-30">
        <div class="secondary-message-box-inner">
          <h3>โปรดรอการแจ้งการชำระเงินจากลูกค้า</h3>
        </div>
      </div>

    @endif

  @elseif($order['order_status_id'] == 6)
    <div class="secondary-message-box info space-bottom-30">
      <div class="secondary-message-box-inner">
        <h3>การสั่งซื้อถูกยกเลิก</h3>
      </div>
    </div>
  @endif

  @if($order['order_status_id'] != 6)
  <div class="row">

    <div class="col-xs-12">
      
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

      @if(isset($updateOrderStatusUrl))
      <div class="secondary-message-box info space-top-30 space-bottom-30">
        <div class="message-box-button-group clearfix">
          <div class="flat-button">
            <a class="button" data-right-side-panel="1" data-right-side-panel-target="#order_status">เปลี่ยนสถานะการสั่งซื้อ</a>
          </div>
        </div>
      </div>

      <div id="order_status" class="right-size-panel form">
        <div class="right-size-panel-inner">
          @include('pages.order.components.order_status_edit')
          <div class="right-size-panel-close-button"></div>
        </div>
      </div>

      @endif

      <!-- <div class="line space-top-bottom-20"></div> -->

    </div>

  </div>
  @endif

  <div class="row space-top-50">

    <div class="col-md-4 col-xs-12">

      <div class="detail-group">
        <h4>รายละเอียดการสั่งซื้อ</h4>
        <div class="line"></div>

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

    <div class="col-md-4 col-xs-12">

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

    <div class="col-md-4 col-xs-12">

      @if($order['pick_up_order'])
      <div class="detail-info-section no-margin">
        <h4>สาขาที่เข้ารับสินค้า</h4>
        <div class="line"></div> 
        <div class="detail-info">
          @foreach($branches as $branch)
            {{$branch['name']}}
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

  <div class="row">
    <div class="col-md-6 col-xs-12">
      <h4>ข้อความจากผู้ซื้อ</h4>
      <div class="line"></div>
      <div>
        {!!$order['customer_message']!!}
      </div>
    </div>
  </div>

  <div class="line space-top-bottom-80"></div>

  <h3>
    <img src="/images/icons/tag-blue.png">
    สรุปรายการสั่งซื้อ
  </h3>

  <div class="cart space-top-30">

    <div class="product-list-table">

      <h4>รายการสินค้า</h4>
      <div class="line grey"></div>

      <div class="product-list-wrapper">
        @foreach($orderProducts as $product)
        <div class="product-list-table-row">

          @if(!empty($product['hasError']))
            <p class="product-error-message">
              {{$product['errorMessage']}}
            </p>
          @endif

          <div class="product-list-box clearfix">

            <div class="image-tile pull-left">
              <a href="{{$product['productDetailUrl']}}">
                <div class="product-image" style="background-image:url({{$product['imageUrl']}});"></div>
              </a>
            </div>

            <div class="col-md-10 col-xs-8 product-info">

              <div class="col-md-4 col-xs-12 product-info-container">
                <a href="{{$product['productDetailUrl']}}">
                  <h4 class="product-text">{{$product['product_name']}}</h4>
                </a>
                @if(!empty($product['productOption']))
                  <div class="product-option">
                    <span class="product-option-name">{{$product['productOption']['productOptionName']}}:</span>
                    <span class="product-option-value-name">{{$product['productOption']['valueName']}}</span>
                  </div>
                @endif
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

      <div class="shipping-method-input space-top-30">

        <h4>การจัดส่ง</h4>
        <div class="line grey"></div>

        <div class="shipping-method-choice">
          <label class="choice-box">

            <div class="inner">
              <div class="row">

                <div class="col-md-4 col-xs-12">
                  <div>ค่าจัดส่ง</div>
                  <div class="shipping-cost">
                    {{$orderShippingCosts['orderShippingCost']}}
                  </div>
                </div>

              </div>
            </div>
          </label>

        </div>
      </div>

      <div class="shipping-cost-summary">
        <h4>สรุปค่าจัดส่ง</h4>
        <h5>ค่าจัดส่ง: <strong>{{$orderShippingCosts['orderShippingCost']}}</strong></h5>
        <h5>ค่าจัดส่งรวมของสินค้าแต่ละรายการ: <strong>{{$orderShippingCosts['productsShippingCost']}}</strong></h5>
        @if(!empty($order['shipping_cost_detail']))
          <a data-right-side-panel="1" data-right-side-panel-target="#shipping_cost_detail" role="button"><strong>แสดงรายละเอียดค่าจัดส่ง</strong></a>
          <div id="shipping_cost_detail" class="right-size-panel">
            <div class="right-size-panel-inner">
              <h3>รายละเอียดค่าจัดส่ง</h3>
              <div class="line space-bottom-10"></div>
              {!!$order['shipping_cost_detail']!!}
            </div>
            <div class="right-size-panel-close-button"></div>
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

  <div class="line space-top-bottom-80"></div>

  <h3><img src="/images/icons/message-blue.png">ประวัติการสั่งซื้อ</h3>
  <div class="space-top-30">
    <h4>รายละเอียด</h4>

    @foreach($orderHistories as $orderHistory)
      <div class="order-histroty-list space-top-20">
        <div class="order-histroty-list-inner">
          <h4>{{$orderHistory['orderStatus']}}</h4>
          <h5>เมื่อ {{$orderHistory['createdDate']}}</h5>
          @if(!empty($orderHistory['message']))
          <h5 class="space-top-20"><strong>รายละเอียด</strong></h5>
          <div>
            {!!$orderHistory['message']!!}
          </div>
          @endif
        </div>
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