<?php

namespace App\Models;

class BusinessEntity extends Model
{
  public $table = 'business_entities';
  protected $fillable = ['name','description'];
}
