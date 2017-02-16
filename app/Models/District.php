<?php

namespace App\Models;

class District extends Model
{
  protected $table = 'districts';
  protected $fillable = ['province_id','name','name_en','zip_code'];
  public $timestamps  = false;
}
