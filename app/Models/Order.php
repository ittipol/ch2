<?php

namespace App\Models;

use App\library\currency;
use App\library\cache;
use App\library\url;
use App\library\date;

class Order extends Model
{
  protected $table = 'orders';
  protected $fillable = ['invoice_prefix','invoice_number','shop_id','created_by','person_name','shipping_address','payment_detail','customer_message','order_status_id','order_free_shipping','order_shipping_cost','shipping_cost_detail','pick_up_order'];
  protected $modelRelations = array('OrderProduct','OrderTotal','OrderShipping','OrderPaymentConfirm','OrderHistory','PaymentMethodToOrder');

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  protected $filterOptions = array(
    'orderStatus' => array(
      'input' => 'checkbox',
      'title' => 'สถานะการสั่งซื้อ',
      'options' => array(
        array(
          'name' => 'รอผู้ขายยืนยันการสั่งซื้อ',
          'value' => 'order_status_id:1',
        ),
        array(
          'name' => 'รอลูกค้าชำระเงิน',
          'value' => 'order_status_id:2',
        ),
        array(
          'name' => 'จัดเตรียมสินค้า',
          'value' => 'order_status_id:3',
        ),
        array(
          'name' => 'จัดส่งสินค้า',
          'value' => 'order_status_id:4',
        ),
        array(
          'name' => 'บิลถูกปิด',
          'value' => 'order_status_id:5',
        )
      )
    ),
    'shipping' => array(
      'input' => 'checkbox',
      'title' => 'การจัดส่ง',
      'options' => array(
        array(
          'name' => 'รายการสั่งซื้อที่ไม่คิดค่าจัดส่ง',
          'value' => 'order_free_shipping:1',
        ),
        array(
          'name' => 'รายการสั่งซื้อที่ต้องการรับสินค้าเอง',
          'value' => 'pick_up_order:1',
        )
      )
    )
  );

  protected $sortingFields = array(
    'title' => 'จัดเรียงตาม',
    'options' => array(
      array(
        'name' => 'ตัวอักษร A - Z ก - ฮ',
        'value' => 'name:asc'
      ),
      array(
        'name' => 'ตัวอักษร Z - A ฮ - ก',
        'value' => 'name:desc'
      ),
      array(
        'name' => 'วันที่เก่าที่สุดไปหาใหม่ที่สุด',
        'value' => 'created_at:asc'
      ),
      array(
        'name' => 'วันที่ใหม่ที่สุดไปหาเก่าที่สุด',
        'value' => 'created_at:desc'
      ),
    ),
    'default' => 'created_at:desc'
  );

  public function orderStatus() {
    return $this->hasOne('App\Models\OrderStatus','id','order_status_id');
  }

  public function shop() {
    return $this->hasOne('App\Models\Shop','id','shop_id');
  }

  public function getInvoicePrefix() {
    return 'INV';
  }

  public function getInvoiceNumber($shopId) {

    $latestOrder = $this
    ->select('id','invoice_number')
    ->orderBy('id','DESC')
    ->where('shop_id','=',$shopId)
    ->first();

    if(empty($latestOrder)) {
      return 1;
    }

    return $latestOrder->invoice_number + 1;

  }

  public function getOrderProducts() {

    $cache = new Cache;
    $url = new Url;

    $orderProducts = $this->getRelatedData('OrderProduct');
  
    $_orderProducts = array();
    foreach ($orderProducts as $orderProduct) {

      $imageUrl = null;
      if(!empty($orderProduct->product)) {
        $imageUrl = $orderProduct->product->getImage('sm');
      }

      $_orderProducts[] = array_merge($orderProduct->buildModelData(),array(
        'imageUrl' => $imageUrl,
        'productDetailUrl' => $url->setAndParseUrl('product/detail/{product_id}',array('product_id' => $orderProduct->product_id))
      ));

    }

    return $_orderProducts;
  }

  public function orderTotals() {
    $orderTotals = $this->getRelatedData('OrderTotal',array(
      'conditions' => array(
        array('alias','!=','savingPrice')
      )
    ));

    $_orderTotals = array();
    foreach ($orderTotals as $orderTotal) {
      $_orderTotals[$orderTotal->alias] = $orderTotal->buildModelData();
    }

    return $_orderTotals;
  }

