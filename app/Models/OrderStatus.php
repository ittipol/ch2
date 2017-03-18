<?php

namespace App\Models;

class OrderStatus extends Model
{
  protected $table = 'order_statuses';

  public function GetDefaultStatuses($build = false) {
    $orderstatuses = $this
    ->where('default_value','=','1')
    ->orderBy('sort','ASC')
    ->get();

    if(!$build) {
      return $orderstatuses;
    }

    $_orderstatuses = array();
    foreach ($orderstatuses as $orderstatus) {
      $_orderstatuses[] = $orderstatus->buildModelData();
    }

    return $_orderstatuses;

  }

  public function buildModelData() {
    return array(
      'id' => $this->id,
      'name' => $this->name,
      'sort' => $this->sort
    );

  }

}
