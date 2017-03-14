<?php

namespace App\Models;

use App\library\currency;
use App\library\cache;

class OrderProduct extends Model
{
  protected $table = 'order_products';
  protected $fillable = ['order_id','product_id','product_name','price','quantity','shipping_calculate_from','free_shipping','shipping_cost','product_shipping_amount_type_id','tax','total'];
  public $timestamps  = false;

  public function productShippingAmountType() {
    return $this->hasOne('App\Models\ProductShippingAmountType','id','product_shipping_amount_type_id');
  }

  public function buildModelData() {

    $currency = new Currency;

    $shippingCostText = '';
    switch ($this->shipping_calculate_from) {
      case 1:

        if(!empty($this->shipping_cost)) {
          $shippingCostText = $currency->format($this->shipping_cost);
        }else{
          $shippingCostText = 'รอการคำนวณจากผู้ขาย';
        }

        break;
      
      case 2:
        
          if($this->free_shipping) {
            $shippingCostText = 'จัดส่งฟรี ('.$currency->format(0).')';
          }else{
            $shippingCostText = $currency->format($this->calShippingCost());
          }

        break;
    }

    return array(
      'product_name' => $this->product_name,
      '_price' => $currency->format($this->price),
      'quantity' => $this->quantity,
      'shipping_calculate_from' => $this->shipping_calculate_from,
      // 'free_shipping' => $this->free_shipping,
      // 'freeShippingText' => !empty($this->free_shipping) ? 'จัดส่งฟรี' : '',
      // '_shipping_cost' => $currency->format($this->calShippingCost()),
      'shippingCostText' => $shippingCostText,
      '_total' => $currency->format($this->total)
    );

  }

  public function calShippingCost() {

    $cost = 0;
    switch ($this->product_shipping_amount_type_id) {
      case 1:
        $cost = $this->shipping_cost * $this->quantity;
        break;

      case 2:
        $cost = $this->shipping_cost;
        break;

    }

    return $cost;

  }

}
