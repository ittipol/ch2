<?php

namespace App\Models;

class TimelinePostType extends Model
{
  protected $table = 'timeline_post_types';
  protected $fillable = ['name','alias'];
}
