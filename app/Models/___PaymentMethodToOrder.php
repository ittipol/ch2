<?php

namespace App\Models;

class PaymentMethodToOrder extends Model
{
  public $table = 'payment_method_to_orders';
  protected $fillable = ['payment_method_id','order_id'];
  public $timestamps  = false;

  public function order() {
    return $this->hasOne('App\Models\Order','id','order_id');
  }

  public function paymentMethod() {
    return $this->hasOne('App\Models\PaymentMethod','id','payment_method_id');
  }

}
