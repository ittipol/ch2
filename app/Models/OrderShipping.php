<?php

namespace App\Models;

class OrderShipping extends Model
{
  protected $table = 'order_shippings';
  protected $fillable = ['order_id','shipping_method_id','shipping_method_name','shipping_service_id','shipping_service_cost_type_id','shipping_time'];
  public $timestamps  = false;

  public function shippingService() {
    return $this->hasOne('App\Models\ShippingServiceProvider','id','shipping_service_id');
  }

  public function shippingServiceCostType() {
    return $this->hasOne('App\Models\ShippingServiceCostType','id','shipping_service_cost_type_id');
  }

  public function buildModelData() {

    $shippingService = '-';
    $shippingServiceCostType = '-';
    
    if(!empty($this->shippingService)) {
      $shippingService = $this->shippingService->name;
    }

    if(!empty($this->shippingServiceCostType)) {
      $shippingServiceCostType = $this->shippingServiceCostType->name;
    }

    return array(
      'shipping_method_name' => $this->shipping_method_name,
      'shippingService' => $shippingService,
      'shippingServiceCostType' => $shippingServiceCostType,
      'shipping_time' => !empty($this->shipping_time) ? $this->shipping_time : '-'
    );
  }

}
