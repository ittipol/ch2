<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\message;
use Redirect;

class OrderController extends Controller
{
  public function detail() {

    $model = Service::loadModel('Order')->where([
      ['id','=',$this->param['id']],
      ['person_id','=',session()->get('Person.id')]
    ])->first();

    if(empty($model)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศนี้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    $shop = $model->shop;

    $slug = $shop->getRelatedData('Slug',array(
      'first' => true,
      'fields' => array('slug')
    ))->slug;

    $model->getOrderStatuses();

    $this->setData('order',$model->modelData->build(true));
    $this->setData('orderProducts',$model->getOrderProducts());
    $this->setData('orderTotals',$model->getSummary(true));
    // $this->setData('orderTotals',$model->orderTotals());

    $this->setData('orderStatuses',$model->getOrderStatuses());

    $this->setData('shop',$shop->modelData->build(true));
    $this->setData('shopImageUrl',$shop->getProfileImageUrl());
    $this->setData('shopCoverUrl',$shop->getCoverUrl());
    $this->setData('shopUrl','shop/'.$slug);

    return $this->view('pages.order.detail');

  }

  public function shopOrder() {

    $model = Service::loadModel('Order');
    
    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $model->paginator->criteria(array(
      'conditions' => array(
        array('shop_id','=',request()->get('shopId'))
      ),
      'order' => array('id','DESC')
    ));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('item/list');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/order/{id}','detailUrl');
    $this->data = $model->paginator->build();

    return $this->view('pages.order.shop_order');

  }

  public function shopOrderDetail() {

    $model = Service::loadModel('Order')->where([
      ['id','=',$this->param['id']],
      ['shop_id','=',request()->get('shopId')]
    ])->first();

    $this->setData('order',$model->modelData->build(true));
    $this->setData('orderProducts',$model->getOrderProducts());
    $this->setData('orderTotals',$model->getSummary(true));
    // $this->setData('orderTotals',$model->orderTotals());

    if($model->order_status_id == 1) {
      $this->setData('orderConfirmUrl',request()->get('shopUrl').'order/confirm/'.$model->id);
    }

    return $this->view('pages.order.shop_order_detail');

  }

  public function shopOrderConfirm() {

    $model = Service::loadModel('Order')->where([
      ['id','=',$this->param['id']],
      ['shop_id','=',request()->get('shopId')]
    ])->first();

    if($model->order_status_id != 1) {
      Message::display('สินค้านี้ถูกยืนยันแล้ว','error');
      return Redirect::to(request()->get('shopUrl').'order');
    }

    // Get Payment method
    $model->formHelper->loadFieldData('PaymentMethod',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'paymentMethods',
      'order' => array(
        array('id','ASC')
      )
    ));

    $this->setData('paymentMethods',$model->formHelper->getFieldDataByIndex('paymentMethods'));

    $this->setData('order',$model->modelData->build(true));
    $this->setData('orderProducts',$model->getOrderProducts());
    $this->setData('orderTotals',$model->getSummary(true));
    // $this->setData('orderTotals',$model->orderTotals());

    $this->setData('hasProductNotSetShippingCost',$model->checkHasProductNotSetShippingCost());
    $this->setData('hasAllProductNotSetShippingCost',$model->checkHasAllProductNotSetShippingCost());

    return $this->view('pages.order.shop_order_confirm');

  }

  public function shopOrderConfirmSubmit() {

    $model = Service::loadModel('Order')->where([
      ['id','=',$this->param['id']],
      ['shop_id','=',request()->get('shopId')]
    ])->first();

    if($model->order_status_id != 1) {
      Message::display('สินค้านี้ถูกยืนยันแล้ว','error');
      return Redirect::to(request()->get('shopUrl').'order');
    }

    switch (request()->get('shipping_cost_type')) {
      case 1:

        $model->order_shipping_cost = request()->get('shipping_cost_order');
        $model->save();

        if(!empty(request()->get('cancel_product_shipping_cost')) && (request()->get('cancel_product_shipping_cost') == 1)) {

          $orderProducts = Service::loadModel('OrderProduct')
          ->where([
            ['order_id','=',$model->id],
            ['shipping_calculate_from','=',2]
          ])
          ->get();

          foreach ($orderProducts as $orderProduct) {
            $orderProduct->shipping_calculate_from = 1;
            $orderProduct->free_shipping = null;
            $orderProduct->shipping_cost = null;
            $orderProduct->product_shipping_amount_type_id = null;
            $orderProduct->save();
          }

        }

        break;
      
      case 2:

        $shippingCostProduct = request()->get('shipping_cost_product');
        
        $orderProducts = Service::loadModel('OrderProduct')
        ->where([
          ['order_id','=',$model->id],
          ['shipping_calculate_from','=',1]
        ])
        ->get();

        foreach ($orderProducts as $orderProduct) {

          if(empty($shippingCostProduct[$orderProduct->id])) {
            continue;
          }

          $orderProduct->shipping_cost = $shippingCostProduct[$orderProduct->id];
          $orderProduct->save();

        }

        break;
    }

    // cal total data
    $orderTotalModel = Service::loadModel('OrderTotal');

    $totals = $model->getSummary();

    foreach ($totals as $alias => $total) {

      $orderTotal = $orderTotalModel
      ->newInstance()
      ->where([
        ['order_id','=',$model->id],
        ['alias','like',$alias]
      ])
      ->first();

      $orderTotal->value = $total['value'];
      $orderTotal->save();

    }

    // Add payment method to order 
    $paymentMethodToOrderModel = Service::loadModel('PaymentMethodToOrder');
    if(!empty(request()->get('payment_method'))) {
      foreach (request()->get('payment_method') as $paymentMethodId) {
        $paymentMethodToOrderModel
        ->newInstance()
        ->fill(array(
          'payment_method_id' => $paymentMethodId,
          'order_id' => $model->id
        ))
        ->save();
      }
    }

    // 
    $model->order_status_id = 2;
    $model->save();

    Message::display('การสั่งซื้อถูกยืนยันแล้ว','success');
    return Redirect::to('shop/'.$this->param['shopSlug'].'/order/'.$model->id);

  }

}
