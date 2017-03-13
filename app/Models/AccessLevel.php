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

  public function getlevelByAlias($alias) {
    return $this->select('level')->where('alias','like',$alias)->first()->level;
  }

  public function getLevel() {

    $accessLevels = $this->getData(array(
      'conditions' => array(
        array('active','=','1')
      ),
      'fields' => array('level','name'),
      'order' => array(
        array('top','ASC'),
        array('level','ASC')
      )
    ));

    $levels = array();
    foreach ($accessLevels as $value) {
      $levels[$value['level']] = $value['name'];
    }

    return $levels;

  }
}
