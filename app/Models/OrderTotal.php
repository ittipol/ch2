<?php

namespace App\Models;

use App\library\currency;

class OrderTotal extends Model
{
  protected $table = 'order_totals';
  protected $fillable = ['order_id','alias','value'];
  public $timestamps  = false;

  // private $Totaltypes = array(
  //   'subTotal' => array(
  //     'title' => 'มูลค่าสินค้า',
  //     'class' => 'sub-total'
  //   ),
  //   'shippingCost' => array(
  //     'title' => 'ค่าจัดส่งสินค้า',
  //     'class' => 'shipping-cost'
  //   ),
  //   'savingPrice' => array(
  //     'title' => 'ประหยัด',
  //     'class' => 'saving-price'
  //   ),
  //   'total' => array(
  //     'title' => 'ยอดสุทธิ',
  //     'class' => 'total-amount'
  //   )
  // );

  // public function getTitle($alias) {

  //   if(empty($this->Totaltypes[$alias]['title'])) {
  //     return null;
  //   }

  //   return $this->Totaltypes[$alias]['title'];
  // }

  // public function getClass($alias) {

  //   if(empty($this->Totaltypes[$alias]['class'])) {
  //     return null;
  //   }

  //   return $this->Totaltypes[$alias]['class'];
  // }

  public function buildModelData() {

    $cart = new Cart;
    $currency = new Currency;

    return array(
      'class' => $cart->getClass($this->alias),
      'title' => $cart->getTitle($this->alias),
      'value' => $currency->format($this->value),
    );

  }

}
