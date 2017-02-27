<?php

namespace App\Models;

class Category extends Model
{
  protected $table = 'categories';
  protected $fillable = ['parent_id','name'];
  public $timestamps  = false;
}
