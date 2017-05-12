<?php

namespace App\Models;

class CareerType extends Model
{
  public $table = 'career_types';
  protected $fillable = ['name'];
  public $timestamps  = false;

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
