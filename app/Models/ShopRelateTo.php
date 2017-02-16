<?php

namespace App\Models;

class ShopRelateTo extends Model
{
  public $table = 'shop_relate_to';
  protected $fillable = ['shop_id','model','model_id'];
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
