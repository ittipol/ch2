<?php

namespace App\Models;

class Role extends Model
{
  protected $table = 'roles';
  protected $fillable = ['name','alias'];

  public function getPermission() {
    $permissions = json_decode($this->permission,true);
    
    $_permissions = array();
    foreach ($permissions as $alias => $permission) {
      $_permissions[$alias] = $permission;
    }

    return $_permissions;
  }

  // public function getPermission() {
  //   return array(
  //     'add' => $this->adding_permission,
  //     'edit' => $this->editing_permission,
  //     'delete' => $this->deleting_permission,
  //   );
  // }
}
