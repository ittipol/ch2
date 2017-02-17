<?php

namespace App\Models;

class Freelance extends Model
{
  public $table = 'freelances';
  protected $fillable = ['freelance_type_id','name','description','person_id'];
  protected $modelRelations = array('Image','Tagging');
  protected $directory = true;

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  public $imageTypes = array(
    'photo' => array(
      'limit' => 10
    )
  );

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
      'freelance_type_id' => 'required',
    ),
    'messages' => array(
      'name.required' => 'ชื่องานฟรีแลนซ์ที่รับทำห้ามว่าง',
      'freelance_type_id.required' => 'ประเภทฟรีแลนซ์ห้ามว่าง',
    )
  );
}
