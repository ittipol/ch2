<?php

namespace App\library;

class NotificationHelper {

  private $model;

  // public function __construct($model = null) {
  //   $this->model = $model;
  // }

  public function setModel($model) {
    $this->model = $model;
  }

  public function create($event) {

    if(empty($this->model)) {
      return false;
    }
dd('ddd');
    $notificationEvent = Service::loadModel('NotificationEvent');

    // Check event exist
    $event = $notificationEvent->where([
      ['model','like',$this->model->modelName],
      ['event','like',$event]
    ]);
dd($event);
    if(!$event->exists()) {
      return false;
    }

  }

  private function parser($options = array()) {

    // option -> format
    // option -> data (attributes)

  }

}

?>