<?php

namespace App\Models;

class NotificationEvent extends Model
{
  protected $table = 'notification_events';
  protected $fillable = ['model','event','receiver','title_format','message_format','url_format'];
  public $timestamps  = false;
}
