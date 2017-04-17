<?php

namespace App\Models;

use App\library\date;
use App\library\cache;

class Person extends Model
{
  protected $table = 'people';
  protected $fillable = ['user_id','name','gender','birth_date','profile_image_id','cover_image_id','theme','token'];
  protected $modelRelations = array('Image','Address','Contact');

  public $formHelper = true;
  public $modelData = true;

  public function __construct() {  
    parent::__construct();
  }

  public static function boot() {

    parent::boot();

    Person::saving(function($model){

      $image = new Image;

      if(!empty($model->modelRelationData['Image']['profile-image']['delete'])) {
        $image->deleteImages($model,$model->modelRelationData['Image']['profile-image']['delete']);
        $model->profile_image_id = null;
      }

      if(!empty($model->modelRelationData['Image']['profile-image']['image'])) {
        
        $imageId = $image->addImage($model,$model->modelRelationData['Image']['profile-image']['image'],array(
          'type' => 'profile-image',
          'token' => $model->modelRelationData['Image']['profile-image']['token']
        ));

        if(!empty($imageId)) {
          $model->profile_image_id = $imageId;
        }
        
        unset($model->modelRelationData['Image']['profile-image']);
      }

    });

  }

  public function personExperience() {
    return $this->hasOne('App\Models\PersonExperience','person_id','id');
  }

  public function __saveRelatedData($model,$options = array()) {

    $options['value'] = array_merge($options['value'],array(
      'user_id' => $model->id
    ));

    return $this->fill($options['value'])->save();
  }

  public function getGender($genderSign = null) {

    if(empty($genderSign)) {
      $genderSign = $this->gender;
    }

    $gender = 'ไม่ระบุ';
    switch ($genderSign) {
      case 'm':
        $gender = 'ชาย';
        break;
      
      case 'f':
        $gender = 'หญิง';
        break;

    }

    return $gender;

  }

  public function getAgeRange($alias) {

    $date = new Date;

    $currentDate = array(
      'year' => date('Y'),
      'month' => date('m'),
      'day' => date('d'),
    );

    $range = null;
    switch ($alias) {
      
      case 'ageRange1':
          $range = $date->findDateRange(18,24,$currentDate);
        break;

      case 'ageRange2':
          $range = $date->findDateRange(25,34,$currentDate);
        break;

      case 'ageRange3':
          $range = $date->findDateRange(35,44,$currentDate);
        break;

      case 'ageRange4':
          $range = $date->findDateRange(45,54,$currentDate);
        break;

      case 'ageRange5':
          $range = $date->findDateRange(55,60,$currentDate);
        break;

    }

    return $range;

  }

  public function getProfileImage() {

    $image = Image::select('id','model','model_id','filename','image_type_id')->find($this->profile_image_id);

    if(empty($image)) {
      return null;
    }

    return array(
      'id' => $image->id,
      '_url' => $image->getImageUrl()
    );
  }

  public function getProfileImageUrl($size = null) {

    $cache = new Cache;

    $image = Image::select('id','model','model_id','filename','image_type_id')->find($this->profile_image_id);

    if(empty($image)) {
      return '/images/common/no-img.png';
    }

    if(empty($size)) {
      return $image->getImageUrl();
    }

    return $cache->getCacheImageUrl($image,$size);

  }

  public function buildModelData() {

    $date = new Date;

    $gender = '-';
    if(!empty($this->gender)) {
      $gender = $this->getGender($this->gender);
    }

    $birthDate = '-';
    if(!empty($this->birth_date)) {
      $birthDate = $date->covertDateToSting($this->birth_date);
    }

    return array(
      'id' => $this->id,
      'name' => $this->name,
      'gender' => $gender,
      'birthDate' => $birthDate
    );

  }

  public function buildFormData() {

    $day = null;
    $month = null;
    $year = null;
    
    if(!empty($this->birth_date)) {
      list($year,$month,$day) = explode('-', $this->birth_date); 
    }

    return array(
      'name' => $this->name,
      'gender' => $this->gender,
      'private_websites' => $this->private_websites,
      'birth_day' => $day,
      'birth_month' => $month,
      'birth_year' => $year,
    );

  }

}
