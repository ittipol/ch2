<?php

namespace App\Models;

use App\library\currency;
use App\library\stringHelper;
use App\library\cache;
use App\library\url;

class RealEstate extends Model
{
  protected $table = 'real_estates';
  protected $fillable = ['announcement_type_id','real_estate_type_id','name','description','price','home_area','land_area','indoor','furniture','facility','feature','need_broker',];
  protected $modelRelations = array('Image','Address','Tagging','Contact');
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
        'keyword_1' => '{{__getAnnouncementType}} {{__getRealEstateType}}',
        'keyword_2' => '{{__getFacility}} {{__getFeature}}',
        'keyword_4' => '{{price}}'
      )
    ),
    'DataAccessPermission' => array(
      'owner' => 'Person',
      'defaultAccessLevel' => 99
    )
  );

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
      'price' => 'required|regex:/^[0-9,]*(\.[0-9]{1,2})?$/|max:255',
      'Contact.phone_number' => 'required|max:255',
      'real_estate_type_id' => 'required' 
    ),
    'messages' => array(
      'name.required' => 'ชื่อที่ต้องการประกาศห้ามว่าง',
      'price.required' => 'จำนวนราคาห้ามว่าง',
      'price.regex' => 'รูปแบบจำนวนราคาไม่ถูกต้อง',
      'Contact.phone_number.required' => 'หมายเลขโทรศัพท์ห้ามว่าง',
      'real_estate_type_id.required' => 'ประเภทอสังหาริมทรัพย์ห้ามว่าง',
    )
  );

  protected $filterOptions = array(
    'announcement' => array(
      'input' => 'checkbox',
      'title' => 'แสดงประกาศ',
      'options' => array(
        array(
          'name' => 'ประกาศซื้อ',
          'value' => 'announcement_type_id:1',
        ),
        array(
          'name' => 'ประกาศขาย',
          'value' => 'announcement_type_id:2',
        ),
        array(
          'name' => 'ประกาศให้เช่า',
          'value' => 'announcement_type_id:3',
        )
      )
    ),
    'used' => array(
      'input' => 'checkbox',
      'title' => 'เฟอร์นิเจอร์',
      'options' => array(
        array(
          'name' => 'ไม่มี',
          'value' => 'furniture:e'
        ),
        array(
          'name' => 'มีบางส่วน',
          'value' => 'furniture:s'
        ),
        array(
          'name' => 'ตกแต่งครบ',
          'value' => 'furniture:f'
        )
      )
    ),
    'broker' => array(
      'input' => 'checkbox',
      'title' => 'ตัวแทนขาย',
      'options' => array(
        array(
          'name' => 'ต้องการตัวแทนขาย',
          'value' => 'need_broker:1'
        ),
        array(
          'name' => 'ไม่ต้องการตัวแทนขาย',
          'value' => 'need_broker:0'
        )
      )
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

  public function announcementType() {
    return $this->hasOne('App\Models\AnnouncementType','id','announcement_type_id');
  }

  public function realEstateType() {
    return $this->hasOne('App\Models\RealEstateType','id','real_estate_type_id');
  }

  public static function boot() {

    parent::boot();

    RealEstate::saving(function($realEstate){

      if(!$realEstate->exists && empty($realEstate->opened)) {
        $realEstate->opened = 1;
      }

    });

  }

  public function fill(array $attributes) {

    if(!empty($attributes)) {
      $attributes['price'] = str_replace(',', '', $attributes['price']);

      if(!empty($attributes['feature'])) {
        $attributes['feature'] = json_encode($attributes['feature']);
      }

      if(!empty($attributes['facility'])) {
        $attributes['facility'] = json_encode($attributes['facility']);
      }

      if(!empty($attributes['home_area'])) {
        $attributes['home_area'] = json_encode($attributes['home_area']);
      }

      if(!empty($attributes['land_area'])) {
        $attributes['land_area'] = json_encode($attributes['land_area']);
      }

      if(!empty($attributes['indoor'])) {
        $attributes['indoor'] = json_encode($attributes['indoor']);
      }
      
    }

    return parent::fill($attributes);

  }

  public function getAnnouncementType() {
    return $this->announcementType->name;
  }

  public function getRealEstateType() {
    return $this->realEstateType->name;
  }

  public function getFacility() {
    $facility = json_decode($this->facility,true);

    $facilities = array();
    if(!empty($facility)) {
      $facility = RealEstateFeature::whereIn('id',$facility)->get();
      $facilities = array();
      foreach ($facility as $value) {
        $facilities[] = $value->name;
      }
    }

    return implode(' ', $facilities);
  }

  public function getFeature() {
    $feature = json_decode($this->feature,true);

    $features = array();
    if(!empty($feature)) {
      $feature = RealEstateFeature::whereIn('id',$feature)->get();
      $features = array();
      foreach ($feature as $value) {
        $features[] = $value->name;
      }
    }

    return implode(' ', $features);
  }

  public function buildModelData() {

    $facility = json_decode($this->facility,true);

    $facilities = array();
    if(!empty($facility)) {
      $facility = RealEstateFeature::whereIn('id',$facility)->get();
      $facilities = array();
      foreach ($facility as $value) {
        $facilities[] = array(
          'id' =>  $value->id,
          'name' =>  $value->name
        );
      }
    }

    $feature = json_decode($this->feature,true);

    $features = array();
    if(!empty($feature)) {
      $feature = RealEstateFeature::whereIn('id',$feature)->get();
      $features = array();
      foreach ($feature as $value) {
        $features[] = array(
          'id' =>  $value->id,
          'name' =>  $value->name
        );
      }
    }

    $homeArea = json_decode($this->home_area,true);

    $_homeArea = '-';
    if(!empty($homeArea['sqm'])) {
      $_homeArea = $homeArea['sqm'].' ตารางเมตร';
    }

    $landArea = json_decode($this->land_area,true);

    $_landAreaSqm = '';
    if(!empty($landArea['sqm'])) {
      $_landAreaSqm = $landArea['sqm'].' ตารางเมตร';
    }

    $_landArea = '';
    if(!empty($landArea['rai'])) {
      $_landArea .= $landArea['rai'].' ไร่ ';
    }
    if(!empty($landArea['ngan'])) {
      $_landArea .= $landArea['ngan'].' งาน ';
    }
    if(!empty($landArea['wa'])) {
      $_landArea .= $landArea['wa'].' ตารางวา ';
    }

    if(!empty($_landAreaSqm) && !empty($_landArea)) {
      $_landArea = trim($_landAreaSqm.'/'.$_landArea);
    }elseif(!empty($_landAreaSqm)) {
      $_landArea = trim($_landAreaSqm);
    }else{
      $_landArea = '-';
    }

    $furniture = '-';
    if(!empty($this->furniture)) { 

      switch ($this->furniture) {
        case 'e':
          $furniture = 'ไม่มี';
          break;
        
        case 's':
          $furniture = 'มีบางส่วน';
          break;

        case 'f':
          $furniture = 'ตกแต่งครบ';
          break;
      }

    }

    $indoor = json_decode($this->indoor,true);

    $rooms = array(
      'bedroom' => 'ห้องนอน',
      'bathroom' => 'ห้องน้ำ',
      'living_room' => 'ห้องนั่งเล่น',
      'home_office' => 'ห้องทำงาน',
      'floors' => 'จำนวนชั้น',
      'carpark' => 'ที่จอดรถ'
    );

    $_indoor = array();
    foreach ($indoor as $room => $value) {
      $_indoor[] = array(
        'room' => $room,
        'name' => $rooms[$room],
        'value' => $value
      );
    }

    $currency = new Currency;

    return array(
      'id' => $this->id,
      'announcement_type_id' => $this->announcement_type_id,
      'real_estate_type_id' => $this->real_estate_type_id,
      'name' => $this->name,
      'description' => !empty($this->description) ? nl2br($this->description) : '-',
      'need_broker' => $this->need_broker,
      '_need_broker' => $this->need_broker ? 'ต้องการตัวแทนขาย' : 'ไม่ต้องการตัวแทนขาย',
      '_furniture' => $furniture,
      '_price' => $currency->format($this->price),
      '_homeArea' => $_homeArea,
      '_landArea' => $_landArea,
      '_indoors' => $_indoor,
      '_facilities' => $facilities,
      '_features' => $features,
      '_announcementTypeName' => $this->announcementType->name,
      '_realEstateTypeName' => $this->realEstateType->name,
      'created_by' => $this->created_by
    );
  }

  public function buildPaginationData() {

    $currency = new Currency;
    $string = new stringHelper;

    return array(
      'id' => $this->id,
      'name' => $this->name,
      '_short_name' => $string->truncString($this->name,25),
      '_price' => $currency->format($this->price),
      '_realEstateTypeName' => $this->realEstateType->name,
      '_announcementTypeName' => $this->announcementType->name,
      'need_broker' => $this->need_broker,
      '_need_broker' => $this->need_broker ? 'ต้องการตัวแทนขาย' : 'ไม่ต้องการตัวแทนขาย',
    );
  }

  public function buildFormData() {
    return array(
      'id' => $this->id,
      'announcement_type_id' => $this->announcement_type_id,
      'real_estate_type_id' => $this->real_estate_type_id,
      'name' => $this->name,
      'description' => $this->description,
      'price' => $this->price,
      'home_area' => json_decode($this->home_area,true),
      'land_area' => json_decode($this->land_area,true),
      'indoor' => json_decode($this->indoor,true),
      'furniture' => $this->furniture,
      'facility' => json_decode($this->facility,true),
      'feature' => json_decode($this->feature,true),
      'need_broker' => $this->need_broker,
    );
  }

  public function buildLookupData() {

    $currency = new Currency;
    $string = new stringHelper;
    $url = new url;

    $needBroker = '';
    if($this->need_broker) {
      $needBroker = 'ต้องการตัวแทนขาย';
    }

    return array(
      'title' => $string->truncString($this->name,90),
      'description' => $string->truncString($this->description,250),
      'flags' => array(
        'ประกาศ'.$this->announcementType->name,
        $needBroker
      ),
      'data' => array(
        'price' => array(
          'title' => 'ราคา'.$this->announcementType->name,
          'value' => $currency->format($this->price)
        )
      ),
      'detailUrl' => $url->setAndParseUrl('real-estate/detail/{id}',array('id' => $this->id)),
      'image' => $this->getImage('list'),
      'isDataTitle' => 'ประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์'
    );

  }

}
