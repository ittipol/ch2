<?php

namespace App\Models;

class JobToArea extends Model
{
  protected $table = 'job_to_areas';
  protected $fillable = ['job_id','province_id','area'];
  public $timestamps  = false;

  public function __saveRelatedData($model,$options = array()) {
dd($options['value']);
    if(!empty($options['value']['province_id'])) {

      if($model->state == 'update') {
        $this->where('job_id','=',$model->id)->delete();
      }

      if(empty($options['value']['province_id'])) {
        return true;
      }

      //   foreach ($options['value']['province_id'] as $provinceId) {
          
      //     $this->newInstance()->fill(array(
      //       'job_id' => $model->id,
      //       'province_id' => $provinceId
      //     ))->save();

      //   }

    }
    
  }
}
