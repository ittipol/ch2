<?php

namespace App\Models;

class NotificationEvent extends Model
{
  protected $table = 'notification_events';
  protected $fillable = ['model','event','title_format','message_format','link'];
  public $timestamps  = false;
}
