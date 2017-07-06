<?php

namespace App\Models;

class RelateToBranch extends Model
{
  protected $table = 'relate_to_branches';
  protected $fillable = ['model','model_id','branch_id'];
  public $timestamps  = false;

  public function advertising() {
    return $this->hasOne('App\Models\Advertising','id','model_id');
  }

  public function job() {
    return $this->hasOne('App\Models\Job','id','model_id');
  }

  public function branch() {
    return $this->hasOne('App\Models\Branch','id','branch_id');
  }

  public function __saveRelatedData($model,$options = array()) {

    // if(!empty($options['value']['branch_id'])) {
  
    //   if($model->state == 'update') {
    //     $this->where(array(
    //       array('model','like',$model->modelName),
    //       array('model_id','=',$model->id),
    //     ))->delete();
    //   }

    //   if(empty($options['value']['branch_id'])) {
    //     return true;
    //   }
      
    //   foreach ($options['value']['branch_id'] as $branchId) {
        
    //     $this->newInstance()->fill(array(
    //       'model' => $model->modelName,
    //       'model_id' => $model->id,
    //       'branch_id' => $branchId
    //     ))->save();

    //   }

    // need always return true
    return true;

    // }

    $shopRelateToModel = new ShopRelateTo;

    if($model->exists) {
      $this->where(array(
        array('model','like',$model->modelName),
        array('model_id','=',$model->id),
      ))->delete();
    }

    if(empty($options['value']['branch_id'])) {
      return true;
    }

    $shopId = $shopRelateToModel
    ->select('shop_id')
    ->where([
      ['model','like',$model->modelName],
      ['model_id','=',$model->id]
    ])->first()->shop_id;
    
    foreach ($options['value']['branch_id'] as $branchId) {
      
      $exist = $shopRelateToModel
      ->select('model_id')
      ->where([
        ['shop_id','=',$shopId],
        ['model','=','Branch'],
        ['model_id','=',$branchId]
      ])->exists();

      if(!$exist) {
        continue;
      }

      $this->newInstance()->fill(array(
        'model' => $model->modelName,
        'model_id' => $model->id,
        'branch_id' => $branchId
      ))->save();

    }

    return true;
    
  }

}
