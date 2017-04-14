<?php

namespace App\Models;

class JobApplyingHistory extends Model
{
  protected $table = 'job_applying_histories';
  protected $fillable = ['job_id','job_applying_status_id','message','times'];

  public function setUpdatedAt($value) {}

  public function buildModelData() {

    return array(
      'message' => nl2br($this->message),
    );

  }
}
