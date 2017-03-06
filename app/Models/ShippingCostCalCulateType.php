<?php

namespace App\Models;

class ShippingCostCalCulateType extends Model
{
  protected $table = 'shipping_cost_calculate_types';
  protected $fillable = ['name'];
  public $timestamps  = false;
}
