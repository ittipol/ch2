<?php

namespace App\Models;

class OrderShipping extends Model
{
  protected $table = 'order_shippings';
  protected $fillable = ['order_id','shipping_method_id','shipping_method_name','shipping_service','shipping_service_cost_type','free_service','service_cost','shipping_time'];
  public $timestamps  = false;
}
