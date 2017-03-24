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
    $this->setData('orderTotals',$model->orderTotals());
    $this->setData('orderStatuses',$model->getOrderStatuses());
    $this->setData('orderShippingMethod',$model->getOrderShippingMethod());
    $this->setData('orderShippingCosts',$model->getOrderShippingCostSummary());

    $this->setData('percent',$model->getOrderProgress());

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
    $this->setData('orderTotals',$model->orderTotals());

    if($model->order_status_id == 1) {
      $this->setData('orderConfirmUrl',request()->get('shopUrl').'order/confirm/'.$model->id);
    }

    if(!$hasPaymentMethod) {
      $this->setData('PaymentMethodAddUrl',request()->get('shopUrl').'payment_method');
    }

    $this->setData('orderShippingMethod',$model->getOrderShippingMethod());
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
    $shippingMethodModel = Service::loadModel('ShippingMethod');

    $paymentMethodNotExist = !$paymentMethodModel->hasPaymentMethod(request()->get('shopId'));
    $shippingMethodNotExist = !$shippingMethodModel->hasShippingMethod(request()->get('shopId'));

    if($paymentMethodNotExist && $shippingMethodNotExist) {
      Message::display('ไม่พบวิธีการชำระเงินและวิธีการจัดส่ง กรุณาเพิ่มวิธีการชำระเงินและวิธีการจัดส่งในร้านค้าของคุณ','error');
      return Redirect::to('shop/'.request()->shopSlug.'/product');
    }elseif($paymentMethodNotExist) {
      Message::display('ไม่พบวิธีการชำระเงิน กรุณาเพิ่มวิธีการชำระเงินในร้านค้าของคุณ','error');
      return Redirect::to('shop/'.request()->shopSlug.'/payment_method');
    }elseif($shippingMethodNotExist) {
      Message::display('ไม่พบวิธีการจัดส่ง กรุณาเพิ่มวิธีการจัดส่งในร้านค้าของคุณ','error');
      return Redirect::to('shop/'.request()->shopSlug.'/shipping_method');
    }

    $_paymentMethods = array();
    foreach ($paymentMethodModel->getPaymentMethod(request()->get('shopId')) as $paymentMethod) {
      $_paymentMethods[$paymentMethod['id']] = $paymentMethod['name'];
    }

    $orderShippingMethod = $model->getOrderShippingMethod();
    if(empty($orderShippingMethod)) {
      $this->setData('shippingMethods',$shippingMethodModel->getShippingMethodChoice(request()->get('shopId')));
    }

    $this->setData('order',$model->modelData->build(true));
    $this->setData('orderProducts',$model->getOrderProducts());
    $this->setData('orderTotals',$model->orderTotals());
    $this->setData('orderShippingMethod',$orderShippingMethod);

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

    $paymentMethodModel = Service::loadModel('PaymentMethod');
    $shippingMethodModel = Service::loadModel('ShippingMethod');

    // check input payment_method
    if(empty(request()->get('payment_method'))) {
      return Redirect::back()->withErrors(['กรุณาเลือกวิธีการชำระเงินให้กับการสั่งซื้อนี้'])->withInput(request()->all());
    }
    // check payment method has exists
    foreach (request()->get('payment_method') as $id) {
      if(!$paymentMethodModel->checkPaymentMethodExistById($id,request()->get('shopId'))) {
        return Redirect::back()->withErrors(['พบวิธีการชำระเงินที่เลือกไม่ถูกต้อง'])->withInput(request()->all());
      }
    }
    // check shipping method has exists
    if(empty($model->getOrderShippingMethod()) && !$shippingMethodModel->checkShippingMethodExistById(request()->get('shipping_method_id'),request()->get('shopId'))) {
      return Redirect::back()->withErrors(['พบวิธีการจัดส่งที่เลือกไม่ถูกต้อง'])->withInput(request()->all());
    }
    
    // if(empty($model->getOrderShippingMethod()) && $shippingMethodModel->checkShippingMethodExistById(request()->get('shipping_method_id'),request()->get('shopId'))) {
    //   // get shipping method
    //   $shippingMethod = $shippingMethodModel->find(request()->get('shipping_method_id'));
    //   // save shipping method
    //   Service::loadModel('OrderShipping')
    //   ->fill(array(
    //     'order_id' => $model->id,
    //     'shipping_method_id' => $shippingMethod->id,
    //     'shipping_method_name' => $shippingMethod->name,
    //     'shipping_service_id' => $shippingMethod->shipping_service_id,
    //     'shipping_service_cost_type_id' => $shippingMethod->shipping_service_cost_type_id,
    //     'shipping_time' => $shippingMethod->shipping_time
    //   ))
    //   ->save();
    // }

    if(request()->get('order_shipping') == 2) {
      // free shipping
      $model->order_free_shipping = 1;
      $model->order_shipping_cost = null;

      $orderProducts = Service::loadModel('OrderProduct')
      ->where('order_id','=',$model->id)
      ->get();

      foreach ($orderProducts as $orderProduct) {
        $orderProduct->free_shipping = 1;
        $orderProduct->shipping_cost = null;
        $orderProduct->save();
      }

    }else{

      $orderShippingCost = request()->get('order_shipping_cost');
      $products = request()->get('products');

      if(!empty($orderShippingCost) && !$validation->isCurrency($orderShippingCost)) {
        return Redirect::back()->withErrors(['จำนวนค่าจัดส่งสินค้าไม่ถูกต้อง'])->withInput(request()->all());
      }

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
          // if(empty($product['free_shipping']) && empty($product['shipping_cost'])) {
          //   return Redirect::back()->withErrors(['พบข้อมูลไม่ครบถ้วน'])->withInput(request()->all());
          // }

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

    if(empty($model->getOrderShippingMethod())) {
      // get shipping method
      $shippingMethod = $shippingMethodModel->find(request()->get('shipping_method_id'));
      // save shipping method to order
      Service::loadModel('OrderShipping')
      ->fill(array(
        'order_id' => $model->id,
        'shipping_method_id' => $shippingMethod->id,
        'shipping_method_name' => $shippingMethod->name,
        'shipping_service_id' => $shippingMethod->shipping_service_id,
        'shipping_service_cost_type_id' => $shippingMethod->shipping_service_cost_type_id,
        'shipping_time' => $shippingMethod->shipping_time
      ))
      ->save();
    }

    // Add payment method to order 
    $paymentMethodToOrderModel = Service::loadModel('PaymentMethodToOrder');
    foreach (request()->get('payment_method') as $id) {
      $paymentMethodToOrderModel
      ->newInstance()
      ->fill(array(
        'payment_method_id' => $id,
        'order_id' => $model->id
      ))
      ->save();
    }

    // shipping cost detail
    if(!empty(request()->get('shipping_cost_detail'))) {
      $model->shipping_cost_detail = request()->get('shipping_cost_detail');
    }

    // 
    $model->order_status_id = Service::loadModel('OrderStatus')->getIdByalias('pending-customer-payment');
    $model->save();
dd('pppp');
    Message::display('การสั่งซื้อถูกยืนยันแล้ว','success');
    return Redirect::to('shop/'.$this->param['shopSlug'].'/order/'.$model->id);

  }

}
