<?php

namespace App\Models;

class WeightUnit extends Model
{
  protected $table = 'weight_units';
  protected $fillable = ['name', 'unit'];
  public $timestamps  = false;
}
