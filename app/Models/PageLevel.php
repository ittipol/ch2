<?php

namespace App\Models;

class PageLevel extends Model
{
  protected $table = 'page_levels';
  protected $fillable = ['level','name','active'];
  public $timestamps  = false;

  public function getIdByLevel($level) {
    return $this->select('id')->where('level','=',$level)->first()->id;
  }

  public function getLevel() {

    $pageLevels = $this->getData(array(
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
    foreach ($pageLevels as $value) {
      $levels[$value['id']] = $value['name'];
    }

    return $levels;

  }
}
