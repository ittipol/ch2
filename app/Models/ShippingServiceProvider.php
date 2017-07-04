<?php

namespace App\Models;

class ShippingServiceProvider extends Model
{
  protected $table = 'shipping_service_providers';

  public function getLogo() {

    if(!empty($this->logo)) {
      return '/images/service_provider_logo/'.$this->logo;
    }

    // return '/images/common/truck.png';
    return null;

  }
}
