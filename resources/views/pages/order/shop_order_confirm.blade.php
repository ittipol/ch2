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

  @include('components.form_error') 

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

    <div class="secondary-message-box">
      <div>* เมื่อกำหนดค่าจัดส่งสินค้าทั้งหมดแล้ว ค่าจัดส่งทั้งหมดจะถูกนำมารวมและจะเป็นจำนวนค่าจัดส่งสุทธิที่ลูกค้าต้องชำระ</div>
    </div>

    <div class="shipping-cost-input-section">

      <div>
        <label class="choice-box">
          <?php
            echo Form::radio('order_shipping', 1, true, array(
              'id' => 'charge_order_shipping'
            ));
          ?> 
          <div class="inner">คิดค่าจัดส่งสินค้า</div>
        </label>

        <label class="choice-box">
          <?php
            echo Form::radio('order_shipping', 2, null, array(
              'id' => 'free_order_shipping'
            ));
          ?> 
          <div class="inner">ฟรีค่าจัดส่งสินค้า</div>
        </label>
      </div>

      <!-- <div class="line space-bottom-20"></div> -->

      <h4>กำหนดค่าจัดส่งของการสั่งซื้อนี้</h4>
      <p class="notice info">เว้นว่างเมื่อไม่ต้องการกำหนดค่าจัดส่งของการสั่งซื้อนี้</p>
      <?php
        echo Form::text('shipping_cost_order_value', null, array(
          'class' => 'shipping-cost-input shipping_cost_order_input',
          'placeholder' => 'ค่าจัดส่งของการสั่งซื้อนี้',
          'autocomplete' => 'off',
          'role' => 'currency'
        ));
      ?>

      <div class="line space-top-bottom-20"></div>

      <h4>กำหนดค่าจัดส่งของสินค้าแต่ละรายการ</h4>

      <!-- <div class="secondary-message-box">
        <div>* เมื่อเลือกตัวเลือก "กำหนดค่าจัดส่งของสินค้าแต่ละรายการ" จะเป็นการยกเลิกการกำหนดค่าจัดส่งของสินค้าทุกรายการและจำนวนค่าจัดส่งสินค้าจะถูกปรับเป็น 0</div>
      </div> -->

      <label class="choice-box">
        <?php
          echo Form::checkbox('cancel_product_shipping_cost', 1, null, array(
            'id' => 'cancel_product_shipping_cost'
          ));
        ?> 
        <div class="inner">ยกเลิกค่าจัดส่งทั้งหมดของสินค้าแต่ละรายการ</div>
      </label>

      @if($checkHasProductHasShippingCost)
      <h4>รายการสินค้าที่คิดค่าจัดส่งแล้ว</h4>
      <div class="row">

        @foreach($orderProducts as $orderProduct)

          @if($orderProduct['shipping_calculate_from'] == 2)

            <div class="col-md-6 col-xs-12">
              <div class="shipping-cost-input-box clearfix">
                <div>
                  <h5><strong>{{$orderProduct['product_name']}}</strong></h5>
                </div>

                <div class="clearfix">

                  <div class="text-center pull-left">
                    <img src="{{$orderProduct['imageUrl']}}">
                  </div>

                  <div class="col-xs-8">

                    <?php
                      echo Form::text('products['.$orderProduct['product_id'].'][shipping_cost]', $orderProduct['shipping_cost'], array(
                        'class' => 'shipping-cost-input shipping_cost_product_input',
                        'placeholder' => 'ค่าจัดส่งสินค้า',
                        'autocomplete' => 'off',
                        // 'role' => 'currency'
                      ));
                    ?>
                    <br/>
                    <label class="choice-box">
                      <?php
                        echo Form::checkbox('products['.$orderProduct['product_id'].'][free_shipping]', 1, $orderProduct['free_shipping'], array(
                          'class' => 'free_shipping_chkbox'
                        ));
                      ?> 
                      <div class="inner">จัดส่งฟรี</div>
                    </label>

                  </div>

                </div>

              </div>
            </div>

          @endif

        @endforeach

      </div>
      @endif

      @if($checkHasProductNotSetShippingCost)
      <h4>รายการสินค้ายังไม่ถูกคิดค่าจัดส่ง</h4>
      <div class="row">

        @foreach($orderProducts as $orderProduct)

          @if(($orderProduct['shipping_calculate_from'] == 1))
          <div class="col-md-6 col-xs-12">
            <div class="shipping-cost-input-box clearfix">
              <div>
                <h5><strong>{{$orderProduct['product_name']}}</strong></h5>
              </div>

              <div class="clearfix">

                <div class="text-center pull-left">
                  <img src="{{$orderProduct['imageUrl']}}">
                </div>

                <div class="col-xs-8">

                  <?php
                    echo Form::text('products['.$orderProduct['product_id'].'][shipping_cost]', $orderProduct['shipping_cost'], array(
                      'class' => 'shipping-cost-input shipping_cost_product_input',
                      'placeholder' => 'ค่าจัดส่งสินค้า',
                      'autocomplete' => 'off',
                      'role' => 'currency'
                    ));
                  ?>
                  <br/>
                  <label class="choice-box">
                    <?php
                      echo Form::checkbox('products['.$orderProduct['product_id'].'][free_shipping]', 1, $orderProduct['free_shipping'], array(
                        'class' => 'free_shipping_chkbox'
                      ));
                    ?> 
                    <div class="inner">จัดส่งฟรี</div>
                  </label>

                </div>

              </div>

            </div>
          </div>
          @endif

        @endforeach

      </div>
      @endif

    </div>

  </div>
