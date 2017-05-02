<?php

namespace App\Models;

use App\library\stringHelper;
use App\library\cache;
use App\library\url;

class Advertising extends Model
{
  public $table = 'advertisings';
  protected $fillable = ['advertising_type_id','name','description','created_by'];
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

  public function advertisingType() {
    return $this->hasOne('App\Models\AdvertisingType','id','advertising_type_id');
  }

  public function buildModelData() {

    $string = new stringHelper;

    return array(
      'id' => $this->id,
      'name' => $this->name,
      'description' => !empty($this->description) ? nl2br($this->description) : '-',
      '_short_name' => $string->truncString($this->name,60),
      '_advertisingType' => AdvertisingType::select(array('name'))->find($this->advertising_type_id)->name
    );
    
  }

  public function buildPaginationData() {

    $string = new stringHelper;

    return array(
      'id' => $this->id,
      'name' => $this->name,
      '_short_name' => $string->truncString($this->name,60),
      '_advertisingType' => AdvertisingType::select(array('name'))->find($this->advertising_type_id)->name
    );
    
  }

  public function buildLookupData() {

    $string = new stringHelper;
    $url = new url;

    return array(
      'title' => $string->truncString($this->name,90),
      'description' => $string->truncString($this->description,250),
      'data' => array(
        'advertisingType' => array(
          'title' => 'ประเภทการโฆษณา',
          'value' => $this->advertisingType->name
        )
      ),
      'detailUrl' => $url->setAndParseUrl('item/detail/{id}',array('id' => $this->id)),
      'image' => $this->getImage('list'),
      'isDataTitle' => 'โฆษณาจากบริษัทและร้านค้า'
    );
    
  }

}
