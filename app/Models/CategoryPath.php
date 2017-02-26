<?php

namespace App\Models;

class CategoryPath extends Model
{
  protected $table = 'category_paths';
  protected $fillable = ['category_id','path_id','level'];
  public $timestamps  = false;
}
