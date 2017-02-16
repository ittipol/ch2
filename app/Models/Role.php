<?php

namespace App\Models;

use Schema;

class Role extends Model
{
  protected $table = 'roles';
  protected $fillable = ['name','alias'];

  public function getPermission() {
    return array(
      'add' => $this->adding_permission,
      'edit' => $this->editing_permission,
      'delete' => $this->deleting_permission,
    );
  }
}
