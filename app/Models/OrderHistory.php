<?php

namespace App\Models;

use App\library\date;

class OrderHistory extends Model
{
  protected $table = 'order_histories';
  protected $fillable = ['order_id','order_status_id','message'];

  public function orderStatus() {
    return $this->hasOne('App\Models\OrderStatus','id','order_status_id');
  }

  public function buildModelData() {

    $date = new Date;

    return array(
      'orderStatus' => $this->orderStatus->name,
      'message' => nl2br($this->message),
      'createdDate' => $date->covertDateTimeToSting($this->created_at->format('Y-m-d H:i:s'))
    );
  }

}
