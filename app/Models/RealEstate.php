<?php

namespace App\Models;

use App\library\currency;
use App\library\string;

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

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
      // 'price' => 'required|regex:/^[\d,]*(\.\d{1,2})?$/|max:255',
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

  public function __construct() {  
    parent::__construct();
  }

  public function announcementType() {
    return $this->hasOne('App\Models\AnnouncementType','id','announcement_type_id');
  }

  public function realEstateType() {
    return $this->hasOne('App\Models\RealEstateType','id','real_estate_type_id');
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
      'description' => !empty($this->description) ? $this->description : '-',
      'need_broker' => $this->need_broker,
      '_furniture' => $furniture,
      '_need_broker' => $this->need_broker ? 'ต้องการตัวแทนขาย' : 'ไม่ต้องการตัวแทนขาย',
      '_price' => $currency->format($this->price),
      '_homeArea' => $_homeArea,
      '_landArea' => $_landArea,
      '_indoors' => $_indoor,
      '_facilities' => $facilities,
      '_features' => $features,
      '_announcementTypeName' => $this->announcementType->name,
      '_realEstateTypeName' => $this->realEstateType->name
    );
  }

  public function buildPaginationData() {

    $currency = new Currency;
    $string = new String;

    return array(
      'id' => $this->id,
      'name' => $this->name,
      '_name_short' => $string->subString($this->name,45),
      // 'description' => $this->description,
      '_price' => $currency->format($this->price),
      '_realEstateTypeName' => $this->realEstateType->name
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

}
