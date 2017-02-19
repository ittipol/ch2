<?php

namespace App\Models;

use App\library\string;

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
      'freelance_type_id.required' => 'ประเภทงานฟรีแลนซ์ห้ามว่าง',
    )
  );

  public function buildModelData() {

    $string = new String;

    return array(
      'id' => $this->id,
      'name' => $this->name,
      'description' => !empty($this->description) ? $this->description : '-',
      '_short_name' => $string->subString($this->name,60),
      '_freelanceType' => FreelanceType::select(array('name'))->find($this->freelance_type_id)->name
    );
    
  }

}
