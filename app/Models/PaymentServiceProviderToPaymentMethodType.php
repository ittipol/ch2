<?php

namespace App\Models;

class PaymentServiceProviderToPaymentMethodType extends Model
{
  public $table = 'payment_service_provider_to_payment_method_types';
  protected $fillable = ['payment_service_provider_id','payment_method_type_id'];
  public $timestamps  = false;
}
