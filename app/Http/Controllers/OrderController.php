<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\message;
use App\library\validation;
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

    $this->setData('order',$model->modelData->build(true));
    $this->setData('orderProducts',$model->getOrderProducts());
    $this->setData('orderTotals',$model->getSummary(true));
    // $this->setData('orderTotals',$model->orderTotals());

    $this->setData('orderStatuses',$model->getOrderStatuses());
    $this->setData('percent',$model->getOrderProgress());

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

    $hasPaymentMethod = Service::loadModel('ShopRelateTo')
    ->where([
      ['shop_id','=',request()->get('shopId')],
      ['model','like','PaymentMethod']
    ])->exists();

    $this->setData('order',$model->modelData->build(true));
    $this->setData('orderProducts',$model->getOrderProducts());
    $this->setData('orderTotals',$model->getSummary(true));
    // $this->setData('orderTotals',$model->orderTotals());

    if($model->order_status_id == 1) {
      $this->setData('orderConfirmUrl',request()->get('shopUrl').'order/confirm/'.$model->id);
    }

    if(!$hasPaymentMethod) {
      $this->setData('PaymentMethodAddUrl',request()->get('shopUrl').'payment_method');
    }

    $this->setData('orderStatuses',$model->getOrderStatuses());
    $this->setData('percent',$model->getOrderProgress());

    $this->setData('hasPaymentMethod',$hasPaymentMethod);

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

    $paymentMethodModel = Service::loadModel('PaymentMethod');
    $paymentMethods = $paymentMethodModel
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $paymentMethodModel->getTable().'.id')
    ->where([
      ['shop_relate_to.model','like',$paymentMethodModel->modelName],
      ['shop_relate_to.shop_id','=',request()->get('shopId')]
    ])
    ->select($paymentMethodModel->getTable().'.id',$paymentMethodModel->getTable().'.name')
    ->orderBy($paymentMethodModel->getTable().'.name','ASC');

    if(!$paymentMethods->exists()) {
      Message::displayWithDesc('ไม่พบวิธีการชำระเงินชองคุณ','กรุณาเพิ่มวิธีการชำระเงินของคุณอย่างน้อย 1 วิธี เพื่อใช่ในการกำหนดวิธีการชำระเงินให้กับลูกค้า','error');
      return Redirect::to('shop/'.request()->shopSlug.'/payment_method/');
    }

    $_paymentMethods = array();
    foreach ($paymentMethods->get() as $paymentMethod) {
      $_paymentMethods[$paymentMethod['id']] = $paymentMethod['name'];
    }

    $this->setData('order',$model->modelData->build(true));
    $this->setData('orderProducts',$model->getOrderProducts());
    $this->setData('orderTotals',$model->getSummary(true));
    // $this->setData('orderTotals',$model->orderTotals());

    $this->setData('checkHasProductNotSetShippingCost',$model->checkHasProductNotSetShippingCost());
    $this->setData('checkHasProductHasShippingCost',$model->checkHasProductHasShippingCost());

    $this->setData('paymentMethods',$_paymentMethods);

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

    $validation = new Validation;

    // dd(request()->all());

    if(request()->get('order_shipping') == 2) {
      // free shipping
      $model->order_free_shipping = 1;
      $model->order_shipping_cost = null;
      $model->save();

      $orderProducts = Service::loadModel('OrderProduct')
      ->where('order_id','=',$model->id)
      ->get();

      foreach ($orderProducts as $orderProduct) {
        $orderProduct->shipping_calculate_from = 1;
        $orderProduct->free_shipping = 1;
        $orderProduct->shipping_cost = null;
        $orderProduct->product_shipping_amount_type_id = null;
        $orderProduct->save();
      }

    }else{

      $orderShippingCost = request()->get('shipping_cost_order_value');
      $products = request()->get('products');

      // Validation
      if(!empty($orderShippingCost) && !$validation->isCurrency($orderShippingCost)) {
        return Redirect::back()->withErrors(['จำนวนค่าจัดส่งสินค้าไม่ถูกต้อง']);
      }

      foreach ($products as $product) {
        if(empty($product['free_shipping']) && empty($product['shipping_cost'])) {
          return Redirect::back()->withErrors(['พบข้อมูลไม่ครบถ้วน']);
        }

        if(!empty($product['shipping_cost']) && !$validation->isCurrency($product['shipping_cost'])) {
          return Redirect::back()->withErrors(['จำนวนค่าจัดส่งสินค้าไม่ถูกต้อง']);
        }
      }

      if(!empty($orderShippingCost)) {
        $model->order_free_shipping = null;
        $model->order_shipping_cost = $orderShippingCost;
        $model->save();
      }

      if(!empty(request()->get('cancel_product_shipping_cost')) && (request()->get('cancel_product_shipping_cost') == 1)) {

        $orderProducts = Service::loadModel('OrderProduct')
        ->where('order_id','=',$model->id)
        ->get();

        foreach ($orderProducts as $orderProduct) {
          $orderProduct->shipping_calculate_from = 1;
          $orderProduct->free_shipping = null;
          $orderProduct->shipping_cost = 0;
          $orderProduct->product_shipping_amount_type_id = null;
          $orderProduct->save();
        
        }

      }else{

        foreach ($products as $productId => $product) {

          $orderProducts = Service::loadModel('OrderProduct')
          ->select('id')
          ->where([
            ['order_id','=',$model->id],
            ['product_id','=',$productId]
          ])
          ->first();

          $orderProducts->shipping_calculate_from = 1;
          $orderProducts->product_shipping_amount_type_id = null;

          if(!empty($product['free_shipping'])) {
            $orderProducts->free_shipping = 1;
            $orderProducts->shipping_cost = null;
          }elseif(!empty($product['shipping_cost'])){
            $orderProducts->free_shipping = null;
            $orderProducts->shipping_cost = $product['shipping_cost'];
          }else{
            $orderProducts->free_shipping = null;
            $orderProducts->shipping_cost = 0;
          }

          $orderProducts->save();

        }

      }

    }

    dd('exit');

    // cal totals
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
    $model->order_status_id = Service::loadModel('OrderStatus')->getIdByalias('pending-customer-payment');
    $model->save();

    Message::display('การสั่งซื้อถูกยืนยันแล้ว','success');
    return Redirect::to('shop/'.$this->param['shopSlug'].'/order/'.$model->id);

  }

}
