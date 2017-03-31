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
    ->where(function($query){
      $query->where('notify','=','1')->orWhere('notify','=','2');
    });

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
    ->where('unread','=','1')
    ->where('notify','=','0')
    ->orderBy('created_at','desc');

    if(!$records->exists()) {
      return null;
    }

    $notifications = array();
    foreach ($records->get() as $notification) {
      $notifications[] = $notification->buildModelData();
    }

    return $notifications;

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

  public function buildModelData() {
    return array(
      'title' => $this->title,
      'message' => $this->message,
      'url' => $this->getUrl($this->model,$this->model_id),
      'createdDate' => $this->calPassedDate(),
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

  public function calPassedDate() {

    $date = new Date;

    $secs = time() - strtotime($this->created_at->format('Y-m-d H:i:s'));
    $mins = (int)floor($secs / 60);
    $hours = (int)floor($mins / 60);
    $days = (int)floor($hours / 24);

    $passed = '';
    if($days == 0) {
      $passedSecs = $secs % 60;
      $passedMins = $mins % 60;
      $passedHours = $hours % 24;

      if($passedHours != 0) {
        $passed = $passedHours.' ชั่วโมงที่แล้ว';
      }elseif($passedMins != 0) {
        $passed = $passedMins.' นาทีที่แล้ว';
      }elseif($passedSecs != 0) {
        $passed = 'เมื่อไม่กี่วินาทีที่ผ่านมา';
      }

    }elseif($days == 1){
      $passed = 'เมื่อวานนี้ เวลา '.$date->covertTimeToSting($this->created_at->format('Y-m-d H:i:s'));
    }else{
      $passed = $date->covertDateToSting($this->created_at->format('Y-m-d H:i:s'));
    }

    return $passed;
  }

  public function setUpdatedAt($value) {}

}
