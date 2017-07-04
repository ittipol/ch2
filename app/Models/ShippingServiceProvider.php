<?php

namespace App\Models;

class ShippingServiceProvider extends Model
{
  protected $table = 'shipping_service_providers';

  public function getLogo() {

    $providerLogo = null;

    if(!empty($this->logo)) {
      $providerLogo = '/images/service_provider_logo/'.$this->logo;
    }

    return $providerLogo;

  }
}
