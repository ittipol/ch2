<?php

namespace App\Models;

use App\library\currency;
use App\library\cache;
use App\library\url;
use App\library\date;

class Order extends Model
{
  protected $table = 'orders';
  protected $fillable = ['invoice_prefix','invoice_number','shop_id','person_id','person_name','shipping_address','payment_detail','message_to_seller','order_status_id','order_shipping_cost'];
  protected $modelRelations = array('PaymentMethodToOrder');

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  private $Totaltypes = array(
    'subTotal' => array(
      'title' => 'มูลค่าสินค้า',
      'class' => 'sub-total'
    ),
    'shippingCost' => array(
      'title' => 'ค่าจัดส่งสินค้า',
      'class' => 'shipping-cost'
    ),
    'savingPrice' => array(
      'title' => 'ประหยัด',
      'class' => 'saving-price'
    ),
    'total' => array(
      'title' => 'ยอดสุทธิ',
      'class' => 'total-amount'
    )
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

      $image = Product::select('id')->find($orderProduct->product_id)->getRelatedData('Image',array(
        'fields' => array('id','model','model_id','filename','image_type_id'),
        'first' => true
      ));

      $imageUrl = '/images/common/no-img.png';
      if(!empty($image)) {
        $imageUrl = $cache->getCacheImageUrl($image,'sm');
      }

      $_orderProducts[] = array_merge($orderProduct->buildModelData(),array(
        'imageUrl' => $imageUrl,
        'productDetailUrl' => $url->setAndParseUrl('product/detail/{id}',array('id' => $orderProduct->id))
      ));

    }

    return $_orderProducts;
  }

  public function orderTotals() {
    $orderTotals = $this->getRelatedData('OrderTotal');

    $_orderTotals = array();
    foreach ($orderTotals as $orderTotal) {
      $_orderTotals[] = $orderTotal->buildModelData();
    }

    return $_orderTotals;
  }

  public function getOrderStatuses() {

    $orderStatusModel = new OrderStatus;
    $orderStatuses = $orderStatusModel->GetDefaultStatuses(true);

    $passed = true;
    $current = false;
    $_orderStatuses = array();
    foreach ($orderStatuses as $orderStatus) {

      $current = false;

      if($this->order_status_id == $orderStatus['id']) {
        $current = true;
        $passed = false;
      }

      $_orderStatuses[] = array(
        'id' => $orderStatus['id'],
        'name' => $orderStatus['name'],
        'current' => $current,
        'passed' => $passed
      );
    }

    return $_orderStatuses;

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

  public function getTitle($alias) {

    if(empty($this->Totaltypes[$alias]['title'])) {
      return null;
    }

    return $this->Totaltypes[$alias]['title'];
  }

  public function getClass($alias) {

    if(empty($this->Totaltypes[$alias]['class'])) {
      return null;
    }

    return $this->Totaltypes[$alias]['class'];
  }

  public function getSummary($format = false) {

    $summaries = array(
      'subTotal' => 'getOrderSubTotal',
      'shippingCost' => 'getOrderShippingCost',
      // 'savingPrice' => 'getOrderSavingPrice',
      'total' => 'getOrderTotal'
    );

    $orderProducts = $this->getRelatedData('OrderProduct');

    $_summaries = array();
    foreach ($summaries as $alias => $fx) {
      $_summaries[$alias] = array(
        'value' => $this->{$fx}($orderProducts,$format),
        'title' => $this->getTitle($alias),
        'class' => $this->getClass($alias),
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

  public function getOrderTotal($orderProducts,$format) {

    $currency = new Currency;

    $total = 0;
    foreach ($orderProducts as $orderProduct) {
      $total += $orderProduct->getOrderTotal();
    }

    if($format) {
      return $currency->format($total);
    }

    return $total;

  }

  public function checkHasProductNotSetShippingCost() {

    return OrderProduct::where([
      ['order_id','=',$this->id],
      ['shipping_calculate_from','=',1],
      ['shipping_cost','=',null]
    ])->exists();

  }

  public function checkHasAllProductNotSetShippingCost() {

    $total = OrderProduct::where([
      ['order_id','=',$this->id]
    ])->count();

    $totalProductNotSetShippingCost = OrderProduct::where([
      ['order_id','=',$this->id],
      ['shipping_calculate_from','=',1],
      ['shipping_cost','=',null]
    ])->count();

    if($total == $totalProductNotSetShippingCost) {
      return true;
    }

    return false;

  }

  public function buildModelData() {

    $date = new Date;

    return array(
      'id' => $this->id,
      'invoice_number' => $this->invoice_number,
      'person_name' => $this->person_name,
      'shipping_address' => $this->shipping_address,
      'shopName' => $this->shop->name,
      'order_status_id' => $this->order_status_id,
      'orderStatusName' => $this->orderStatus->name,
      'message_to_seller' => $this->message_to_seller,
      'orderedDate' => $date->covertDateToSting($this->created_at->format('Y-m-d'))
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