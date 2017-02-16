<?php

namespace App\Models;

class Language extends Model
{
  protected $table = 'languages';
  protected $fillable = ['name','active'];
  public $timestamps  = false;
}
