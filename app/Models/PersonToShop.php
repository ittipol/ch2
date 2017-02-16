<?php

namespace App\Models;

class PersonToShop extends Model
{
  public $table = 'person_to_shops';
  protected $fillable = ['shop_id','person_id','role_id'];

  public function role() {
    return $this->hasOne('App\Models\Role','id','role_id');
  }

  public function shop() {
    return $this->hasOne('App\Models\Shop','id','shop_id');
  }

  public function person() {
    return $this->hasOne('App\Models\Person','id','person_id');
  }

  public function saveSpecial($value) {

    $record = $this->getData(array(
      'conditions' => array(
        ['person_id','=',$value['person_id']],
        ['shop_id','=',$value['shop_id']],
        ['role_id','=',$value['role_id']]
      )
    ));

    if(empty($record)){
      return $this->fill($value)->save();
    }

    return true;

  }

  public function setUpdatedAt($value) {}
}
