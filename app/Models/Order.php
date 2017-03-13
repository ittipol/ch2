<?php

namespace App\Models;

use App\library\currency;
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

  public function countProduct() {
    // OrderProduct
  }

  public function buildPaginationData() {

    $currency = new Currency;
    $date = new Date;

    // Get Total
    $total = $this->getRalatedData('OrderTotal',array(
      'conditions' => array(
        array('alias','like','total')
      ),
      'first' => true
    ));

    return array(
      'id' => $this->id,
      '_total' => $currency->format($total->value),
      'OrderStatusName' => $this->orderStatus->name,
      'orderedDate' => $date->covertDateToSting($this->created_at->format('Y-m-d'))
    );
    
  }

}
