<?php

namespace App\Models;

class ShopRelateTo extends Model
{
  public $table = 'shop_relate_to';
  protected $fillable = ['shop_id','model','model_id'];
  protected $modelRelations = array('Product','Branch','ShippingMethod','PaymentMethod','Job','Advertising');
  public $timestamps  = false;

  public function product() {
    return $this->hasOne('App\Models\Product','id','model_id');
  }

  public function branch() {
    return $this->hasOne('App\Models\Branch','id','model_id');
  }

  public function advertising() {
    return $this->hasOne('App\Models\Advertising','id','model_id');
  }

  public function paymentMethod() {
    return $this->hasOne('App\Models\PaymentMethod','id','model_id');
  }

  public function shop() {
    return $this->hasOne('App\Models\Shop','id','shop_id');
  }

  public function __saveRelatedData($model,$options = array()) {
    return $this->fill(array(
      'shop_id' => $options['value']['shop_id'],
      'model' => $model->modelName,
      'model_id' => $model->id
    ))->save();
  }
  
}
