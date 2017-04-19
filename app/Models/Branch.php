<?php

namespace App\Models;

use App\library\string;
use App\library\cache;
use App\library\url;

class Branch extends Model
{
  public $table = 'branches';
  protected $fillable = ['name','description','person_id'];
  protected $modelRelations = array('Image','Address','Contact','ShopRelateTo','RelateToBranch');
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

  protected $sortingFields = array(
    'title' => 'จัดเรียงตาม',
    'options' => array(
      array(
        'name' => 'ตัวอักษร A - Z ก - ฮ',
        'value' => 'name:asc'
      ),
      array(
        'name' => 'ตัวอักษร Z - A ฮ - ก',
        'value' => 'name:desc'
      ),
      array(
        'name' => 'วันที่เก่าที่สุดไปหาใหม่ที่สุด',
        'value' => 'created_at:asc'
      ),
      array(
        'name' => 'วันที่ใหม่ที่สุดไปหาเก่าที่สุด',
        'value' => 'created_at:desc'
      ),
    ),
    'default' => 'created_at:desc'
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

  public function buildLookupData() {

    $string = new String;
    $cache = new Cache;
    $url = new url;

    $image = $this->getRelatedData('Image',array(
      'first' => true
    ));

    $_imageUrl = '/images/common/no-img.png';
    if(!empty($image)) {
      $_imageUrl = $cache->getCacheImageUrl($image,'list');
    }

    $slug = ShopRelateTo::select('shop_id')
    ->where(array(
      array('model','like','Branch'),
      array('model_id','=',$this->id)
    ))
    ->first()
    ->shop->getRelatedData('Slug',array(
      'fields' => array('slug'),
      'first' => true
    ))
    ->slug;

    return array(
      'id' => $this->id,
      'name' => $this->name,
      '_short_name' => $string->truncString($this->name,60),
      '_detailUrl' => $url->url('shop/'.$slug.'/branch/'.$this->id),
      '_imageUrl' => $_imageUrl,
      'dataFromFlag' => 'สาขา'
    );
    
  }

}

