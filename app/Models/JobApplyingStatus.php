<?php

namespace App\Models;

class JobApplyingStatus extends Model
{
  protected $table = 'job_applying_statuses';
  protected $fillable = ['name','alias','default_value'];
  public $timestamps  = false;
}
