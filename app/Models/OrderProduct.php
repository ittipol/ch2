<?php

namespace App\Models;

class OrderProduct extends Model
{
  protected $table = 'order_products';
  protected $fillable = ['order_id','product_id','product_name','price','quantity','shippng_cost','tax'];
  public $timestamps  = false;
}
