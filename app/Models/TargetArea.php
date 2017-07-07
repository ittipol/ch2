<?php

namespace App\Models;

class TargetArea extends Model
{
  protected $table = 'target_areas';
  protected $fillable = ['model','model_id','province_id','district_area','sub_district_area'];
  public $timestamps  = false;

  // district_area,sub_district_area -> store as json

  public function __saveRelatedData($model,$options = array()) {

    if(!empty($options['value']['province_id'])) {
  
      if($model->state == 'update') {
        $this->where(array(
          array('model','like',$model->modelName),
          array('model_id','=',$model->id),
        ))->delete();
      }
      
      foreach ($options['value']['province_id'] as $provinceId) {

        $this->newInstance()->fill(array(
          'model' => $model->modelName,
          'model_id' => $model->id,
          'province_id' => $provinceId
        ))->save();

      }

    }

    return true;
  }

}
