<?php

namespace App\library;

use Session;
use Schema;

class MessageHelper
{
  private $model;

  public function setModel($model) {
    $this->model = $model;
  }

  public static function display($title = '',$type = 'info') {
    Session::flash('message.title', $title);
    Session::flash('message.type', $type); 
  }

  public static function displayWithDesc($title = '',$desc = '',$type = 'info') {
    Session::flash('message.title', $title);
    Session::flash('message.desc', $desc);
    Session::flash('message.type', $type); 
  }

  public function loginSuccess() {
    Session::flash('message.title', 'เข้าสู่ระบบแล้ว');
    Session::flash('message.desc', '');
    Session::flash('message.type', 'info');
  }

  public function registerSuccess() {
    Session::flash('message.title', 'สมัครสมาชิกแล้ว');
    // Session::flash('message.desc', '');
    Session::flash('message.type', 'info');
  }

  public function error($text = '') {
    Session::flash('message.title', 'เกิดข้อผิดพลาด '.$text);
    Session::flash('message.type', 'error'); 
  }

  public function getSender($sendAs = 'person') {

    switch ($sendAs) {
      case 'person':

        $sender = 'Person';
        $senderId = session()->get('Person.id');

        break;

      case 'shop':
        
        $sender = 'Shop';
        $senderId = null;

        if(Schema::hasColumn($this->model->getTable(), 'shop_id')) {
          $senderId = $this->model->shop_id;
        }

        break;
    }

    return array(
      'sender' => $sender,
      'sender_id' => $senderId,
    );

  }

  public function getReceiver($receiveAs = 'person') {

    switch ($receiveAs) {
      case 'person':

        $receiver = 'Person';

        if(Schema::hasColumn($this->model->getTable(), 'person_id')) {
          $receiverId = $this->model->person_id;
        }

        break;

      case 'shop':
        
        $receiver = 'Shop';
        $receiverId = null;

        if(Schema::hasColumn($this->model->getTable(), 'shop_id')) {
          $receiverId = $this->model->shop_id;
        }

        break;
    }

    return array(
      'receiver' => $receiver,
      'receiver_id' => $receiverId,
    );

  }

}
