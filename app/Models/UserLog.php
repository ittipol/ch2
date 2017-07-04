<?php

namespace App\Models;

class UserLog extends Model
{
  protected $table = 'user_logs';
  protected $fillable = ['model','model_id','action','ip_address','user_id'];

  public function setUpdatedAt($value) {}
}
