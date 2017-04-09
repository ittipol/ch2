<?php

namespace App\Models;

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

}
