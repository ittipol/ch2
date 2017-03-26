<?php

namespace App\Models;

class OrderHistory extends Model
{
  protected $table = 'order_histories';
  protected $fillable = ['order_id','order_status_id','message'];

  public function setUpdatedAt($value) {}
}
