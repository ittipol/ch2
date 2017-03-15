@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">ยืนยันการสั่งซื้อเลขที่ {{$order['invoice_number']}}</h2>
      </div>
    </div>
  </div>
</div>

<div class="container">

  <div class="row">

    <div class="col-md-4 col-sm-12">

      <div class="detail-group">
        <h4>รายละเอียดการสั่งซื้อ</h4>

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
                <div class="product-text shipping-cost-input">
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

        @foreach($orderOrderTotals as $orderOrderTotal)
          <div class="text-right">
            <h5 class="{{$orderOrderTotal['_class']}}">{{$orderOrderTotal['_title']}}: <span class="amount">{{$orderOrderTotal['_value']}}</span></h5>
          </div>
        @endforeach

      </div>

    </div>

  </div>

  <div class="space-top-30">
    <h4 class="require">ค่าจัดส่งสินค้า</h4>
    <div class="line"></div>
    <div class="alert alert-danger space-top-20" role="alert">
      <p class="error-message">* สินค้าที่คำนวณค่าจัดส่งแล้วจะไม่สามารถแก้ไขค่าจัดส่งได้</p>
    </div>

    <label class="choice-box">
      <?php
        echo Form::radio('shipping_cost_chkbox', 2, null);
      ?> 
      <div class="inner">กำหนดค่าจัดส่งต่อการสั่งซื้อ</div>
    </label>

    <div class="shipping-cost-input-section">

      <label class="choice-box">
        <?php
          echo Form::checkbox('include', 1, null);
        ?> 
        <div class="inner">รวมกับสินค้าที่คิดค่าจัดส่งแล้ว</div>
      </label>

      <input 
      class="shipping-cost-input" 
      type="text" name="quantity" 
      value=""
      autocomplete="off"
      placeholder="กำหนดค่าจัดส่งต่อการสั่งซื้อ" 
      role="number" />

    </div>

    <label class="choice-box">
      <?php
        echo Form::radio('shipping_cost_chkbox', 1, true);
      ?> 
      <div class="inner">กำหนดค่าจัดส่งรายสินค้า</div>
    </label>

    <div class="shipping-cost-input-section">
      <h4>สินค้าที่ยังไม่ได้คิดค่าจัดส่ง</h4>

      <div class="row">

        @foreach($orderProducts as $product)

          @if($product['shipping_calculate_from'] == 1)
          <div class="col-md-6 col-xs-12">
            <div class="shipping-cost-input-box clearfix">

              <div class="text-center pull-left">
                <img src="{{$product['imageUrl']}}">
              </div>

              <div class="col-xs-8">
                <h5><strong>{{$product['product_name']}}</strong></h5>

                <input 
                class="shipping-cost-input" 
                type="text" name="quantity" 
                value=""
                autocomplete="off"
                placeholder="ค่าจัดส่งสินค้า" 
                role="number" />

              </div>

            </div>
          </div>
          @endif

        @endforeach

      </div>

    </div>

  </div>

  <div class="space-top-30">
    <h4 class="require">รายละเอียดและวิธีการชำระเงิน</h4>
    <div class="line"></div>
    <?php 
      echo Form::textarea('description', null, array(
        'class' => 'ckeditor'
      ));
    ?>
  </div>

  <div class="space-top-30">
    <h4 class="require">ข้อความถึงผู้ซื้อ</h4>
    <div class="line"></div>
    <?php 
      echo Form::textarea('description', null, array(
        'class' => 'ckeditor'
      ));
    ?>
  </div>

</div>

@stop