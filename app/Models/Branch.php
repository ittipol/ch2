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

  protected $behavior = array(
    'Lookup' => array(
      'format' =>  array(
        'name' => '{{name}}',
        'keyword_1' => '{{__Shop|getShopName}}'
      )
    ),
    'DataAccessPermission' => array(
      'owner' => 'Shop',
      'defaultAccessLevel' => 99
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

  public function getBranches($shopId,$build = true) {

    $branches = $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', 'branches.id')
    ->where('shop_relate_to.model','like','Branch')
    ->where('shop_relate_to.shop_id','=',request()->get('shopId'))
    ->select('branches.id','branches.name');

    if(!$branches->exists()) {
      return null;
    }

    if(!$build) {
      return $branches->get();
    }

    $_branches = array();
    foreach ($branches->get() as $branch) {
      $_branches[] = $branch->buildModelData();
    }

    return $_branches;

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

