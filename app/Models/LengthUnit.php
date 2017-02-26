<?php

namespace App\Models;

class LengthUnit extends Model
{
  protected $table = 'length_units';
  protected $fillable = ['name', 'unit'];
  public $timestamps  = false;
}