รายละเอียดการคิดค่าขนส่นของการสั่งซื้อ
  <div class="space-top-30">
    <h4 class="required">วิธีการชำระเงิน</h4>
    <div class="line"></div>

    <div class="secondary-message-box info">
      <h3>กำหนดวิธีการชำระเงินของคุณให้กับการสั่งซื้อนี้</h3>
      <p>กรุณาเลือกวิธีการชำระเงินอย่างน้อย 1 วิธีให้กับการสั่งซื้อนี้</p>
    </div>

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

  </div>

  <div class="space-top-30">
    <h4>ข้อความถึงผู้ซื้อ</h4>
    <?php 
      echo Form::textarea('comment', null, array(
        'class' => 'ckeditor'
      ));
    ?>
  </div>

  <div class="secondary-message-box space-top-30">
    <div>* จะไม่สามารถแก้ไขได้หลังจากยืนยันการสั่งซื้อ</div>
    <div>* โปรดตรวจสอบความถูกต้องก่อนการยืนยันการสั่งซื้</div>
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

    constructor() {}

    load() {

      $('.free_shipping_chkbox').each(function(i, obj) {
        if($(this).is(':checked')) {
          $(this).parent().parent().find('.shipping_cost_product_input').prop('disabled', true);
        }
      });

      this.bind();
    }

    bind() {

      $('#charge_order_shipping').on('change',function(){
        if($(this).is(':checked')) {

          $('#cancel_product_shipping_cost').prop('disabled', false);

          if(!$('#cancel_product_shipping_cost').is(':checked')) {
            $('.shipping_cost_order_input').prop('disabled', false);
            $('.shipping_cost_product_input').prop('disabled', false);
            $('.free_shipping_chkbox').prop('disabled', false);
          }

        }
      });

      $('#free_order_shipping').on('change',function(){
        if($(this).is(':checked')) {

          $('#cancel_product_shipping_cost').prop('disabled', true);

          $('.shipping_cost_order_input').prop('disabled', true);
          $('.shipping_cost_product_input').prop('disabled', true);
          $('.free_shipping_chkbox').prop('disabled', true);
        }
      });

      $('#cancel_product_shipping_cost').on('change',function(){
        if($(this).is(':checked')) {
          $('.shipping_cost_product_input').prop('disabled', true);
          $('.free_shipping_chkbox').prop('disabled', true);
        }else{
          $('.shipping_cost_product_input').prop('disabled', false);
          $('.free_shipping_chkbox').prop('disabled', false);
        }
      });

      $('.free_shipping_chkbox').on('change',function(){
        if($(this).is(':checked')) {
          $(this).parent().parent().find('.shipping_cost_product_input').prop('disabled', true);
        }else{
          $(this).parent().parent().find('.shipping_cost_product_input').prop('disabled', false);
        }
      });

    }

  }

  $(document).ready(function(){
    const shippingCostInput = new ShippingCostInput();
    shippingCostInput.load();

    const form = new Form();
    form.load();
  });

</script>

@stop