<?php

namespace App\Models;

class PersonToShop extends Model
{
  public $table = 'person_to_shops';
  protected $fillable = ['shop_id','created_by','role_id'];

  public function role() {
    return $this->hasOne('App\Models\Role','id','role_id');
  }

  public function shop() {
    return $this->hasOne('App\Models\Shop','id','shop_id');
  }

  public function person() {
    return $this->hasOne('App\Models\Person','id','created_by');
  }

  public function saveSpecial($value) {

    $record = $this->getData(array(
      'conditions' => array(
        ['created_by','=',$value['created_by']],
        ['shop_id','=',$value['shop_id']],
        ['role_id','=',$value['role_id']]
      )
    ));

    if(empty($record)){
      return $this->fill($value)->save();
    }

    return true;

  }

  public function getByShopId($shopId) {
    $records = $this->where('shop_id','=',$shopId);

    if(!$records->exists()) {
      return null;
    }

    return $records->get();

  }

  public function setUpdatedAt($value) {}
}
