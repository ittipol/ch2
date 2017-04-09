<?php

namespace App\Models;

class AttachedFileAccessPermission extends Model
{
  protected $table = 'attached_file_access_permissions';
  protected $fillable = ['model','model_id','attached_file_id'];
  public $timestamps  = false;
  
}
