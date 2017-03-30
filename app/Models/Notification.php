<?php

namespace App\Models;

class Notification extends Model
{
  protected $table = 'notifications';
  protected $fillable = ['title','message','url','sender','sender_id','receiver','receiver_id','unread','notify'];

  public function notificationUnreadCount() {
    
  }

  public function setUpdatedAt($value) {}

}
