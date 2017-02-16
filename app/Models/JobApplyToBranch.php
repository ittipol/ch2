<?php

namespace App\Models;

class JobApplyToBranch extends Model
{
  protected $table = 'job_apply_to_branches';
  protected $fillable = ['person_apply_job_id','branch_id'];
  public $timestamps  = false;

  public function branch() {
    return $this->hasOne('App\Models\Branch','id','branch_id');
  }

  public function __saveRelatedData($model,$options = array()) {

    if(!empty($options['value']['branch_id'])) {

      if($model->state == 'update') {
        $this->where('person_apply_job_id','=',$model->id)->delete();
      }
      
      foreach ($options['value']['branch_id'] as $branchId) {
        
        $this->newInstance()->fill(array(
          'person_apply_job_id' => $model->id,
          'branch_id' => $branchId,
        ))->save();

      }

    }

    return true;

  }

}
