<?php

namespace App\Models;

class AdvertisingType extends Model
{
  public $table = 'advertising_types';
  protected $fillable = ['name'];

  public function getTypeName($id) {

    if(empty($id)) {
      return null;
    }

    $type = $this->select('name')->where('id','=',$id);

    if(!$type->exists()) {
      return null;
    }

    return $type->first()->name;
  }
  
}
