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

  <?php 
    echo Form::open(['id' => 'main_form','method' => 'post', 'enctype' => 'multipart/form-data']);
  ?>

  <div class="space-top-30">
    <h4 class="require">ค่าจัดส่งสินค้า</h4>
    <div class="line"></div>
    <div class="alert alert-danger space-top-20" role="alert">
      <p class="error-message">* สินค้าที่คำนวณค่าจัดส่งแล้วจะไม่สามารถแก้ไขค่าจัดส่งได้</p>
    </div>

    <label class="choice-box">
      <?php
        echo Form::radio('shipping_cost_type', 1, !$hasProductNotSetShippingCost, array(
          'id' => 'shipping_cost_order_chkbox'
        ));
      ?> 
      <div class="inner">กำหนดค่าจัดส่งต่อการสั่งซื้อ</div>
    </label>

    <div class="shipping-cost-input-section">

      @if(!$hasAllProductNotSetShippingCost)

      <label class="choice-box">
        <?php
          echo Form::checkbox('cancel_product_shipping_cost', 1, null, array(
          'id' => 'cancel_product_shipping_cost',
          'disabled' => 'disabled'
        ));
        ?> 
        <div class="inner">ยกเลิกค่าจัดส่งสินค้าที่ได้คิดค่าจัดส่งไว้แล้ว</div>
      </label>

      @endif

      <?php
        echo Form::text('shipping_cost_order', null, array(
          'class' => 'shipping-cost-input shipping_cost_order_input',
          'placeholder' => 'กำหนดค่าจัดส่งต่อการสั่งซื้อ',
          'autocomplete' => 'off',
          'role' => 'number',
          'disabled' => 'disabled'
        ));
      ?>

    </div>

    @if($hasProductNotSetShippingCost)

    <label class="choice-box">
      <?php
        echo Form::radio('shipping_cost_type', 2, $hasProductNotSetShippingCost, array(
          'id' => 'shipping_cost_product_chkbox'
        ));
      ?> 
      <div class="inner">กำหนดค่าจัดส่งรายสินค้า</div>
    </label>

    <div class="shipping-cost-input-section">
      <h4>สินค้าที่ยังไม่ได้คิดค่าจัดส่ง</h4>

      <div class="row">

        @foreach($orderProducts as $product)

          @if(($product['shipping_calculate_from'] == 1)  && empty($product['shipping_cost']))
          <div class="col-md-6 col-xs-12">
            <div class="shipping-cost-input-box clearfix">

              <div class="text-center pull-left">
                <img src="{{$product['imageUrl']}}">
              </div>

              <div class="col-xs-8">
                <h5><strong>{{$product['product_name']}}</strong></h5>

                <?php
                  echo Form::text('shipping_cost_product['.$product['id'].']', null, array(
                    'class' => 'shipping-cost-input shipping_cost_product_input',
                    'placeholder' => 'ค่าจัดส่งสินค้า',
                    'autocomplete' => 'off',
                    'role' => 'number'
                  ));
                ?>

              </div>

            </div>
          </div>
          @endif

        @endforeach

      </div>

    </div>

    @endif

  </div>

  <div class="space-top-30">
    <h4 class="required">วิธีการชำระเงิน</h4>
    <p class="notice info">กำหนดวิธีการชำระเงินของคุณให้กับการสั่งซื้อนี้</p>
    <div class="line"></div>

    @if(!empty($paymentMethods))

      @foreach ($paymentMethods as $id => $name)
      <div>
        <label class="choice-box">
          <?php
            echo Form::checkbox('payment_method[]', $id);
          ?> 
          <div class="inner"><?php echo $name; ?></div>
        </label>
      </div>
      @endforeach

    @else
      <p class="space-top-10">ยังไม่เพิ่มวิธีการชำระเงิน</p>
    @endif

  </div>

  <div class="space-top-30">
    <h4>ข้อความถึงผู้ซื้อ</h4>
    <!-- <div class="line"></div> -->
    <?php 
      echo Form::textarea('comment', null, array(
        'class' => 'ckeditor'
      ));
    ?>
  </div>

  <?php
    echo Form::submit('ยืนยันการสั่งซื้อ' , array(
      'class' => 'button space-top-30'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  class ShippingCostInput {

    constructor() {

    }

    load() {

      let _this = this;

      if($('#shipping_cost_order_chkbox').is(':checked')) {
        $('.shipping_cost_order_input').prop('disabled', false);
        $('#cancel_product_shipping_cost').prop('disabled', false);
        $('.shipping_cost_product_input').prop('disabled', true);
      }

      if($('#shipping_cost_product_chkbox').is(':checked')) {
        $('.shipping_cost_order_input').prop('disabled', true);
        $('#cancel_product_shipping_cost').prop('disabled', true).prop('checked',false);
        $('.shipping_cost_product_input').prop('disabled', false);
      }

      this.bind();

    }

    bind() {

      let _this = this;

      $('#shipping_cost_order_chkbox').on('change',function(){
        $('.shipping_cost_order_input').prop('disabled', false);
        $('#cancel_product_shipping_cost').prop('disabled', false);
        $('.shipping_cost_product_input').prop('disabled', true);
      });

      $('#shipping_cost_product_chkbox').on('change',function(){
        $('.shipping_cost_order_input').prop('disabled', true);
        $('#cancel_product_shipping_cost').prop('disabled', true).prop('checked',false);
        $('.shipping_cost_product_input').prop('disabled', false);
      });

    }

  }

  $(document).ready(function(){
    const shippingCostInput = new ShippingCostInput();
    shippingCostInput.load();
  });

</script>

@stop