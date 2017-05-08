<?php

namespace App\Models;

use App\library\currency;
use App\library\cache;

class OrderProduct extends Model
{
  protected $table = 'order_products';
  protected $fillable = ['order_id','product_id','product_name','product_option_value_id','product_option_name','product_option_value_name','full_price','price','quantity','free_shipping','shipping_cost','tax','total'];
  public $timestamps  = false;

  public function product() {
    return $this->hasOne('App\Models\Product','id','product_id');
  }

  public function buildModelData() {

    $currency = new Currency;

    $shippingCostText = '';
    if($this->free_shipping) {
      $shippingCostText = 'จัดส่งฟรี ('.$currency->format(0).')';
    }elseif($this->shipping_cost != null){
      $shippingCostText = $currency->format($this->getOrderShippingCost());
    }else{
      $shippingCostText = 'ยังไม่ระบุจากผู้ขาย';
    }

    $product = $this->product;

    $totalWeight = 'ยังไม่ระบุน้ำหนัก';
    $productUnit = '';
    if(!empty($product)) {

      if(!empty($product->weight) && !empty($product->weight_unit_id)) {
        $totalWeight = ($this->quantity * $product->weight).' '.$product->weightUnit->name;
      }

      $productUnit = $product->product_unit;

    }

    $productOption = null;
    if(!empty($this->product_option_value_id)) {
      $productOption = array(
        'productOptionName' => $this->product_option_name,
        'valueName' => $this->product_option_value_name,
      );
    }

    return array(
      'id' => $this->id,
      'product_id' => $this->product_id,
      'product_name' => $this->product_name,
      '_price' => $currency->format($this->price),
      'quantity' => $this->quantity,
      '_total' => $currency->format($this->total),
      'totalWeight' => $totalWeight,
      'product_unit' => $productUnit,
      'free_shipping' => $this->free_shipping,
      'shipping_cost' => $this->shipping_cost,
      'shippingCostText' => $shippingCostText,
      'productOption' => $productOption
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

  public function getOrderSavingPrice($format = false) {

    $currency = new Currency;

    $savingPrice = ($this->full_price - $this->price) * $this->quantity;

    if($format) {
      return $currency->format($savingPrice);
    }

    return $savingPrice;

  }

  public function getOrderTotal($format = false) {
    
    $currency = new Currency;

    $total = $this->getSubTotal() + $this->getOrderShippingCost();

    if($format) {
      return $currency->format($total);
    }

    return $total;

  }

}
