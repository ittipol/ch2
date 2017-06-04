<?php

namespace App\Models;

class PaymentServiceProviderType extends Model
{
  public $table = 'payment_service_provider_types';
  protected $fillable = ['name'];
  public $timestamps  = false;
}
