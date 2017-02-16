<?php

namespace App\library;

class Shop
{
  private $slugName;
  private $model;

  public function __construct($slug) {
    $this->slugName = $slug->name;
    $this->model = service::loadModel($slug->model)->find($slug->model_id);
  }

  public function buildData() {

    return array(
      'model' => $this->model,
      'slugName' => $this->slugName,
      'permission' => $this->getPermission(),
      'url' => Service::url('shop/'.$this->slugName),
    );

  }

  public function getPermission() {

    if(empty(session()->get('Person.id'))) {
      return false;
    }

    $person = Service::loadModel('PersonToShop')->getData(array(
      'conditions' => array(
        ['person_id','=',session()->get('Person.id')],
        ['shop_id','=',$this->id],
      ),
      'fields' => array('role_id')
    ));

    $permission = array();
    if(!empty($person)) {
      $role = $person->role;
      $permission = array(
        'add' => $role->adding_permission,
        'edit' => $role->editing_permission,
        'delete' => $role->deleting_permission,
      );
    }

    return $permission;

  }

}

?>