<?php

namespace App\Models;

use App\library\string;
use App\library\cache;
use App\library\url;

class Advertising extends Model
{
  public $table = 'advertisings';
  protected $fillable = ['advertising_type_id','name','description','person_id'];
  protected $modelRelations = array('Image','Tagging','RelateToBranch','ShopRelateTo');
  protected $directory = true;

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  protected $behavior = array(
    'Lookup' => array(
      'format' =>  array(
        'name' => '{{name}}',
        'keyword_1' => '{{__Shop|getShopName}}',
        'keyword_2' => '{{AdvertisingType.name|Advertising.advertising_type_id=>AdvertisingType.id}}',
      )
    ),
    'DataAccessPermission' => array(
      'owner' => 'Shop',
      'defaultAccessLevel' => 99
    )
  );

  public $imageTypes = array(
    'photo' => array(
      'limit' => 10
    )
  );

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
      'advertising_type_id' => 'required',
    ),
    'messages' => array(
      'name.required' => 'ชื่อโฆษณาหรือหัวข้อโฆษณาห้ามว่าง',
      'advertising_type_id.required' => 'ประเภทของโฆษณาห้ามว่าง',
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

  public function buildModelData() {

    $string = new String;

    return array(
      'id' => $this->id,
      'name' => $this->name,
      'description' => !empty($this->description) ? $this->description : '-',
      '_short_name' => $string->truncString($this->name,60),
      '_advertisingType' => AdvertisingType::select(array('name'))->find($this->advertising_type_id)->name
    );
    
  }

  public function buildPaginationData() {

    $string = new String;

    return array(
      'id' => $this->id,
      'name' => $this->name,
      '_short_name' => $string->truncString($this->name,60),
      '_advertisingType' => AdvertisingType::select(array('name'))->find($this->advertising_type_id)->name
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
    
    return array(
      'id' => $this->id,
      'name' => $this->name,
      '_short_name' => $string->truncString($this->name,60),
      '_advertisingType' => AdvertisingType::select(array('name'))->find($this->advertising_type_id)->name,
      '_detailUrl' => $url->setAndParseUrl('advertising/detail/{id}',array('id' => $this->id)),
      '_imageUrl' => $_imageUrl,
      'dataFromFlag' => 'โฆษณาจากบริษัทและร้านค้า'
    );
    
  }

}
