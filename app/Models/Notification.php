<?php

namespace App\Models;

use App\library\date;

class Notification extends Model
{
  protected $table = 'notifications';
  protected $fillable = ['title','message','url','sender','sender_id','receiver','receiver_id','unread','notify'];

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  public function clearNotify() {

    $records = $this->select('id')
    ->where(function($query){
      $query->where([
        ['receiver','like','Person'],
        ['receiver_id','=',session()->get('Person.id')]
      ]);
    })
    ->where('notify','=','1');

    if($records->exists()) {
      
      foreach ($records->get() as $notification) {
        $notification->notify = 0;
        $notification->save();
      }

    }

    return true;

  }

  public function getUnreadNotification() {
    $records = $this->select('title','message','url','created_at')
    ->where(function($query){
      $query->where([
        ['receiver','like','Person'],
        ['receiver_id','=',session()->get('Person.id')]
      ]);
    })
    ->where('unread','=','1');

    if(!$records->exists()) {
      return null;
    }

    $notifications = array();
    foreach ($records->get() as $notification) {
      $notifications[] = $notification->buildModelData();
    }

    return $notifications;

  }

  public function notificationUnreadCount() {
    return $this->where([
      ['notify','=','0'],
      ['unread','=','1']
    ])->count();
  }

  public function buildModelData() {

    $date = new Date;

    return array(
      'title' => $this->title,
      'message' => $this->message,
      'url' => $this->url,
      'createdDate' => $date->covertDateToSting($this->created_at->format('Y-m-d'))
    );
  }

  public function setUpdatedAt($value) {}

}
