<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
use App\library\filterHelper;
use App\library\url;
use App\library\date;
use App\library\validation;
use App\library\notificationHelper;
use Redirect;

class OrderController extends Controller
{
  public function detail() {

    $url = new Url;

    $model = Service::loadModel('Order')->where([
      ['id','=',$this->param['id']],
      ['created_by','=',session()->get('Person.id')]
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

      $hasOrderPaymentConfirm = $model->hasOrderPaymentConfirm();

      if(!$hasOrderPaymentConfirm) {

        // $paymentMethodToOrders = $model->getRelatedData('PaymentMethodToOrder');
        // $paymentMethods = array();
        // foreach ($paymentMethodToOrders as $paymentMethodToOrder) {
        //   $paymentMethods[] = $paymentMethodToOrder->paymentMethod->buildPaginationData();
        // }

        $paymentMethods = array();
        foreach (Service::loadModel('PaymentMethod')->getPaymentMethod($model->shop_id) as $paymentMethod) {
          $paymentMethods[] = $paymentMethod->buildPaginationData();
        }

        $this->setData('paymentMethods',$paymentMethods);
        $this->setData('paymentInformUrl','order/payment/inform/'.$model->id);

      }

      $this->setData('orderConfirmMessage',$model->orderStatusMessage());

      $this->setData('hasOrderPaymentConfirm',$hasOrderPaymentConfirm);

    }

    if($model->pick_up_order) {
      // $branches = $model->getRelatedData('OrderPickUpToBranch');
      
      $_branches = array();
      foreach ($model->getRelatedData('OrderPickUpToBranch') as $branch) {

        $_branches[] = array(
          'name' => $branch->branch->name,
          'detailUrl' => $url->setAndParseUrl('shop/{shopSlug}/branch/{id}',array(
            'shopSlug' => $slug,
            'id' => $branch->branch->id,
          ))
        );

      }

      $this->setData('branches',$_branches);

    }

    $this->setData('order',$model->modelData->build(true));
    $this->setData('orderProducts',$model->getOrderProducts());
    $this->setData('orderTotals',$model->orderTotals());
    $this->setData('orderStatuses',$model->getOrderStatuses());
    $this->setData('orderShippingMethod',$model->getOrderShippingMethod());
    $this->setData('orderShippingCosts',$model->getOrderShippingCostSummary());
    $this->setData('orderHistories',$model->getOrderHistories());
    $this->setData('percent',$model->getOrderProgress());
    $this->setData('shopUrl','shop/'.$slug);

    return $this->view('pages.order.detail');

  }

  public function paymentInform() {

    $order = Service::loadModel('Order')->where([
      ['id','=',$this->param['id']],
      ['created_by','=',session()->get('Person.id')]
    ])->first();

    if(empty($order)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศนี้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    if($order->order_status_id != Service::loadModel('OrderStatus')->getIdByalias('pending-customer-payment')) {
      MessageHelper::display('การสั่งซื้อนี้ได้ยืนยันการชำระเงินแล้ว','error');
      return Redirect::to('account/order/'.$order->id);
    }

    $model = Service::loadModel('OrderPaymentConfirm');

    if($model->where('order_id','=',$order->id)->exists()) {
      MessageHelper::display('การสั่งซื้อนี้ได้ยืนยันการชำระเงินแล้ว','error');
      return Redirect::to('account/order/'.$order->id);
    }

    // $paymentMethodToOrders = $order->getRelatedData('PaymentMethodToOrder');
    // $paymentMethods = array();
    // foreach ($paymentMethodToOrders as $paymentMethodToOrder) {
    //   $paymentMethod = $paymentMethodToOrder->paymentMethod;
    //   $paymentMethods[$paymentMethod->id] = $paymentMethod->name;
    // }

    $paymentMethods = array();
    foreach (Service::loadModel('PaymentMethod')->getPaymentMethod($order->shop_id) as $_paymentMethod) {
      $paymentMethods[$_paymentMethod['id']] = $_paymentMethod['name'];
    }

    $date = new Date;

    $currentYear = date('Y');
    
    $day = array();
    $month = array();
    $year = array();

    for ($i=1; $i <= 31; $i++) { 
      $day[$i] = $i;
    }

    for ($i=1; $i <= 12; $i++) { 
      $month[$i] = $date->getMonthName($i);
    }

    for ($i=$currentYear; $i <= $currentYear + 1; $i++) { 
      $year[$i] = $i+543;
    }

    // Time
    $hours = array();
    $mins = array();

    for ($i=0; $i <= 23; $i++) { 

      if($i < 10) {
        $hours['0'.$i] = $i;
      }else{
        $hours[$i] = $i;
      }

    }

    for ($i=0; $i <= 59; $i++) {

      if($i < 10) {
        $mins['0'.$i] = '0'.$i;
      }else{
        $mins[$i] = $i;
      }
      
    }

    $this->data = $model->formHelper->build();
    $this->setData('invoiceNumber',$order->invoice_number);

    $this->setData('paymentMethods',$paymentMethods);

    $this->setData('day',$day);
    $this->setData('month',$month);
    $this->setData('year',$year);

    $this->setData('currentDay',date('j'));
    $this->setData('currentMonth',date('n'));
    $this->setData('currentYear',$currentYear);

    $this->setData('hours',$hours);
    $this->setData('mins',$mins);
    
    return $this->view('pages.order.payment_inform');
  }

  public function paymentInformSubmit(CustomFormRequest $request) {

    $order = Service::loadModel('Order')->where([
      ['id','=',$this->param['id']],
      ['created_by','=',session()->get('Person.id')]
    ])->first();

    if(empty($order)) {
      $this->error = array(
        'message' => 'ไม่พบรายการสั่งซื้อ'
      );
      return $this->error();
    }

    // if($order->order_status_id != 2) {
    //   MessageHelper::display('การสั่งซื้อนี้ได้ยืนยันการชำระเงินแล้ว','error');
    //   return Redirect::to('account/order/'.$order->id);
    // }

    $model = Service::loadModel('OrderPaymentConfirm');

    if($model->where('order_id','=',$order->id)->exists()) {
      MessageHelper::display('การสั่งซื้อนี้ได้ยืนยันการชำระเงินแล้ว','error');
      return Redirect::to('account/order/'.$order->id);
    }

    if(!Service::loadModel('PaymentMethod')->checkPaymentMethodExistById($request->get('payment_method_id'),$order->shop_id)) {
      return Redirect::back()->withErrors(['พบวิธีการชำระเงินที่เลือกไม่ถูกต้อง'])->withInput(request()->all());
    }

    // Set order id
    $model->order_id = $order->id;

    if($model->fill($request->all())->save()) {

      $notificationHelper = new NotificationHelper;
      $notificationHelper->setModel($order);
      $notificationHelper->create('order-payment-inform');

      MessageHelper::display('ยืนยันการชำระเงินเลขที่การสั่งซื้อ '.$order->invoice_number.' แล้ว','success');
      return Redirect::to('account/order/'.$order->id);
    }else{
      return Redirect::back();
    }

  }

  public function paymentDetail() {

    $model = Service::loadModel('Order')->find($this->param['id']);

    // $hasOrderPaymentConfirm = $model->hasOrderPaymentConfirm();

    $orderPaymentConfirm = $model->getRelatedData('OrderPaymentConfirm',array(
      'first' => true
    ));

    if(empty($orderPaymentConfirm)) {
      MessageHelper::display('ไม่การแจ้งการชำระเงินของการสั่งซื้อ','error');
      return Redirect::to('account/order/'.$order->id);
    }

    $orderPaymentConfirm->modelData->loadData(array(
      'json' => array('Image')
    ));

    $this->data = $orderPaymentConfirm->modelData->build();

    $this->setData('order',$model->modelData->build(true));

    $this->setData('paymentConfirmUrl',request()->get('shopUrl').'order/payment/confirm/'.$model->id);

    return $this->view('pages.order.payment_detail');

  }

  public function shopOrder() {

    $model = Service::loadModel('Order');
    $filterHelper = new FilterHelper($model);
    
    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $filters = '';
    if(!empty($this->query['fq'])) {
      $filters = $this->query['fq'];
    }

    $sort = '';
    if(!empty($this->query['sort'])) {
      $sort = $this->query['sort'];
    }

    $filterHelper->setFilters($filters);
    $filterHelper->setSorting($sort);

    $conditions = $filterHelper->buildFilters();
    $order = $filterHelper->buildSorting();

    $conditions[] = array('orders.shop_id','=',request()->get('shopId'));

    $model->paginator->criteria(array_merge(array(
      'conditions' => $conditions
    ),$order));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl(request()->get('shopUrl').'order');
    $model->paginator->setUrl(request()->get('shopUrl').'order/{id}','detailUrl');
    $model->paginator->setUrl(request()->get('shopUrl').'order/delete/{id}','deleteUrl');
    $model->paginator->setQuery('sort',$sort);
    $model->paginator->setQuery('fq',$filters);

    $searchOptions = array(
      'filters' => $filterHelper->getFilterOptions(),
      'sort' => $filterHelper->getSortingOptions()
    );

    // $displayingFilters = array(
    //   'filters' => $filterHelper->getDisplayingFilterOptions(),
    //   'sort' => $filterHelper->getDisplayingSorting()
    // );

    $this->data = $model->paginator->build();
    $this->setData('searchOptions',$searchOptions);
    // $this->setData('displayingFilters',$displayingFilters);

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
      $this->setData('orderCancelUrl',request()->get('shopUrl').'order/cancel/'.$model->id);
    }elseif($model->order_status_id == 2) {

      $hasOrderPaymentConfirm = $model->hasOrderPaymentConfirm();

      if($hasOrderPaymentConfirm) {
        $this->setData('paymentDetailUrl',request()->get('shopUrl').'order/payment/detail/'.$model->id);
        $this->setData('paymentConfirmUrl',request()->get('shopUrl').'order/payment/confirm/'.$model->id);
      }

      $this->setData('hasOrderPaymentConfirm',$hasOrderPaymentConfirm);

    }elseif($model->order_status_id < 5) {

      $nextOrderStatuses = $model->getNextOrderStatuses();
      $_orderStatuses = array();
      foreach ($nextOrderStatuses as $orderStatus) {
        $_orderStatuses[$orderStatus->id] = $orderStatus->name;
      }

      $this->setData('nextOrderStatuses',$_orderStatuses);
      $this->setData('updateOrderStatusUrl',request()->get('shopUrl').'order/status/update/'.$model->id);

    }

    if(!$hasPaymentMethod) {
      $this->setData('PaymentMethodAddUrl',request()->get('shopUrl').'payment_method');
    }

    if($model->pick_up_order) {
      
      $_branches = array();
      foreach ($model->getRelatedData('OrderPickUpToBranch') as $branch) {

        $_branches[] = array(
          'name' => $branch->branch->name
        );

      }

      $this->setData('branches',$_branches);

    }

    $this->setData('orderShippingMethod',$model->getOrderShippingMethod());
    $this->setData('orderStatuses',$model->getOrderStatuses());
    $this->setData('orderShippingCosts',$model->getOrderShippingCostSummary());
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
      MessageHelper::display('สินค้านี้ถูกยืนยันแล้ว','error');
      return Redirect::to(request()->get('shopUrl').'order');
    }

    $paymentMethodModel = Service::loadModel('PaymentMethod');
    $shippingMethodModel = Service::loadModel('ShippingMethod');

    $paymentMethodNotExist = !$paymentMethodModel->hasPaymentMethod(request()->get('shopId'));
    $shippingMethodNotExist = !$shippingMethodModel->hasShippingMethod(request()->get('shopId'));

    if($paymentMethodNotExist && $shippingMethodNotExist) {
      MessageHelper::display('ไม่พบวิธีการชำระเงินและวิธีการจัดส่ง กรุณาเพิ่มวิธีการชำระเงินและวิธีการจัดส่งในร้านค้าของคุณ','error');
      return Redirect::to('shop/'.request()->shopSlug.'/manage/product');
    }elseif($paymentMethodNotExist) {
      MessageHelper::display('ไม่พบวิธีการชำระเงิน กรุณาเพิ่มวิธีการชำระเงินในร้านค้าของคุณ','error');
      return Redirect::to('shop/'.request()->shopSlug.'/payment_method');
    }elseif($shippingMethodNotExist) {
      MessageHelper::display('ไม่พบวิธีการจัดส่ง กรุณาเพิ่มวิธีการจัดส่งในร้านค้าของคุณ','error');
      return Redirect::to('shop/'.request()->shopSlug.'/shipping_method');
    }

    // $_paymentMethods = array();
    // foreach ($paymentMethodModel->getPaymentMethod(request()->get('shopId')) as $paymentMethod) {
    //   $_paymentMethods[$paymentMethod['id']] = $paymentMethod['name'];
    // }

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

    // $this->setData('paymentMethods',$_paymentMethods);

    return $this->view('pages.order.shop_order_confirm');

  }

