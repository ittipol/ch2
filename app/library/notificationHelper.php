<?php

namespace App\library;

use Route;

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

    $notificationModel = Service::loadModel('Notification');
    $notificationEventModel = Service::loadModel('NotificationEvent');

    // Check event exist
    $event = $notificationEventModel->where([
      ['model','like',$this->model->modelName],
      ['event','like',$event]
    ]);

    if(!$event->exists()) {
      return false;
    }

    $event = $event->first();

    $value = array(
      'model' => $this->model->modelName,
      'model_id' => $this->model->id,
      'notification_event_id' => $event->id,
      'unread' => 1,
      'notify' => 1
    );

    $options = array(
      'format' => array(
        'title' => $event->title_format,
      ),
      'data' => $this->model->getAttributes()
    );

    $result = $this->parser($options);

    if(!empty($result)){
      foreach ($result as $key => $_value){
        $value[$key] = $_value;
      }
    }

    $value = array_merge($value,$this->getSender());

    $receivers = $this->getReceiver($event->receiver);

    foreach ($receivers as $receiver) {

      $_model = $notificationModel->newInstance();

      $_value = array_merge($value,array(
        'receiver' => 'Person',
        'receiver_id' => $receiver,
      ));

      $_model->fill($_value)->save();
    }

    return true;

  }

  private function parser($options = array()) {

    if(empty($options['format']) || empty($options['data'])){
      return false;
    }

    $parseFormat = '/{{[\w\d|._,=>@]+}}/';
    $parseValue = '/[\w\d|._,=>@]+/';

    $result = array();
    foreach ($options['format'] as $key => $format){

      if(empty($format)) {
        $result[$key] = $format;
        continue;
      }

      preg_match_all($parseFormat, $format, $matches);

      if(empty($matches[0])){
        $result[$key] = $format;
        continue;
      }

      $result[$key] = $format;

      foreach ($matches[0] as $value) {

        preg_match($parseValue, $value, $_matches);

        if(empty($_matches[0])){
          break;
        }

        if(substr($_matches[0],0,2) == '__'){
     
          if(strpos($_matches[0], '|')) {
            
            list($_model,$fx) = explode('|', $_matches[0]);

            $_value = Service::loadModel(substr($_model,2))->{$fx}($model);

          }else{
            $_value = $model->{substr($_matches[0],2)}();
          }
        
        }elseif(array_key_exists($_matches[0],$options['data'])) {
          $_value = $options['data'][$_matches[0]];
        }else{
          $parts = explode('|', $_matches[0]);

          if(!empty($parts[1])){

            $records = $this->_parser($parts[0],$model,array(
              'lookupStringFormat' => $parts[1]
            ));

            list($class,$field) = explode('.', $parts[0]);

            $_value = array();

            if(!empty($records[$class])){
              foreach ($records[$class] as $record) {
                $_value[] = $record[$field];
              }
            }
          
            $_value = implode(' ', $_value);

          }
        }

        $result[$key] = $this->_replace($_value,$value,$result[$key]);

      }

      $result[$key] = trim($result[$key]);

    }

    return $result;

  }

  private function _parser($fields,$class,$options = array()) {

    $data = array();

    if(empty($class)){
      return false;
    }

    if(!empty($options['lookupStringFormat'])) {

      $lookup = new Lookup;

      $formats = explode(',', $options['lookupStringFormat']);

      $records = array();
      foreach ($formats as $format) {
        list($key1,$key2) = explode('=>', $format);
        $records = $this->__lookupFormatParser($class,$key1,$key2,$records);
      }

      $fields = explode('.', $fields);

      $data = array();
      foreach ($records as $key => $record) {
        $data[$fields[0]][$key][$fields[1]] = $record[$fields[1]];
      }

    }elseif(!empty($options['lookupArrayFormat'])) {

      $lookup = new Lookup;

      $formats = $options['lookupArrayFormat'];

      $records = array();
      foreach ($formats as $key1 => $key2) {
        $records = $this->__lookupFormatParser($class,$key1,$key2,$records);
      }

      $fields = explode('.', $fields);

      $data = array();
      foreach ($records as $key => $record) {
        $data[$fields[0]][$key][$fields[1]] = $record[$fields[1]];
      }
    }

    return $data;

  }

  private function __lookupFormatParser($class,$key1,$key2,$records = array()) {

    $temp = array();

    if(substr($key1, 0, 1) == '@') {
      $func = substr($key1, 1);
      $records = $class->{$func}($key2);
      
      foreach ($records as $key => $_record) {
        $temp[] = $_record;
      }

      return $temp;
    }

    list($class1,$field1) = explode('.', $key1);
    list($class2,$field2) = explode('.', $key2);

    $class1 = Service::loadModel($class1);
    $class2 = Service::loadModel($class2);

    if(($class->modelName == $class1->modelName) && empty($records)){
      $records = $class->getAttributes();
    }

    if(array_key_exists($field1,$records)) {

      $_records = $class2->where($field2,'=',$records[$field1])->get();

      foreach ($_records as $key => $_record) {
        $temp[] = $_record;
      }

      $records = $temp;

    }else{

      foreach ($records as $key => $record) {

        if(empty($record[$field1])) {
          continue;
        }

        $_records = $class2->where($field2,'=',$record[$field1])->get();

        foreach ($_records as $key => $_record) {
          $temp[] = $_record->getAttributes();
        }
        
      }

      $records = $temp;

    }

    return $records;

  }

  private function _replace($value,$key1,$key2) {
    $value = $this->_clean($value);
    return str_replace($key1, $value, $key2);
  }

  private function _clean($value) {
    $value = strip_tags($value);
    $value = trim(preg_replace('/\s\s+/', ' ', $value));
    return $value;
  }

  public function getSender() {

    // $param = Route::current()->parameters();

    $sender = 'Person';
    $senderId = session()->get('Person.id');

    return array(
      'sender' => $sender,
      'sender_id' => $senderId,
    );

  }

  public function getReceiver($receiver) {
    
    $receiverGroup = json_decode($receiver);

    $receivers = array();    
    foreach ($receiverGroup as $key => $value) {
      
      switch ($key) {
        case 'group':
            $receivers = $this->getReceiverByGroup($value);
          break;

        case 'person':
         
            if($this->model->modelName == 'Order') {
              $receivers[] = $this->model->person_id;
            }

          break;

      }

    }

    return $receivers;

  }

  private function getReceiverByGroup($group) {

    $receivers = array();
    switch ($group) {

      case 'all-person-in-shop':
          
          if($this->model->modelName == 'Order') {
            $people = Service::loadModel('PersonToShop')->where('shop_id','=',$this->model->shop_id)->get();
          }
          
          foreach ($people as $person) {
            $receivers[] = $person->person_id;
          }

        break;

    }

    return $receivers;

  }

}

?>