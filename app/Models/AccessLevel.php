<?php

namespace App\Models;

class AccessLevel extends Model
{
  protected $table = 'access_levels';
  protected $fillable = ['level','name','active'];
  public $timestamps  = false;

  public function getIdByLevel($level) {
    return $this->select('id')->where('level','=',$level)->first()->id;
  }

  public function getLevel() {

    $accessLevels = $this->getData(array(
      'conditions' => array(
        array('active','=','1')
      ),
      'fields' => array('id','name'),
      'order' => array(
        array('top','ASC'),
        array('level','ASC')
      )
    ));

    $levels = array();
    foreach ($accessLevels as $value) {
      $levels[$value['id']] = $value['name'];
    }

    return $levels;

  }
}
