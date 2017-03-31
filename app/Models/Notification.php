<?php

namespace App\Models;

use App\library\date;
use App\library\url;

class Notification extends Model
{
  protected $table = 'notifications';
  protected $fillable = ['model','model_id','title','message','url','sender','sender_id','receiver','receiver_id','type','unread','notify'];

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
    $records = $this->select('model','model_id','title','message','url','created_at')
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
      'url' => $this->getUrl($this->model,$this->model_id),
      'createdDate' => $date->covertDateToSting($this->created_at->format('Y-m-d')),
      'image' => $this->getImage($this->model)
    );
  }

  public function getImage($modelName) {

    $image = '';

    switch ($modelName) {
      case 'Order':
          $image = '/images/icons/bag-white.png';
        break;
    
    }

    return $image;

  }

  public function getUrl($modelName,$modelId) {

    $url = new Url;

    $_url = '';
    switch ($modelName) {
      case 'Order':
          $_url = $url->setAndParseUrl('account/order/{id}',array('id'=>$modelId));
        break;
    
    }

    return $_url;

  }

  public function setUpdatedAt($value) {}

}
