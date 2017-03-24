<?php

namespace App\Models;

class OrderShipping extends Model
{
  protected $table = 'order_shippings';
  protected $fillable = ['order_id','shipping_method_id','shipping_method_name','shipping_service_id','shipping_service_cost_type_id','shipping_time'];
  public $timestamps  = false;

  public function buildModelData() {
    return array(
      'shipping_method_name' => $this->shipping_method_name,
      'shipping_service' => $this->shipping_service,
      'shipping_service_cost_type' => $this->shipping_service_cost_type,
      'shipping_time' => $this->shipping_time
    );
  }

}
