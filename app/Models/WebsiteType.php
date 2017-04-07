<?php

namespace App\Models;

class WebsiteType extends Model
{
  protected $table = 'website_types';
  protected $fillable = ['name','alias'];
}
