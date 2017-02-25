<?php

namespace App\Models;

use App\library\string;

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

  public function buildModelData() {

    $string = new String;

    return array(
      'id' => $this->id,
      'name' => $this->name,
      'description' => !empty($this->description) ? $this->description : '-',
      '_short_name' => $string->subString($this->name,60),
      '_advertisingType' => AdvertisingType::select(array('name'))->find($this->advertising_type_id)->name
    );
    
  }

  public function buildPaginationData() {

    $string = new String;

    $advertisingType = AdvertisingType::select(array('name'))->find($this->advertising_type_id);

    return array(
      'id' => $this->id,
      'name' => $this->name,
      '_short_name' => $string->subString($this->name,60),
      '_advertisingType' => $advertisingType->name
    );
    
  }
}
