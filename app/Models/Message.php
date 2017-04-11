<?php

namespace App\Models;

use App\library\service;
use App\library\string;
use App\library\date;

class Message extends Model
{
  protected $table = 'messages';
  protected $fillable = ['model','model_id','parent_id','message','sender','sender_id','receiver','receiver_id','person_id'];
  protected $modelRelations = array('AttachedFile');

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  protected $validation = array(
    'rules' => array(
      'message' => 'required',
    ),
    'messages' => array(
      'message.required' => 'ข้อความห้ามว่าง',
    )
  );

  public function getSenderName() {
    return Service::loadModel($this->sender)->select('name')->find($this->sender_id)->name;
  }

  public function getReceiverName() {
    return Service::loadModel($this->receiver)->select('name')->find($this->receiver_id)->name;
  }

  public function getSenderInfo() {
    $sender = Service::loadModel($this->sender)->select('id','name','profile_image_id')->find($this->sender_id);
    return array(
      'name' => $sender->name,
      'profileImage' => $sender->getProfileImageUrl('xs')
    );
  }

  public function getMessage() {

    $string = new String;

    return $string->truncString($this->message,40,true);
  }

  public function hasPermission() {

    if($this->checkPermission($this->sender,$this->sender_id) || $this->checkPermission($this->receiver,$this->receiver_id)) {
      return true;
    }

    return false;

  }

  public function checkPermission($modelName,$modelId) {

    $hasPermission = false;
    switch ($modelName) {
      case 'Shop':
        
        $personToShopModel = new PersonToShop;
        $records = $personToShopModel->getByShopId($modelId);

        $personIds = array();
        foreach ($records as $record) {
          $personIds[] = $record->person_id; 
        }


        $hasPermission = in_array(session()->get('Person.id'), $personIds);

        break;
      
      case 'Person':
        
        if(session()->get('Person.id') == $permission->model_id) {
          $hasPermission = true;
        }

        break;

    }

    return $hasPermission;

  }

  public function isTopParent() {

    if($this->parent_id == null) {
      return true;
    }

    return false;

  }

  public function getReplyMessage($build = false) {

    $replyMessages = $this->where('parent_id','=',$this->id);

    if(!$replyMessages->exists()) {
      return null;
    }

    if(!$build) {
      return $replyMessages->get();
    }

    $_replyMessages = array();
    foreach ($replyMessages->get() as $message) {
      $_replyMessages[] = $message->buildModelData();
    }

    return $_replyMessages;

  }

  public function buildModelData() {

    $date = new Date;

    $files = $this->getRelatedData('AttachedFile',array(
      'fileds' => array('id','filename','filesize')
    ));
   
    $_files = array();
    if(!empty($files)) {

      foreach ($files as $file) {
        $_files[] = $file->buildModelData();
      }

    }

    return array(
      'message' => $this->message,
      'sender' => $this->getSenderInfo(),
      'attachedFiles' => $_files,
      'createdDate' => $date->calPassedDate($this->created_at->format('Y-m-d H:i:s')),
      'replyMessages' => $this->getReplyMessage(true)
    );

  }

}