  public function getOrderStatuses() {

    $orderStatusModel = new OrderStatus;
    $orderStatuses = $orderStatusModel->GetDefaultStatuses(true);
    $total = count($orderStatuses);

    $count = 0;
    $passed = true;
    $_orderStatuses = array();
    foreach ($orderStatuses as $orderStatus) {

      $position = '';
      $count++;

      if($this->order_status_id == $orderStatus['id']) {

        if($count == $total) {
          $position = 'passed';
        }else{
          $position = 'current';
          $passed = false;
        }

      }elseif($passed) {
        $position = 'passed';
      }

      $_orderStatuses[] = array(
        'id' => $orderStatus['id'],
        'name' => $orderStatus['name'],
        'alias' => $orderStatus['alias'],
        'position' => $position
      );
    }

    return $_orderStatuses;

  }

  public function getOrderProgress() {

    $orderStatusModel = new OrderStatus;
    $total = $orderStatusModel->countDefaultStatus();
    $orderStatus = $orderStatusModel->find($this->order_status_id);
  
    if($orderStatus->sort == $total) {
      $percent = 100;
    }else{
      $percent = (($orderStatus->sort * 100) / $total) - 10;
    }

    return $percent;

  }

  public function countProduct() {
    return OrderProduct::where('order_id','=',$this->id)->count();
  }

  public function countProductQuantity() {
    $products = $this->getRelatedData('OrderProduct',array(
      'fields' => array('quantity')
    ));

    if(empty($products)) {
      return 0;
    }

    $count = 0;
    foreach ($products as $product) {
      $count += $product->quantity;
    }

    return $count;
  }

  public function getSummary($format = false) {

    $cart = new Cart;

    $summaries = array(
      'subTotal' => 'getOrderSubTotal',
      'shippingCost' => 'getOrderShippingCost',
      'savingPrice' => 'getOrderSavingPrice',
      'total' => 'getOrderTotal'
    );

    $orderProducts = $this->getRelatedData('OrderProduct');

    $_summaries = array();
    foreach ($summaries as $alias => $fx) {
      $_summaries[$alias] = array(
        'value' => $this->{$fx}($orderProducts,$format),
        'title' => $cart->getTitle($alias),
        'class' => $cart->getClass($alias),
      );
    }

    return $_summaries;

  }

  public function getOrderSubTotal($orderProducts,$format) {

    $currency = new Currency;

    $subTotal = 0;
    foreach ($orderProducts as $orderProduct) {
      $subTotal += $orderProduct->getSubTotal();
    }

    if($format) {
      return $currency->format($subTotal);
    }

    return $subTotal;

  }

  public function getOrderShippingCost($orderProducts,$format) {

    $currency = new Currency;

    $shippingCost = 0;

    if(!empty($this->order_shipping_cost)) {
      $shippingCost = $this->order_shipping_cost;
    }

    foreach ($orderProducts as $orderProduct) {
      $shippingCost += $orderProduct->getOrderShippingCost();
    }

    if($format) {
      return $currency->format($shippingCost);
    }

    return $shippingCost;

  }

  public function getOrderSavingPrice($orderProducts,$format) {

    $currency = new Currency;

    $savingPrice = 0;

    foreach ($orderProducts as $orderProduct) {
      $savingPrice += $orderProduct->getOrderSavingPrice();
    }

    if($format) {
      return $currency->format($savingPrice);
    }

    return $savingPrice;

  }

  public function getOrderTotal($orderProducts,$format) {

    $currency = new Currency;

    $total = 0;
    foreach ($orderProducts as $orderProduct) {
      $total += $orderProduct->getOrderTotal();
    }

    // $total += $this->getOrderShippingCost($orderProducts,$format);

    if(!empty($this->order_shipping_cost)) {
      $total += $this->order_shipping_cost;
    }

    // foreach ($orderProducts as $orderProduct) {
    //   $total += $orderProduct->getOrderShippingCost();
    // }

    if($format) {
      return $currency->format($total);
    }

    return $total;

  }

  public function getOrderHistories() {

    $orderhistories = $this->getRelatedData('OrderHistory');

    $_orderhistories = array();
    foreach ($orderhistories as $orderhistory) {
      $_orderhistories[] = $orderhistory->buildModelData();
    }

    return $_orderhistories;

  }

  public function checkHasProductNotSetShippingCost() {
    return OrderProduct::where('order_id','=',$this->id)
    ->whereNull('free_shipping')
    ->whereNull('shipping_cost')
    ->exists();
  }

  public function checkHasProductHasShippingCost() {
    return OrderProduct::where('order_id','=',$this->id)
    ->where(function($query) {
      $query->whereNotNull('free_shipping')
            ->orWhereNotNull('shipping_cost');
    })
    ->exists();
  }

  public function hasOrderPaymentConfirm() {
    return OrderPaymentConfirm::where('order_id','=',$this->id)->exists();
  }

