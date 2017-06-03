<?php

namespace App\Models;

class PaymentMethodType extends Model
{
  public $table = 'payment_method_types';
  protected $fillable = ['name'];
}
