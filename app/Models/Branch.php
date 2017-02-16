<?php

namespace App\Models;

class Branch extends Model
{
  public $table = 'branches';
  protected $fillable = ['name','description','person_id'];
  protected $modelRelations = array('Image','Address','Contact','ShopRelateTo');
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
    ),
    'messages' => array(
      'name.required' => 'ชื่อสาขาห้ามว่าง',
    )
  ); 

  public function __construct() {  
    parent::__construct();
  }

  public function buildModelData() {
    return array(
      'name' => $this->name
    );
  }

  public function buildPaginationData() {

    return array(
      'id' => $this->id,
      'name' => $this->name
    );
    
  }

}