  public function checkAllProductsHaveShippingCost() {

    $total = OrderProduct::where([
      ['order_id','=',$this->id]
    ])->count();

    $totalProductNotSetShippingCost = OrderProduct::where('order_id','=',$this->id)
    ->where(function($query) {
      $query->whereNotNull('free_shipping')
            ->orWhereNotNull('shipping_cost');
    })->count();

    if($total == $totalProductNotSetShippingCost) {
      return true;
    }

    return false;

  }

  public function getOrderShippingCostSummary() {

    $currency = new Currency;

    $orderProducts = $this->getRelatedData('OrderProduct');

    if($this->order_status_id == 1) {

      $orderShippingCost = 'ยังไม่ระบุจากผู้ขาย';
      if($this->order_free_shipping) {
        $orderShippingCost = 'จัดส่งฟรี';
      }elseif($this->order_shipping_cost != null) {
        $orderShippingCost = $currency->format($this->order_shipping_cost);
      }

      $productShippingCost = 0;
      $waitingConfirm = true;
      foreach ($orderProducts as $orderProduct) {

        if(empty($orderProduct->free_shipping) && empty($orderProduct->shipping_cost)) {
          continue;
        }

        $productShippingCost += $orderProduct->getOrderShippingCost();
        $waitingConfirm = false;
      }

      if($waitingConfirm) {
        $productShippingCost = 'ยังไม่ระบุจากผู้ขาย';
      }else{
        $productShippingCost = $currency->format($productShippingCost);
      }

    }else{

      if($this->order_free_shipping) {
        $orderShippingCost = 'จัดส่งฟรี';
      }else {
        $orderShippingCost = $currency->format($this->order_shipping_cost);
      }

      $productShippingCost = 0;
      foreach ($orderProducts as $orderProduct) {
        $productShippingCost += $orderProduct->getOrderShippingCost();
      }

      $productShippingCost = $currency->format($productShippingCost);

    }

    return array(
      'orderShippingCost' => $orderShippingCost,
      'productsShippingCost' => $productShippingCost
    );

  }

  public function getOrderShippingMethod() {

    $orderShipping = $this->getRelatedData('OrderShipping',array(
      'first' => true
    ));
    
    if(empty($orderShipping)) {
      return null;
    }

    return $orderShipping->buildModelData();

  }

  public function getNextOrderStatuses() {

    return OrderStatus::where([
      ['default_value','=','1'],
      ['sort','>',OrderStatus::select('sort')->find($this->order_status_id)->sort]
    ])->get();

  }

  public function orderStatusMessage() {

    $orderHistory = $this->getRelatedData('OrderHistory',array(
      'conditions' => array(
        'order_status_id' => $this->order_status_id
      ),
      'fields' => array('message'),
      'first' => true
    ));

    if(empty($orderHistory)) {
      return null;
    }

    return $orderHistory->message;

  }

  public function getShopName() {

    $shop = Shop::select('name')->find($this->shop_id);

    if(empty($shop)) {
      return null;
    }

    return $shop->name;

  }

  public function buildModelData() {

    $currency = new Currency;
    $date = new Date;

    return array(
      'id' => $this->id,
      'invoice_number' => $this->invoice_number,
      'person_name' => $this->person_name,
      'shipping_address' => $this->shipping_address,
      'shopName' => $this->shop->name,
      'order_status_id' => $this->order_status_id,
      'order_free_shipping' => $this->order_free_shipping,
      'order_shipping_cost' => $this->order_shipping_cost,
      'orderShippingCostText' => $currency->format($this->order_shipping_cost),
      'orderStatusName' => $this->orderStatus->name,
      'customer_message' => !empty($this->customer_message) ? $this->customer_message : '-',
      'orderedDate' => $date->covertDateToSting($this->created_at->format('Y-m-d')),
      'shipping_cost_detail' => $this->shipping_cost_detail,
      'pick_up_order' => $this->pick_up_order
    );
  }

  public function buildPaginationData() {

    $currency = new Currency;
    $date = new Date;

    // Get Total
    $total = $this->getRelatedData('OrderTotal',array(
      'conditions' => array(
        array('alias','like','total')
      ),
      'first' => true
    ));

    $_total = 0;
    if(!empty($total)) {
      $_total = $total->value;
    }

    return array(
      'id' => $this->id,
      'invoice_number' => $this->invoice_number,
      'shopName' => $this->shop->name,
      // 'countProuduct' => $this->countProductQuantity(),
      '_total' => $currency->format($_total),
      'OrderStatusName' => $this->orderStatus->name,
      'orderedDate' => $date->covertDateToSting($this->created_at->format('Y-m-d'))
    );
    
  }

}
