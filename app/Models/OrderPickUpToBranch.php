<?php

namespace App\Models;

class OrderPickUpToBranch extends Model
{
  protected $table = 'order_pick_up_to_branches';
  protected $fillable = ['order_id','branch_id'];
  public $timestamps  = false;

  public function order() {
    return $this->hasOne('App\Models\Order','id','order_id');
  }

  public function branch() {
    return $this->hasOne('App\Models\Branch','id','branch_id');
  }
}
