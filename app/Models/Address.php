<?php

namespace App\Models;

class Address extends Model
{
  protected $table = 'addresses';
  protected $fillable = ['model','model_id','address','province_id','district_id','sub_district_id','description','latitude','longitude','created_by'];

  public $formHelper = true;

  public function __construct() {  
    parent::__construct();
  }

  public function province() {
    return $this->hasOne('App\Models\Province','id','province_id');
  }

  public function district() {
    return $this->hasOne('App\Models\District','id','district_id');
  }

  public function subDistrict() {
    return $this->hasOne('App\Models\SubDistrict','id','sub_district_id');
  }

  public function __saveRelatedData($model,$options = array()) {

    $address = $model->getRelatedData('Address',
      array(
        'first' => true
      )
    );

    // if(($model->state == 'update') && !empty($address)){
    if(!empty($address)){
      return $address
      ->fill($options['value'])
      ->save();
    }else{
      return $this->fill($model->includeModelAndModelId($options['value']))->save();
    }
    
  }

  public function getAddress($short = false,$zipCode = true) {

    $address = array();

    if($short) {
      if(!empty($this->subDistrict->name)) {
        $address[] = $this->subDistrict->name;
      }

      if(!empty($this->district->name)) {
        $address[] = $this->district->name;
      }

      if(!empty($this->province->name)) {
        $address[] = $this->province->name;
      }

      return implode(' ', $address);
    }

    if(!empty($this->address)) {
      $address[] = $this->address;
    }

    if(!empty($this->subDistrict->name)) {
      $address[] = $this->subDistrict->name;
    }

    if(!empty($this->district->name)) {
      $address[] = $this->district->name;
    }

    if(!empty($this->province->name)) {
      $address[] = $this->province->name;
    }

    if(!empty($this->district->zip_code) && $zipCode) {
      $address[] = $this->district->zip_code;
    }    

    return implode(' ', $address);

  }

  public function getProvinceName() {
    if(empty($this->province->name)) {
      return null;
    }

    return $this->province->name;
  }

  public function getDistrictName() {
    if(empty($this->district->name)) {
      return null;
    }

    return $this->district->name;
  }

  public function getSubDistrictName() {
    if(empty($this->subDistrict->name)) {
      return null;
    }

    return $this->subDistrict->name;
  }

  public function getLatLng() {

    $geographic = array();
    if(!empty($this->latitude) && !empty($this->latitude)) {
      $geographic['latitude'] = $this->latitude;
      $geographic['longitude'] = $this->longitude;
    }

    return $geographic;

  }

  public function buildModelData() {
    
    $geographic = array();
    if(!empty($this->latitude) && !empty($this->latitude)) {
      $geographic['latitude'] = $this->latitude;
      $geographic['longitude'] = $this->longitude;
    }

    // $subDistrictName = '';
    // $districtName = '';
    // $provinceName = '';

    // if(!empty($this->subDistrict->name)) {
    //   $subDistrictName = $this->subDistrict->name;
    // }

    // if(!empty($this->district->name)) {
    //   $districtName = $this->district->name;
    // }

    // if(!empty($this->province->name)) {
    //   $provinceName = $this->province->name;
    // }

    return array(
      '_province_name' => $this->getProvinceName(),
      '_district_name' => $this->getDistrictName(),
      '_sub_district_name' => $this->getSubDistrictName(),
      '_short_address' => $this->getAddress(true),
      '_long_address' => $this->getAddress(),
      '_geographic' => !empty($geographic) ? json_encode($geographic) : ''
    );
    
  }

  public function buildFormData() {
    
    $geographic = array();
    if(!empty($this->latitude) && !empty($this->latitude)) {
      $geographic['latitude'] = $this->latitude;
      $geographic['longitude'] = $this->longitude;
    }

    return array(
      'address' => $this->address,
      'province_id' => $this->province_id,
      'district_id' => $this->district_id,
      'sub_district_id' => $this->sub_district_id,
      '_geographic' => json_encode($geographic)
    );
    
  }

  public function getGeographic() {

    $geographic = array();
    if(!empty($this->latitude) && !empty($this->latitude)) {
      $geographic['latitude'] = $this->latitude;
      $geographic['longitude'] = $this->longitude;
    }
    
    return !empty($geographic) ? json_encode($geographic) : '';
  }

}
