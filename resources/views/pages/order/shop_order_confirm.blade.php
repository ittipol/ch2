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
    <h4>วิธีการจัดส่งสินค้า</h4>
    <div class="line"></div>

    @if(empty($orderShippingMethod))
      <div class="secondary-message-box info">
        <div class="secondary-message-box-inner">
          <div>*** การสั่งซื้อนี้ยังไม่ได้กำหนดวิธีการจัดส่งสินค้า</div>
        </div>
      </div>

      <div class="shipping-method-input">
        @foreach($shippingMethods as $shippingMethod)
          <div class="shipping-method-choice">
            <label class="choice-box">
              <?php
                echo Form::radio('shipping_method_id', $shippingMethod['id'], $shippingMethod['select'], array(
                  'class' => 'shipping-method-rdobox',
                  'data-free-service' => !empty($shippingMethod['free_service']) ? 1 : 0,
                  'data-service-cost' => isset($shippingMethod['service_cost']) ? $shippingMethod['service_cost'] : ''
                ));
              ?> 
              <div class="inner">
                <div class="row">

                  <div class="col-md-4 col-xs-12">
                    <h5>{{$shippingMethod['name']}}</h5>
                    <p>ผู้ให้บริการ: <strong>{{$shippingMethod['shippingService']}}</strong></p>
                    <p>ระยะเวลาจัดส่ง: <strong>{{$shippingMethod['shipping_time']}}</strong></p>
                  </div>

                  <div class="col-md-4 col-xs-12">
                    <p>รูปแบบการคิดค่าจัดส่ง: <strong>{{$shippingMethod['shippingServiceCostType']}}</strong></p>
                  </div>

                  <div class="col-md-4 col-xs-12">
                    <div class="shipping-cost">
                      @if(empty($shippingMethod['serviceCostText']) || ($shippingMethod['serviceCostText'] == '-'))
                        -
                      @else
                        + {{$shippingMethod['serviceCostText']}}
                      @endif
                    </div>
                  </div>

                </div>
              </div>
            </label>
          </div>
        @endforeach
      </div>

    @else

      <div class="secondary-message-box info">
        <div class="secondary-message-box-inner">
          <div>*** วิธีการจัดส่งที่ลูกค้าเลือก</div>
        </div>
      </div>

      <div class="list-h">

        <div class="list-h-item clearfix">

          <div class="list-image pull-left">
            <img src="/images/icons/truck-white.png">
          </div>

          <div class="col-md-11 col-xs-8">

            <div class="row">

              <div class="col-md-4 col-xs-12 list-content">
                <h4 class="primary-info">{{$orderShippingMethod['shipping_method_name']}}</h4>
                <div class="secondary-info">ผู้ให้บริการการจัดส่ง: {{$orderShippingMethod['shippingService']}}</div>
                <div class="secondary-info">ระยะเวลาจัดส่ง: {{$orderShippingMethod['shipping_time']}}</div>
              </div>

              <div class="col-md-4 col-xs-12 list-content">
                <h4 class="primary-info">รูปแบบการคิดค่าจัดส่ง</h4>
                <div class="secondary-info">{{$orderShippingMethod['shippingServiceCostType']}}</div>
              </div>

              <div class="col-md-4 col-xs-12 list-content">
                <h4 class="primary-info">ค่าจัดส่ง</h4>
                <div class="secondary-info">{{$order['orderShippingCostText']}}</div>
              </div>

            </div>

          </div>

        </div>

      </div>

    @endif

  </div>

  <div class="space-top-30">
    <h4 class="require">ค่าจัดส่งสินค้า</h4>
    <div class="line"></div>

    <div class="secondary-message-box info">
      <div class="secondary-message-box-inner">
        <div>*** ผู้ขายสามารถแก้ไขค่าจัดส่งได้จากส่วนนี้</div>
        <div>*** ในแต่ละการสั่งซื้อผู้ขายสามารถกำหนดค่าจัดส่งให้กับการสั่งซื้อและสินค้าแต่ละรายการได้</div>
        <div>*** หลังจากกำหนดค่าจัดส่งสินค้าทั้งหมดแล้ว ค่าจัดส่งทั้งหมดจะถูกนำมารวมและจะเป็นจำนวนค่าจัดส่งสุทธิที่ลูกค้าต้องชำระ</div>
      </div>
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
            echo Form::radio('order_shipping', 2, $order['order_free_shipping'], array(
              'id' => 'free_order_shipping'
            ));
          ?> 
          <div class="inner">ฟรีค่าจัดส่งสินค้า</div>
        </label>
      </div>

      <div class="line space-bottom-20"></div>

      <h4>กำหนดค่าจัดส่งของการสั่งซื้อนี้</h4>
      <?php
        echo Form::text('order_shipping_cost', $order['order_shipping_cost'], array(
          'class' => 'shipping-cost-input',
          'id' => 'shipping_cost_order_input',
          'placeholder' => 'ค่าจัดส่งของการสั่งซื้อนี้',
          'autocomplete' => 'off',
          'role' => 'currency'
        ));
      ?>

      <div class="line space-top-bottom-20"></div>

      <h4>กำหนดค่าจัดส่งของสินค้าแต่ละรายการ</h4>

      <label class="choice-box">
        <?php
          echo Form::checkbox('cancel_product_shipping_cost', 1, null, array(
            'id' => 'cancel_product_shipping_cost'
          ));
        ?> 
        <div class="inner">ยกเลิกการคิดค่าจัดส่งของสินค้าแต่ละรายการทั้งหมด</div>
      </label>

      @if($hasProductHasShippingCost)

      <h4>สินค้าที่คิดค่าจัดส่งแล้ว</h4>
      <div class="product-shipping-cost-input-section">
        <div class="row">
          @foreach($orderProducts as $orderProduct)

            @if(($orderProduct['free_shipping'] != null) || ($orderProduct['shipping_cost'] != null))

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

                      <div class="space-bottom-20">
                        <div>มูลค่าสินค้า: <strong>{{$orderProduct['_total']}}</strong></div>
                        <div>จำนวนที่สั่งซื้อ: <strong>{{$orderProduct['quantity']}} {{$orderProduct['product_unit']}}</strong></div>
                        <div>นำ้หนักรวมสินค้า: <strong>{{$orderProduct['totalWeight']}}</strong></div>
                      </div>

                      <div>
                      <?php
                        echo Form::text('products['.$orderProduct['product_id'].'][shipping_cost]', $orderProduct['shipping_cost'], array(
                          'class' => 'shipping-cost-input shipping-cost-product-input',
                          'placeholder' => 'ค่าจัดส่งสินค้า',
                          'autocomplete' => 'off',
                          'role' => 'currency'
                        ));
                      ?>
                      </div>
                      
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
      </div>
      @endif

      @if($hasProductNotSetShippingCost)

      <h4>สินค้ายังไม่ถูกคิดค่าจัดส่ง</h4>
      <div class="product-shipping-cost-input-section">
        <div class="row">
          @foreach($orderProducts as $orderProduct)

            @if(($orderProduct['free_shipping'] == null) && ($orderProduct['shipping_cost'] == null))
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

                    <div class="space-bottom-20">
                      <div>มูลค่าสินค้า: <strong>{{$orderProduct['_total']}}</strong></div>
                      <div>จำนวนที่สั่งซื้อ: <strong>{{$orderProduct['quantity']}} {{$orderProduct['product_unit']}}</strong></div>
                      <div>นำ้หนักรวมสินค้า: <strong>{{$orderProduct['totalWeight']}}</strong></div>
                    </div>

                    <div>
                    <?php
                      echo Form::text('products['.$orderProduct['product_id'].'][shipping_cost]', $orderProduct['shipping_cost'], array(
                        'class' => 'shipping-cost-input shipping-cost-product-input',
                        'placeholder' => 'ค่าจัดส่งสินค้า',
                        'autocomplete' => 'off',
                        'role' => 'currency'
                      ));
                    ?>
                    </div>
                    
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
      </div>
      @endif

    </div>

  </div>

  <div class="space-top-30">
    <h4>รายละเอียดค่าจัดส่นสินค้า</h4>
    <p class="notice info">*** ข้อมูลนี้จะไม่แสดงถ้าหากไม่มีการกรอกรายละเอียด</p>
    <?php 
      echo Form::textarea('shipping_cost_detail');
    ?>
  </div>

  <div class="space-top-30">
    <h4 class="required">วิธีการชำระเงิน</h4>
    <div class="line"></div>

    <div class="secondary-message-box info">
      <div class="secondary-message-box-inner">
        <h3>ระบุวิธีการชำระเงินของการสั่งซื้อนี้</h3>
        <p>กรุณาเลือกวิธีการชำระเงินอย่างน้อย 1 วิธีให้กับการสั่งซื้อนี้</p>
      </div>
    </div>

    <div class="payment-method-input-section">
      @foreach ($paymentMethods as $id => $name)
      <div>
        <label class="choice-box">
          <?php
            echo Form::checkbox('payment_method[]', $id, true);
          ?> 
          <div class="inner"><?php echo $name; ?></div>
        </label>
      </div>
      @endforeach
    </div>

  </div>

  <div class="space-top-30">
    <h4>ข้อความถึงผู้ซื้อ</h4>
    <?php 
      echo Form::textarea('message');
    ?>
  </div>

  <div class="secondary-message-box error space-top-30">
    <div class="secondary-message-box-inner">
      <div>*** จะไม่สามารถแก้ไขได้หลังจากยืนยันการสั่งซื้อ</div>
      <div>*** โปรดตรวจสอบความถูกต้องก่อนการยืนยันการสั่งซื้อ</div>
    </div>
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

      this.shippingMethod($('.shipping-method-rdobox:checked').data('free-service'),$('.shipping-method-rdobox:checked').data('service-cost'));

      if($('#free_order_shipping').is(':checked')) {
        $('#cancel_product_shipping_cost').prop('disabled', true);

        $('#shipping_cost_order_input').prop('disabled', true);
        $('.shipping-cost-product-input').prop('disabled', true);
        $('.free_shipping_chkbox').prop('disabled', true);
      }else if($('#cancel_product_shipping_cost').is(':checked')) {
        $('.shipping-cost-product-input').prop('disabled', true);
        $('.free_shipping_chkbox').prop('disabled', true);
      }

      $('.free_shipping_chkbox').each(function(i, obj) {
        if($(this).is(':checked')) {
          $(this).parent().parent().find('.shipping-cost-product-input').prop('disabled', true);
        }
      });

      this.bind();

    }

    bind() {

      $('#charge_order_shipping').on('change',function(){
        if($(this).is(':checked')) {

          $('#cancel_product_shipping_cost').prop('disabled', false);

          if(!$('#cancel_product_shipping_cost').is(':checked')) {
            $('#shipping_cost_order_input').prop('disabled', false);
            $('.shipping-cost-product-input').prop('disabled', false);
            $('.free_shipping_chkbox').prop('disabled', false);
          }

        }
      });

      $('#free_order_shipping').on('change',function(){
        if($(this).is(':checked')) {

          $('#cancel_product_shipping_cost').prop('disabled', true);

          $('#shipping_cost_order_input').prop('disabled', true);
          $('.shipping-cost-product-input').prop('disabled', true);
          $('.free_shipping_chkbox').prop('disabled', true);
        }
      });

      $('#cancel_product_shipping_cost').on('change',function(){
        if($(this).is(':checked')) {
          $('.shipping-cost-product-input').prop('disabled', true);
          $('.free_shipping_chkbox').prop('disabled', true);
        }else{
          $('.shipping-cost-product-input').prop('disabled', false);
          $('.free_shipping_chkbox').prop('disabled', false);
        }
      });

      $('.free_shipping_chkbox').on('change',function(){
        if($(this).is(':checked')) {
          $(this).parent().parent().find('.shipping-cost-product-input').prop('disabled', true);
        }else{
          $(this).parent().parent().find('.shipping-cost-product-input').prop('disabled', false);
        }
      });

      $('.shipping-method-rdobox').on('change',function(){
        let freeService = $(this).data('free-service');
        let serviceCost = $(this).data('service-cost');

        if(freeService) {
          $('#free_order_shipping').trigger('click');
        }else{
          $('#charge_order_shipping').trigger('click');
          $('#shipping_cost_order_input').val(serviceCost);
        }
        
      });

    }

    shippingMethod(freeService,serviceCost) {
      if(freeService) {
        $('#free_order_shipping').trigger('click');
      }else if(serviceCost){
        $('#charge_order_shipping').trigger('click');
        $('#shipping_cost_order_input').val(serviceCost);
      }
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