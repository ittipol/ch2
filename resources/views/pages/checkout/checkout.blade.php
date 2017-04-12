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

            @if(!empty($shippingMethods[$value['shop']['id']]))

              @foreach($shippingMethods[$value['shop']['id']] as $shippingMethod)
                <div class="shipping-method-choice">
                  <label class="choice-box">
                    <?php
                      echo Form::radio('shop['.$value['shop']['id'].'][shipping_method_id]', $shippingMethod['id'], $shippingMethod['select'], array(
                        'class' => 'shipping-method-rdobox',
                        'id' => 'shipping_method_rdobox_'.$shippingMethod['id'],
                        'data-group' => $value['shop']['id'],
                        'data-shipping-method' => $shippingMethod['id'],
                        'data-has-option' => $shippingMethod['special']['hasOption']
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

                  <div class="shipping-method-detail hide-element">

                    @if($shippingMethod['special']['hasOption'] && !empty($shippingMethod['special']['options']))
                      <div class="shipping-method-special-option space-bottom-10">
                        <h4>ระบุสาขาที่คุณต้องการเข้ารับสินค้า</h4>
                        <?php
                          echo Form::select('shop['.$value['shop']['id'].'][branch_id]', $shippingMethod['special']['options'], null, array(
                            'id' => 'special_option_'.$shippingMethod['id'],
                            'disabled' => true
                          ));
                        ?>
                      </div>
                    @endif

                    <div class="shipping-method-detail-panel"></div>
                  </div>

                  <!-- <div class="text-right">
                    <a 
                    class="button shipping-method-detail-button" 
                    data-right-side-panel="1" 
                    data-right-side-panel-target="#shipping_method_expand_panel"
                    data-group="{{$value['shop']['id']}}"
                    data-shipping-method="{{$shippingMethod['id']}}"
                    >รายละเอียดการจัดส่ง</a>
                  </div> -->

                </div>

              @endforeach

            @else

              <div>ร้านค้านี้ยังไม่ระบุวิธีการจัดส่งสินค้า</div>

            @endif

          </div>

        </div>

      @endforeach

      <div class="text-right">

        <div class="secondary-message-box error space-bottom-20">
          <div class="secondary-message-box-inner">
            <p>*** รายการสั่งซื้อหรือสินค้าบางรายการอาจยังไม่ได้รวมค่าจัดส่ง</p>
            <p>*** ค่าจัดส่งของการสั่งซื้ออาจมีการเปลี่ยนแปลงหลังจากผู้ขายได้ทำการยืนยันการสั่งซื้อ</p>
            <p>*** โปรดรอการยืนยันการสั่งซื้อจากผู้ขายก่อนการชำระเงิน</p>
          </div>
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

      <!-- <div id="shipping_method_expand_panel" class="right-size-panel filter">
        <div class="right-size-panel-inner">
          <div id="shipping_method_panel"></div>
          <div class="right-size-panel-close-button"></div>
        </div>
      </div> -->

    @else

      @include('pages.cart.cart_empty')

    @endif

  </div>

</div>

<script type="text/javascript">

  class Checkout {

    constructor() {
      this.downloaded = [];
      this.currentTarget = [];
      this.currentOption = [];
    }

    load() {

      let _this = this;

      $('.shipping-method-rdobox:checked').each(function(i, obj) {

        let group = $(this).data('group');
        let shippingMethodId = $(this).data('shipping-method');

        _this.currentTarget[group] = shippingMethodId;
        _this.getShippingMethodDetail(shippingMethodId,$(this).parent().parent().find('.shipping-method-detail'));

        if($(this).data('has-option')) {
          _this.currentOption[group] = shippingMethodId;
          $('#special_option_'+shippingMethodId).prop('disabled',false);
        }

      });

      this.bind();
    }

    bind() {

      let _this = this;

      $('.shipping-method-rdobox').on('change',function(){

        let group = $(this).data('group');
        let shippingMethodId = $(this).data('shipping-method');
        let obj = $('#shipping_method_rdobox_'+_this.currentTarget[group]).parent().parent().find('.shipping-method-detail');

        obj.slideUp(220);

        if(window.innerWidth <= 480) {
          $(document).scrollTop($(this).parent().position().top-obj.height());
        }

        _this.currentTarget[group] = shippingMethodId;
        _this.getShippingMethodDetail(shippingMethodId,$(this).parent().parent().find('.shipping-method-detail'));

        if(_this.currentOption[group]) {
          $('#special_option_'+_this.currentOption[group]).prop('disabled',true);
        }

        if($(this).data('has-option')) {
          _this.currentOption[group] = shippingMethodId;
          $('#special_option_'+shippingMethodId).prop('disabled',false);
        }

      });

      // $('.shipping-method-detail-button').on('click',function(){

      //   let group = $(this).data('group');
      //   let shippingMethodId = $(this).data('shipping-method');

      //   _this.currentTarget[group] = shippingMethodId;
      //   _this.getShippingMethodDetail(shippingMethodId,$(this).parent().parent().find('.shipping-method-detail'));

      // });

    }

    getShippingMethodDetail(shippingMethodId,obj) {

      let _this = this;

      if((typeof this.downloaded[shippingMethodId] === 'boolean') && this.downloaded[shippingMethodId]) {
        obj.slideDown(220);
      }else{

        let request = $.ajax({
          url: "/api/v1/get_shipping_method/"+shippingMethodId,
          type: "get",
          dataType:'json',
          // beforeSend: function( xhr ) {
          //   $('#shipping_method_panel').text('');
          // }
        });

        request.done(function (response, textStatus, jqXHR){

          if(response.success) {
            obj.find('.shipping-method-detail-panel').html(response.description);
            obj.slideDown(220);
            _this.downloaded[shippingMethodId] = true;

            // $('#shipping_method_panel').html(response.description);
          }
          
        });

      }

    }

  }

  $(document).ready(function(){
    const checkout = new Checkout();
    checkout.load();
  });

</script>

@stop