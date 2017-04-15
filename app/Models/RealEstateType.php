<?php

namespace App\Models;

class RealEstateType extends Model
{
  protected $table = 'real_estate_types';

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
