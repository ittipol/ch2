<?php

namespace App\Models;

use App\library\currency;
use App\library\cache;
use App\library\url;
use App\library\date;

class Order extends Model
{
  protected $table = 'orders';
  protected $fillable = ['invoice_prefix','invoice_number','shop_id','person_id','person_name','shipping_address','message_to_seller','order_status_id'];

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

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

      $image = Product::select('id')->find($orderProduct->product_id)->getRelatedModelData('Image',array(
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

  public function orderOrderTotals() {
    $orderTotals = $this->getRelatedData('OrderTotal');

    $_orderTotals = array();
    foreach ($orderTotals as $orderTotal) {
      $_orderTotals[] = $orderTotal->buildModelData();
    }

    return $_orderTotals;
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

  public function buildModelData() {

    $date = new Date;

    return array(
      'id' => $this->id,
      'person_name' => $this->person_name,
      'shipping_address' => $this->shipping_address,
      'shopName' => $this->shop->name,
      'orderStatusName' => $this->orderStatus->name,
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
      'countProuduct' => $this->countProductQuantity(),
      '_total' => $currency->format($_total),
      'OrderStatusName' => $this->orderStatus->name,
      'orderedDate' => $date->covertDateToSting($this->created_at->format('Y-m-d'))
    );
    
  }

}
