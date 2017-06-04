<?php

namespace App\Models;

class PaymentMethodType extends Model
{
  public $table = 'payment_method_types';
  protected $fillable = ['name'];

  public function getServiceProvider() {
    $paymentServiceProvider = new PaymentServiceProvider();
    return $paymentServiceProvider->getServiceProviderByPaymentMethodTypeId($this->id);
  }
}
