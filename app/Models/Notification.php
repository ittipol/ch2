<?php

namespace App\Models;

use App\library\service;
use App\library\date;
use App\library\url;

class Notification extends Model
{
  protected $table = 'notifications';
  protected $fillable = ['model','model_id','notification_event_id','title','sender','sender_id','receiver','receiver_id','type','unread','notify','person_id'];

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
    ->take(12);

    if(!$records->exists()) {
      return null;
    }

    $notifications = array();
    foreach ($records->get() as $notification) {
      $notifications[] = $notification->buildModelData();
    }

    return $notifications;

  }

  public function getUrl($modelName,$modelId,$notificationEvent) {

    $url = new Url;

    $model = Service::loadModel($modelName)->find($modelId);

    return $url->setAndParseUrl($notificationEvent->url_format,$this->parseUrl($notificationEvent->url_format,$model));

  }

  private function parseUrl($urlFormat,$model) {

    if(empty($model)) {
      return null;
    }

    preg_match_all('/{[\w0-9]+}/', $urlFormat, $matches);

    $value = array();
    foreach ($matches[0] as $pattern) {

      switch ($pattern) {
        case '{id}':
            $value['id'] = $model->id;
          break;
        
        case '{shopSlug}':

            if(!empty($model->shop_id)) {
              $slug = Slug::select('slug')
              ->where([
                ['model','like','Shop'],
                ['model_id','=',$model->shop_id]
              ])
              ->first()
              ->slug;
            }else {

              switch ($model->modelName) {
                case 'Message':
                  
                  if($model->receiver == 'Shop') {

                    $slug = Slug::select('slug')
                    ->where([
                      ['model','like','Shop'],
                      ['model_id','=',$model->receiver_id]
                    ])
                    ->first()
                    ->slug;

                  }

                  break;
                
              }

            }

            $value['shopSlug'] = $slug;

          break;

        case '{model_id}':
            $value['model_id'] = $model->model_id;
          break;

      }

    }

    return $value;

  }

  public function getNorificationIcon($modelName) {

    $image = '';

    switch ($modelName) {
      case 'Order':
          $image = '/images/icons/bag-white.png';
        break;

      case 'PersonApplyJob':
          $image = '/images/icons/resume-white.png';
        break;

      case 'Message':
          $image = '/images/icons/message-white.png';
        break;

      default:
         $image = '/images/icons/bell-white.png'; 
    
    }

    return $image;

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

  public function buildPaginationData() {

    $date = new Date;

    return array(
      'title' => $this->title,
      'url' => $this->getUrl($this->model,$this->model_id,$this->notificationEvent),
      'createdDate' => $date->calPassedDate($this->created_at->format('Y-m-d H:i:s')),
      'image' => $this->getNorificationIcon($this->model)
    );
    
  }

  public function setUpdatedAt($value) {}

}
