<?php

namespace App\Models;

class RealEstateFeature extends Model
{
  protected $table = 'real_estate_features';
  protected $fillable = ['name','real_estate_feature_type_id',];
  public $timestamps  = false;
}
