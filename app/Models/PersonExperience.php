<?php

namespace App\Models;

use App\library\date;

class PersonExperience extends Model
{
  protected $table = 'person_experiences';
  protected $fillable = ['person_id','name','gender','birth_date','private_websites','profile_image_id','active'];
  protected $modelRelations = array('Image','Address','Contact');
  protected $directory = true;

  public $formHelper = true;
  public $modelData = true;
  
  public $imageTypes = array(
    'profile-image' => array(
      'limit' => 1
    )
  );

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
    ),
    'messages' => array(
      'name.required' => 'ชื่อห้ามว่าง'
    )
  );

  public static function boot() {

    parent::boot();

    PersonExperience::saving(function($model){

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

  public function fill(array $attributes) {

    if(!empty($attributes)) {

      if(!empty($attributes['private_websites'])) {

        $websites = array();
        foreach ($attributes['private_websites'] as $value) {
          if(empty($value['value'])) {
            continue;
          }

          $websites[] = array(
            'type' => $value['type'],
            'name' => $value['value']
          );
        }

        $attributes['private_websites'] = '';
        if(!empty($websites)) {
          $attributes['private_websites'] = json_encode($websites);
        }

      }
      
    }

    return parent::fill($attributes);

  }

  public function getByPersonId() {
    return $this->where('person_id','=',session()->get('Person.id'))->first();
  }

  public function checkExistByPersonId() {
    return $this->where('person_id','=',session()->get('Person.id'))->exists();
  }

  public function getGender($gender = '-') {

    switch ($gender) {
      case 'm':
        $gender = 'ชาย';
        break;
      
      case 'f':
        $gender = 'หญิง';
        break;

      case '0':
        $gender = 'ไม่ระบุ';
        break;
    }

    return $gender;

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

  public function getProfileImageUrl() {

    $image = Image::select('id','model','model_id','filename','image_type_id')->find($this->profile_image_id);

    if(empty($image)) {
      return '';
    }

    return $image->getImageUrl();
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
