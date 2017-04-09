<?php

namespace App\Models;

use App\library\service;
use App\library\string;

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

  public function getMessage() {

    $string = new String;

    return $string->truncString($this->message,40,true);
  }

  public function buildModelData() {

    // Get Attached files
    $files = $this->getRelatedData('AttachedFile',array(
      'fileds' => array('id','filename','filesize')
    ));
    
    $_files = array();
    foreach ($files as $file) {
      $_files[] = array(
        'filename' => $file->filename,
        'filesize' => $file->getFilesize(),
        'url' => $file->buildUrl()
      );
    }

    return array(
      'message' => $this->message,
      'sender' => $this->getSenderName(),
      'attachedFile' => $_files
    );

  }

}
