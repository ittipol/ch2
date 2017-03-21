<?php

namespace App\Models;

use App\library\currency;
use App\library\cache;

class OrderProduct extends Model
{
  protected $table = 'order_products';
  protected $fillable = ['order_id','product_id','product_name','price','quantity','free_shipping','shipping_cost','tax','total'];
  public $timestamps  = false;

  public function buildModelData() {

    $currency = new Currency;

    $shippingCostText = '';
    if($this->free_shipping) {
      $shippingCostText = 'จัดส่งฟรี ('.$currency->format(0).')';
    }elseif($this->shipping_cost != null){
      $shippingCostText = $currency->format($this->getOrderShippingCost());
    }else{
      $shippingCostText = 'ยังไม่ระบุ';
    }

    return array(
      'id' => $this->id,
      'product_id' => $this->product_id,
      'product_name' => $this->product_name,
      '_price' => $currency->format($this->price),
      'quantity' => $this->quantity,
      'has_shipping_cost' => $this->has_shipping_cost,
      'free_shipping' => $this->free_shipping,
      'shipping_cost' => $this->shipping_cost,
      'shippingCostText' => $shippingCostText,
      '_total' => $currency->format($this->total)
    );

  }

  public function getSubTotal($format = false) {

    $currency = new Currency;

    $subTotal = $this->price * $this->quantity;

    if($format) {
      return $currency->format($subTotal);
    }

    return $subTotal;

  }

  public function getOrderShippingCost($format = false) {

    $currency = new Currency;

    if($format) {
      return $currency->format($this->shipping_cost);
    }

    return $this->shipping_cost;

  }

  // public function getOrderSavingPrice($format = false) {}

  public function getOrderTotal($format = false) {
    
    $currency = new Currency;

    $total = $this->getSubTotal() + $this->getOrderShippingCost();

    if($format) {
      return $currency->format($total);
    }

    return $total;

  }

}
