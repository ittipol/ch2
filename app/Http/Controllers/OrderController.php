<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
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

    if($model->order_status_id == 2) {

      $paymentMethodToOrders = $model->getRelatedData('PaymentMethodToOrder');
      $paymentMethods = array();
      foreach ($paymentMethodToOrders as $paymentMethodToOrder) {
        $paymentMethod = $paymentMethodToOrder->paymentMethod;
        $paymentMethods[] = array(
          'name' => $paymentMethod->name,
          'url' => 'shop/'.$slug.'/payment_method/'.$paymentMethod->id
        );
      }

      $this->setData('paymentMethods',$paymentMethods);
      $this->setData('paymentConfirmUrl','order/payment/confirm/'.$model->id);

    }

    $this->setData('order',$model->modelData->build(true));
    $this->setData('orderProducts',$model->getOrderProducts());
    // $this->setData('orderTotals',$model->getSummary(true));
    $this->setData('orderTotals',$model->orderTotals());

    $this->setData('orderStatuses',$model->getOrderStatuses());
    $this->setData('percent',$model->getOrderProgress());

    // Get Shipping cost of this order
    $this->setData('orderShippingCosts',$model->getOrderShippingCostSummary());
 
    // $this->setData('shop',$shop->modelData->build(true));
    // $this->setData('shopImageUrl',$shop->getProfileImageUrl());
    // $this->setData('shopCoverUrl',$shop->getCoverUrl());
    $this->setData('shopUrl','shop/'.$slug);

    return $this->view('pages.order.detail');

  }

  public function paymentConfirm() {

    $order = Service::loadModel('Order')->where([
      ['id','=',$this->param['id']],
      ['person_id','=',session()->get('Person.id')]
    ])->first();

    if(empty($order)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศนี้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    if($order->order_status_id != Service::loadModel('OrderStatus')->getIdByalias('pending-customer-payment')) {
      Message::display('การสั่งซื้อนี้ได้ยืนยันการชำระเงินแล้ว','error');
      return Redirect::to('account/order/'.$order->id);
    }

    $model = Service::loadModel('OrderPaymentConfirm');

    if($model->where('order_id','=',$order->id)->exists()) {
      Message::display('การสั่งซื้อนี้ได้ยืนยันการชำระเงินแล้ว','error');
      return Redirect::to('account/order/'.$order->id);
    }

    $paymentMethodToOrders = $order->getRelatedData('PaymentMethodToOrder');
    $paymentMethods = array();
    foreach ($paymentMethodToOrders as $paymentMethodToOrder) {
      $paymentMethod = $paymentMethodToOrder->paymentMethod;
      $paymentMethods[$paymentMethod->id] = $paymentMethod->name;
    }

    $this->data = $model->formHelper->build();
    $this->setData('invoiceNumber',$order->invoice_number);

    $this->setData('paymentMethods',$paymentMethods);
    
    return $this->view('pages.order.payment_confirm');
  }

  public function paymentConfirmSubmit(CustomFormRequest $request) {

    $order = Service::loadModel('Order')->where([
      ['id','=',$this->param['id']],
      ['person_id','=',session()->get('Person.id')]
    ])->first();

    if(empty($order)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศนี้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    if($order->order_status_id != Service::loadModel('OrderStatus')->getIdByalias('pending-customer-payment')) {
      Message::display('การสั่งซื้อนี้ได้ยืนยันการชำระเงินแล้ว','error');
      return Redirect::to('account/order/'.$order->id);
    }

    $model = Service::loadModel('OrderPaymentConfirm');

    if($model->where('order_id','=',$order->id)->exists()) {
      Message::display('การสั่งซื้อนี้ได้ยืนยันการชำระเงินแล้ว','error');
      return Redirect::to('account/order/'.$order->id);
    }

    // Set order id
    $model->order_id = $order->id;

    if($model->fill($request->all())->save()) {
      Message::display('ยืนยันการชำระเงินแล้ว','success');
      return Redirect::to('account/order/'.$order->id);
    }else{
      return Redirect::back();
    }

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
    // $this->setData('orderTotals',$model->getSummary(true));
    $this->setData('orderTotals',$model->orderTotals());

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
    // $paymentMethods = $paymentMethodModel
    // ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $paymentMethodModel->getTable().'.id')
    // ->where([
    //   ['shop_relate_to.model','like',$paymentMethodModel->modelName],
    //   ['shop_relate_to.shop_id','=',request()->get('shopId')]
    // ])
    // ->select($paymentMethodModel->getTable().'.id',$paymentMethodModel->getTable().'.name')
    // ->orderBy($paymentMethodModel->getTable().'.name','ASC');

    if(!$paymentMethodModel->hasPaymentMethod(request()->get('shopId'))) {
      Message::displayWithDesc('ไม่พบวิธีการชำระเงินชองคุณ','กรุณาเพิ่มวิธีการชำระเงินของคุณอย่างน้อย 1 วิธี เพื่อใช่ในการกำหนดวิธีการชำระเงินให้กับลูกค้า','error');
      return Redirect::to('shop/'.request()->shopSlug.'/payment_method/');
    }

    $_paymentMethods = array();
    foreach ($paymentMethodModel->getPaymentMethod(request()->get('shopId')) as $paymentMethod) {
      $_paymentMethods[$paymentMethod['id']] = $paymentMethod['name'];
    }

    $this->setData('order',$model->modelData->build(true));
    $this->setData('orderProducts',$model->getOrderProducts());
    // $this->setData('orderTotals',$model->getSummary(true));
    $this->setData('orderTotals',$model->orderTotals());

    $this->setData('hasProductNotSetShippingCost',$model->checkHasProductNotSetShippingCost());
    $this->setData('hasProductHasShippingCost',$model->checkHasProductHasShippingCost());

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

    // check has payment_method
    if(empty(request()->get('payment_method'))) {
      return Redirect::back()->withErrors(['กรุณาเลือกวิธีการชำระเงินอย่างน้อย 1 วิธีให้กับการสั่งซื้อนี้'])->withInput(request()->all());
    }

    if(request()->get('order_shipping') == 2) {
      // free shipping
      $model->order_free_shipping = 1;
      $model->order_shipping_cost = null;
      // $model->save();

      $orderProducts = Service::loadModel('OrderProduct')
      ->where('order_id','=',$model->id)
      ->get();

      foreach ($orderProducts as $orderProduct) {
        $orderProduct->free_shipping = 1;
        $orderProduct->shipping_cost = null;
        $orderProduct->save();
      }

    }else{

      $orderShippingCost = request()->get('shipping_cost_order_value');
      $products = request()->get('products');

      // Validation
      if(!empty($orderShippingCost) && !$validation->isCurrency($orderShippingCost)) {
        return Redirect::back()->withErrors(['จำนวนค่าจัดส่งสินค้าไม่ถูกต้อง'])->withInput(request()->all());
      }
      // ###

      if(!empty(request()->get('cancel_product_shipping_cost')) && (request()->get('cancel_product_shipping_cost') == 1)) {
        // cancel all
        $orderProducts = Service::loadModel('OrderProduct')
        ->where('order_id','=',$model->id)
        ->get();

        foreach ($orderProducts as $orderProduct) {
          $orderProduct->free_shipping = null;
          $orderProduct->shipping_cost = 0;
          $orderProduct->save();
        }

      }else{

        foreach ($products as $product) {
          if(empty($product['free_shipping']) && empty($product['shipping_cost'])) {
            return Redirect::back()->withErrors(['พบข้อมูลไม่ครบถ้วน'])->withInput(request()->all());
          }

          if(!empty($product['shipping_cost']) && !$validation->isCurrency($product['shipping_cost'])) {
            return Redirect::back()->withErrors(['จำนวนค่าจัดส่งสินค้าไม่ถูกต้อง'])->withInput(request()->all());
          }
        }

        foreach ($products as $productId => $product) {

          $orderProduct = Service::loadModel('OrderProduct')
          ->select('id')
          ->where([
            ['order_id','=',$model->id],
            ['product_id','=',$productId]
          ])
          ->first();

          if(!empty($product['free_shipping'])) {
            $orderProduct->free_shipping = 1;
            $orderProduct->shipping_cost = null;
          }elseif(!empty($product['shipping_cost'])){
            $orderProduct->free_shipping = null;
            $orderProduct->shipping_cost = $product['shipping_cost'];
          }else{
            $orderProduct->free_shipping = null;
            $orderProduct->shipping_cost = 0;
          }

          $orderProduct->save();

        }

      }

    }

    // order shipping cost
    if(!empty($orderShippingCost)) {
      $model->order_free_shipping = null;
      $model->order_shipping_cost = $orderShippingCost;
      $model->save();
    }

    // update order product
    $orderProducts = Service::loadModel('OrderProduct')
    ->where('order_id','=',$model->id)
    ->get();

    foreach ($orderProducts as $orderProduct) {
      $orderProduct->total = $orderProduct->getOrderTotal();
      $orderProduct->save();
    }

    // update order totals
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

    // shipping cost detail
    if(!empty(request()->get('shipping_cost_detail'))) {
      $model->shipping_cost_detail = request()->get('shipping_cost_detail');
    }

    // 
    $model->order_status_id = Service::loadModel('OrderStatus')->getIdByalias('pending-customer-payment');
    $model->save();

    Message::display('การสั่งซื้อถูกยืนยันแล้ว','success');
    return Redirect::to('shop/'.$this->param['shopSlug'].'/order/'.$model->id);

  }

}
