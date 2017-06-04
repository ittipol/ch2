<?php

namespace App\Models;

class PaymentServiceProvider extends Model
{
  public $table = 'payment_service_providers';
  protected $fillable = ['payment_service_provider_type_id','name','logo'];
  public $timestamps  = false;

  public function getServiceProviderByPaymentMethodTypeId($id = null) {

    if(empty($id)) {
      return null;
    }

    $serviceProvider = $this
    ->join('payment_service_provider_to_payment_method_types', 'payment_service_provider_to_payment_method_types.payment_service_provider_id', '=', 'payment_service_providers.id')
    ->select('payment_service_providers.*')
    ->where('payment_service_provider_to_payment_method_types.payment_method_type_id','=',$id);

    if($serviceProvider->exists()) {
      return $serviceProvider->get();
    }

    return null;

  }

}
