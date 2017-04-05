<?php

namespace App\Models;

class SpecialShippingMethod extends Model
{
  protected $table = 'special_shipping_methods';
  protected $fillable = ['name','alias','shipping_service_id','shipping_service_cost_type_id','sort'];
  public $timestamps  = false;
}
