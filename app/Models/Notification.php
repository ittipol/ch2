<?php

namespace App\Models;

class Notification extends Model
{
  protected $table = 'notifications';
  protected $fillable = ['title','message','sender','sender_id','receiver','receiver_id','unread','notify'];

  // public function create($event,$model) {

  //   $notificationEvent = new NotificationEvent;

  //   // Check event exist
  //   $event = $notificationEvent->where([
  //     ['model','like',$model->modelName],
  //     ['event','like',$event]
  //   ]);

  //   if(!$event->exists()) {
  //     return false;
  //   }

  //   $options = array(
  //     'format' => $event->message_format,
  //     'data' => $model->getAttributes()
  //   );

  //   //
  //   $this->parser()

  // }

  // private function parser($model,$options = array()) {

  //   // option -> format
  //   // option -> data (attributes)

  // }

  public function setUpdatedAt($value) {}

}
