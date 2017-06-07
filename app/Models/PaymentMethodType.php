<?php

namespace App\Models;

class PaymentMethodType extends Model
{
  public $table = 'payment_method_types';
  protected $fillable = ['name','alias'];

  public function getServiceProvider() {
    $paymentServiceProvider = new PaymentServiceProvider();
    return $paymentServiceProvider->getServiceProviderByPaymentMethodTypeId($this->id);
  }

  public function hasPaymentServiceProvider() {
    $providerToType = new PaymentServiceProviderToPaymentMethodType;
    return $providerToType->where('payment_method_type_id','=',$this->id)->exists();
  }

  public function getPaymentMethodTypeImage() {

    if(!empty($this->image)) {
      return '/images/payment_type/'.$this->image;
    }

    return '/images/payment_type/bank-transfer.png';

  }
}
