<?php

namespace App\Models;

use App\library\service;
use App\library\date;
use App\library\url;

class Notification extends Model
{
  protected $table = 'notifications';
  protected $fillable = ['model','model_id','notification_event_id','title','sender','sender_id','receiver','receiver_id','type','unread','notify'];

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  public function notificationEvent() {
    return $this->hasOne('App\Models\NotificationEvent','id','notification_event_id');
  }

  public function clearNotify() {

    $notifications = $this->select('id')
    ->where(function($query){
      $query->where([
        ['receiver','like','Person'],
        ['receiver_id','=',session()->get('Person.id')]
      ]);
    })
    ->where(function($query){
      $query->where('notify','=','1')->orWhere('notify','=','2');
    });

    if($notifications->exists()) {
      
      foreach ($notifications->get() as $notification) {
        $notification->notify = 0;
        $notification->save();
      }

    }

    return true;

  }

  public function countUnreadNotification() {
    return $this
    ->where(function($query){
      $query->where([
        ['receiver','like','Person'],
        ['receiver_id','=',session()->get('Person.id')]
      ]);
    })
    ->where('unread','=','1')
    ->where('notify','=','0')
    ->count();
  }

  public function getUnreadNotification() {
    $records = $this->select('model','model_id','notification_event_id','title','created_at')
    ->where(function($query){
      $query->where([
        ['receiver','like','Person'],
        ['receiver_id','=',session()->get('Person.id')]
      ]);
    })
    // ->where('unread','=','1')
    ->where('notify','=','0')
    ->orderBy('created_at','desc')
    ->take(16);

    if(!$records->exists()) {
      return null;
    }

    $notifications = array();
    foreach ($records->get() as $notification) {
      $notifications[] = $notification->buildModelData();
    }

    return $notifications;

  }

  public function buildModelData() {

    $date = new Date;

    return array(
      'title' => $this->title,
      'url' => $this->getUrl($this->model,$this->model_id,$this->notificationEvent),
      'createdDate' => $date->calPassedDate($this->created_at->format('Y-m-d H:i:s')),
      'image' => $this->getNorificationIcon($this->model)
    );
  }

  public function getUrl($modelName,$modelId,$notificationEvent) {

    $url = new Url;
    $model = Service::loadModel($modelName)->find($modelId);

    $_url = '';
    switch ($notificationEvent->event) {

      case 'order-create':

          // get slug
          $slug = Service::loadModel('Slug')
          ->where([
            ['model','like','Shop'],
            ['model_id','=',$model->shop_id]
          ])
          ->first()
          ->slug;
 
          $_url = $url->setAndParseUrl($notificationEvent->url_format,array('id'=>$model->id,'shopSlug'=>$slug));
        break;

      case 'order-confirm':
          $_url = $url->setAndParseUrl($notificationEvent->url_format,array('id'=>$model->id));
        break;

      case 'order-payment-inform':

          // get slug
          $slug = Service::loadModel('Slug')
          ->where([
            ['model','like','Shop'],
            ['model_id','=',$model->shop_id]
          ])
          ->first()
          ->slug;
      
          $_url = $url->setAndParseUrl($notificationEvent->url_format,array('id'=>$model->id,'shopSlug'=>$slug));
        break;

      case 'order-payment-confirm':
          $_url = $url->setAndParseUrl($notificationEvent->url_format,array('id'=>$model->id));
        break;
    
    }

    return $_url;

  }

  public function getNorificationIcon($modelName) {

    $image = '';

    switch ($modelName) {
      case 'Order':
          $image = '/images/icons/bag-white.png';
        break;
    
    }

    return $image;

  }

  public function setUpdatedAt($value) {}

}