  public function shopOrderConfirmSubmit() {

    $model = Service::loadModel('Order')->where([
      ['id','=',$this->param['id']],
      ['shop_id','=',request()->get('shopId')]
    ])->first();

    if($model->order_status_id != 1) {
      MessageHelper::display('รายการสั่งซื้อถูกยืนยันแล้ว','error');
      return Redirect::to(request()->get('shopUrl').'order');
    }

    $validation = new Validation;

    $paymentMethodModel = Service::loadModel('PaymentMethod');
    $shippingMethodModel = Service::loadModel('ShippingMethod');

    // check input payment_method
    // if(empty(request()->get('payment_method'))) {
    //   return Redirect::back()->withErrors(['กรุณาเลือกวิธีการชำระเงินให้กับการสั่งซื้อนี้'])->withInput(request()->all());
    // }

    // check payment method has exists
    if(!empty(request()->get('payment_method'))) {
      foreach (request()->get('payment_method') as $id) {
        if(!$paymentMethodModel->checkPaymentMethodExistById($id,request()->get('shopId'))) {
          return Redirect::back()->withErrors(['พบวิธีการชำระเงินที่เลือกไม่ถูกต้อง'])->withInput(request()->all());
        }
      }
    }
    
    // check shipping method has exists
    if(empty($model->getOrderShippingMethod()) && !$shippingMethodModel->checkShippingMethodExistById(request()->get('shipping_method_id'),request()->get('shopId'))) {
      return Redirect::back()->withErrors(['พบวิธีการจัดส่งที่เลือกไม่ถูกต้อง'])->withInput(request()->all());
    }

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

        if(!empty($orderShippingCost)) {
          $model->order_free_shipping = null;
          $model->order_shipping_cost = $orderShippingCost;
        }

        foreach ($products as $product) {
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
    // $paymentMethodToOrderModel = Service::loadModel('PaymentMethodToOrder');
    // foreach (request()->get('payment_method') as $id) {
    //   $paymentMethodToOrderModel
    //   ->newInstance()
    //   ->fill(array(
    //     'payment_method_id' => $id,
    //     'order_id' => $model->id
    //   ))
    //   ->save();
    // }

    // shipping cost detail
    if(!empty(request()->get('shipping_cost_detail'))) {
      $model->shipping_cost_detail = request()->get('shipping_cost_detail');
    }

    // Update order 
    $model->order_status_id = 2;
    $model->save();

    // Add order history
    $OrderHistoryModel = Service::loadModel('OrderHistory');
    $OrderHistoryModel->order_id = $model->id;
    $OrderHistoryModel->order_status_id = $model->order_status_id;
    $OrderHistoryModel->message = request()->get('message');
    $OrderHistoryModel->save();

    $notificationHelper = new NotificationHelper;
    $notificationHelper->setModel($model);
    $notificationHelper->create('order-confirm',array(
      'sender' => array(
        'model' => 'Shop',
        'id' => request()->get('shopId')
      )
    ));

    MessageHelper::display('ยืนยันการสั่งซื้อเรียบร้อยแล้ว','success');
    return Redirect::to('shop/'.$this->param['shopSlug'].'/order/'.$model->id);

  }

  public function shopOrderCancel() {

    $model = Service::loadModel('Order')->where([
      ['id','=',$this->param['id']],
      ['shop_id','=',request()->get('shopId')]
    ])->first();

    if($model->order_status_id != 1) {
      MessageHelper::display('ยังไม่สามารถเปลี่ยนแปลงการสั่งซื้อได้','error');
      return Redirect::to(request()->get('shopUrl').'order/'.$model->id);
    }
    
    $model->order_status_id = 6;

    if($model->save()) {

      $OrderHistoryModel = Service::loadModel('OrderHistory');
      $OrderHistoryModel->order_id = $model->id;
      $OrderHistoryModel->order_status_id = $model->order_status_id;
      $OrderHistoryModel->message = request()->get('message');
      $OrderHistoryModel->save();

      $notificationHelper = new NotificationHelper;
      $notificationHelper->setModel($model);
      $notificationHelper->create('order-cancel',array(
        'sender' => array(
          'model' => 'Shop',
          'id' => request()->get('shopId')
        )
      ));

      MessageHelper::display('ยกเลิกการสั่งซื้อแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถยกเลิกการสั่งซื้อได้','error');
    }

    return Redirect::to(request()->get('shopUrl').'order/'.$model->id);

  }

  public function paymentConfirm() {

    // $model = Service::loadModel('Order')->where([
    //   ['id','=',$this->param['id']],
    //   ['shop_id','=',request()->get('shopId')]
    // ])->first();

    $model = Service::loadModel('Order')->find($this->param['id']);

    if($model->order_status_id != 2) {
      MessageHelper::display('รายการสั่งซื้อยืนยันการชำระเงินแล้ว','error');
      return Redirect::to(request()->get('shopUrl').'order');
    }

    $orderPaymentConfirm = Service::loadModel('OrderPaymentConfirm')->where('order_id','=',$this->param['id']);

    if(!$orderPaymentConfirm->exists()) {
      MessageHelper::display('ไม่สามารถยืนยันการชำระเงินได้','error');
      Redirect::to(request()->get('shopUrl').'order/'.$model->id);
    }

    $orderPaymentConfirm = $orderPaymentConfirm->first();
    $orderPaymentConfirm->confirmed = 1;
    
    if(!$orderPaymentConfirm->save()) {
      MessageHelper::display('ไม่สามารถยืนยันการชำระเงินได้','error');
      Redirect::to(request()->get('shopUrl').'order/'.$model->id);
    }

    $model->order_status_id = 3;

    if($model->save()) {

      $notificationHelper = new NotificationHelper;
      $notificationHelper->setModel($model);
      $notificationHelper->create('order-payment-confirm');

      MessageHelper::display('ยืนยันการชำระเงินเรียบร้อยแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถยืนยันการชำระเงินได้','error');
    }

    return Redirect::to(request()->get('shopUrl').'order/'.$model->id);

  }

  public function updateOrderStatus() {

    $model = Service::loadModel('Order')->where([
      ['id','=',$this->param['id']],
      ['shop_id','=',request()->get('shopId')]
    ])->first();

    if($model->order_status_id == 1 || $model->order_status_id == 2) {
      MessageHelper::display('ไม่สามารถเปลี่ยนแปลงการสั่งซื้อได้','error');
      return Redirect::to(request()->get('shopUrl').'order/'.$model->id);
    }

    $orderStatus = Service::loadModel('OrderStatus')->where([
      ['id','=',$model->order_status_id],
      ['default_value','=','1']
    ])->exists();

    if(!$orderStatus) {
      MessageHelper::display('สถานะการสั่งซื้อไม่ถูกต้อง','error');
      return Redirect::to(request()->get('shopUrl').'order/'.$model->id);
    }

    $model->order_status_id = request()->get('order_status_id');

    if($model->save()) {

      $OrderHistoryModel = Service::loadModel('OrderHistory');
      $OrderHistoryModel->order_id = $model->id;
      $OrderHistoryModel->order_status_id = $model->order_status_id;
      $OrderHistoryModel->message = request()->get('message');
      $OrderHistoryModel->save();

      $notificationHelper = new NotificationHelper;
      $notificationHelper->setModel($model);
      $notificationHelper->create('order-status-change',array(
        'sender' => array(
          'model' => 'Shop',
          'id' => request()->get('shopId')
        )
      ));

      MessageHelper::display('ยืนยันการชำระเงินเรียบร้อยแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถยืนยันการชำระเงินได้','error');
    }

    return Redirect::to(request()->get('shopUrl').'order/'.$model->id);

  }

  public function delete() {

    $model = Service::loadModel('Order')->find($this->param['id']);

    if($model->delete()) {
      MessageHelper::display('ข้อมูลถูกลบแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถลบข้อมูลนี้ได้','error');
    }

    return Redirect::to('shop/'.request()->shopSlug.'/order');
  }

}
