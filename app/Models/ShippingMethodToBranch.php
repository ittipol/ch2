<?php

namespace App\Models;

class ShippingMethodToBranch extends Model
{
  protected $table = 'shipping_method_to_branches';
  protected $fillable = ['shipping_method_id','branch_id'];
  public $timestamps  = false;

  public function branch() {
    return $this->hasOne('App\Models\Branch','id','branch_id');
  }

  public function __saveRelatedData($model,$options = array()) {

    if(!empty($options['value']['branch_id'])) {

      if($model->state == 'update') {
        $this->where('shipping_method_id','=',$model->id)->delete();
      }

      foreach ($options['value']['branch_id'] as $branchId) {
        
        $this->newInstance()->fill(array(
          'shipping_method_id' => $model->id,
          'branch_id' => $branchId,
        ))->save();

      }

    }
    
  }

}
