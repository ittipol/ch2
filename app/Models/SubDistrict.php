<?php

namespace App\Models;

class SubDistrict extends Model
{
  public $table = 'sub_districts';
  protected $fillable = ['district_id','name','zip_code'];
  
  public function __construct() {  
    parent::__construct();
  }
}
