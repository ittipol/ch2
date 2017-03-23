@extends('layouts.blackbox.main')
@section('content')

<div class="top-header-wrapper top-header-border">
  <div class="container">
    <div class="top-header">
      <div class="detail-title">
        <h2 class="title">สั่งซื้อสินค้า</h2>
      </div>
    </div>
  </div>
</div>

<div class="container">

  <div id="cart_panel" class="checkout">

    @if(!empty($data))

      @include('components.form_error') 

      <?php 
        echo Form::open(['id' => 'main_form','method' => 'post', 'enctype' => 'multipart/form-data']);
      ?>

      @foreach($data as $value)

        <div id="_shop_{{$value['shop']['id']}}" class="checkout-wrapper">

          <label class="choice-box">
            <?php
              echo Form::checkbox('shop['.$value['shop']['id'].'][checkout]', 1, true);
            ?>
            <div class="inner">สั่งซื้อสินค้าในร้าน <strong>{{$value['shop']['name']}}</strong></div>
          </label>

          <div class="row">
            <div class="col-sm-6 col-sm-12">
              <h5 class="shop-info">{{$value['shop']['name']}}</h5>
            </div>
          </div>

          <div class="row">

            <div class="col-sm-6 col-sm-12">

              <div class="checkout-list">
                @foreach($value['products'] as $product)
                <div id="_product_{{$product['id']}}" class="checkout-list-table-row" data-id="_shop_{{$value['shop']['id']}}">

                  <div class="product-list-box clearfix">

                    <div class="product-image pull-left">
                      <a href="{{$product['productDetailUrl']}}">
                        <img src="{{$product['imageUrl']}}">
                      </a>
                    </div>

                    <div class="col-xs-8 product-info">

                      <a href="{{$product['productDetailUrl']}}">
                        <h4 class="product-title">{{$product['name']}}</h4>                      
                      </a>


                      @if(!empty($product['hasError']))
                        <p class="product-error-message">
                          {{$product['errorMessage']}}
                        </p>
                      @endif

                      <div>
                        ราคาสินค้ารวม: <span class="product-price">{{$product['price']}}</span> x {{$product['quantity']}}
                      </div>
                      @if($product['shipping_calculate_from'] == 2)
                      <div>
                        ค่าจัดส่งสินค้า: <span class="product-price">{{$product['shippingCost']}}</span>
                      </div>
                      @endif
                      <div>
                        มูลค่าสินค้า: <span class="product-price">{{$product['total']}}</span>
                      </div>

                    </div>

                  </div>

                </div>
                @endforeach
              </div>

              <div class="checkout-summary clearfix">

                <div class="pull-right">
              
                  <div class="text-right">
                    <h5>มูลค่าสินค้า: <span class="sub-total amount">{{$value['summaries']['subTotal']['value']}}</span></h5>
                  </div>
                  <div class="text-right">
                    <h5>ค่าจัดส่งสินค้า: <span class="shipping-cost amount">{{$value['summaries']['shippingCost']['value']}}</span></h5>
                  </div>
                  <div class="text-right">
                    <h4>ยอดสุทธิ: <span class="total-amount amount product-price">{{$value['summaries']['total']['value']}}</span></h4>
                  </div>

                </div>

              </div>

            </div>

            <div class="col-sm-6 col-sm-12">

              <div class="checkout-content-right">

                <div class="checkout-input">

                  <div class="address-input">
                    <h5 class="required">ที่อยู่สำหรับการจัดส่ง</h5>
                    <input type="text" name="shop[{{$value['shop']['id']}}][shipping_address]" value="{{$shippingAddress}}">
                  </div>

                  <div class="message-input">
                    <h5>ข้อความถึงผู้ขาย</h5>
                    <textarea name="shop[{{$value['shop']['id']}}][message]"></textarea>
                  </div>

                </div>

              </div>

            </div>

          </div>

          <div class="shipping-method-input space-top-30">
            <h5>เลือกวิธีการจัดส่งสินค้า</h5>
            <!-- <div class="line space-top-bottom-10"></div> -->

            @if(!empty($shippingMethods[$value['shop']['id']]))

              @foreach($shippingMethods[$value['shop']['id']] as $shippingMethod)
                <div class="shipping-method-choice">
                  <label class="choice-box">
                    <?php
                      echo Form::radio('shop['.$value['shop']['id'].'][shipping_method_id]', $shippingMethod['id'], $shippingMethod['select']);
                    ?> 
                    <div class="inner">
                      <div class="row">

                        <div class="col-md-4 col-xs-12">
                          <h5>{{$shippingMethod['name']}}</h5>
                          <p>ผู้ให้บริการ: <strong>{{$shippingMethod['shippingService']}}</strong></p>
                        </div>

                        <div class="col-md-4 col-xs-12">
                          <p>รูปแบบการคิดค่าจัดส่ง: <strong>{{$shippingMethod['shippingServiceCostType']}}</strong></p>
                          <p>ค่าจัดส่ง: <strong>{{$shippingMethod['serviceCostText']}}</strong></p>
                        </div>

                        <div class="col-md-4 col-xs-12">
                          <p>ระยะเวลาจัดส่ง: <strong>{{$shippingMethod['shipping_time']}}</strong></p>
                        </div>

                      </div>
                    </div>
                  </label>
                </div>
              @endforeach

            @else

              <div>ร้านค้านี้ยังไม่ระบุวิธีการจัดส่งสินค้า</div>

            @endif

          </div>

        </div>

      @endforeach

      <div class="text-right">

        <div class="checkout-notice">
          <p class="error-message text-bold">*** รายการสั่งซื้อหรือสินค้าบางรายการอาจยังไม่ได้รวมค่าจัดส่ง</p>
          <p class="error-message text-bold">*** ค่าจัดส่งของการสั่งซื้ออาจมีการเปลี่ยนแปลงหลังจากผู้ขายได้ทำการยืนยันการสั่งซื้อ</p>
          <p class="error-message text-bold">*** โปรดรอการยืนยันการสั่งซื้อจากผู้ขายก่อนการชำระเงิน</p>
        </div>

        <?php
          echo Form::submit('สั่งซื้อสินค้า' , array(
            'class' => 'button'
          ));
        ?>
      </div>

      <?php
        echo Form::close();
      ?>

    @else

      @include('pages.cart.cart_empty')

    @endif

  </div>

</div>

@stop