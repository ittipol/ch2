<?php

namespace App\Models;

class OrderProduct extends Model
{
  protected $table = 'order_products';
  protected $fillable = ['order_id','product_id','product_name','price','quantity','shipping_calculate_from','free_shipping','shippng_cost','product_shipping_amount_type_id','tax','total'];
  public $timestamps  = false;
}
