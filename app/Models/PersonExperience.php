<?php

namespace App\Models;

use App\library\service;
use App\library\string;
use App\library\cache;
use App\library\url;

class PersonExperience extends Model
{
  protected $table = 'person_experiences';
  protected $fillable = ['person_id','active'];
  protected $directory = true;

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;
  
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

  protected $behavior = array(
    // 'Lookup' => array(
    //   'format' =>  array(
    //     'name' => '{{name}}',
    //   ),
    //   'active' => 0
    // ),
    'DataAccessPermission' => array(
      'owner' => 'Person',
      'defaultAccessLevel' => 99
    )
  );

  protected $filterOptions = array(
    'gender' => array(
      'input' => 'checkbox',
      'title' => 'เพศ',
      'options' => array(
        array(
          'name' => 'ชาย',
          'value' => 'gender:m',
        ),
        array(
          'name' => 'หญิง',
          'value' => 'gender:f',
        )
      )
    ),
    'age' => array(
      'input' => 'checkbox',
      'title' => 'ช่วงอายุ',
      'options' => array(
        array(
          'name' => '18-24 ปี',
          'value' => 'age:ageRange1'
        ),
        array(
          'name' => '25-34 ปี',
          'value' => 'age:ageRange2'
        ),
        array(
          'name' => '35-44 ปี',
          'value' => 'age:ageRange3'
        ),
        array(
          'name' => '45-54 ปี',
          'value' => 'age:ageRange4'
        ),
        array(
          'name' => '55-60 ปี',
          'value' => 'age:ageRange5'
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

  public function getByPersonId() {
    return $this->where('person_id','=',session()->get('Person.id'))->first();
  }

  public function checkExistByPersonId() {
    return $this->where('person_id','=',session()->get('Person.id'))->exists();
  }

  public function getPersonExperience() {

    $url = new Url;

    $data = array();

    // Get career objective
    $careerObjective = Service::loadModel('PersonCareerObjective')
    ->select(array('id','career_objective'))
    ->where('person_experience_id','=',$this->id)
    ->first();

    //
    $personPrivateWebsites = Service::loadModel('PersonPrivateWebsite')->where('person_experience_id','=',$this->id)->get();

    $_privateWebsites = array();
    foreach ($personPrivateWebsites as $personPrivateWebsite) {
      $_privateWebsites[] = array(
        'website_url' => $personPrivateWebsite->website_url,
        'websiteType' => $personPrivateWebsite->websiteType->name,
        'url' => $url->redirect($personPrivateWebsite->website_url)
      );
    }

    // Get skill
    $skills = Service::loadModel('PersonSkill')->where('person_experience_id','=',$this->id)->get();

    $_skills = array();
    foreach ($skills as $skill) {
      $_skills[] = array(
        'skill' => $skill->skill
      );
    }

    // Get language skill
    $languageSkills = Service::loadModel('PersonLanguageSkill')->where('person_experience_id','=',$this->id)->get();

    $_languageSkills = array();
    foreach ($languageSkills as $languageSkill) {
      $_languageSkills[] = array(
        'name' => $languageSkill->language->name,
        'level' => $languageSkill->languageSkillLevel->name
      );
    }

    $models = array(
      'PersonWorkingExperience' => 'working',
      'PersonInternship' => 'internship',
      'PersonEducation' => 'education',
      'PersonProject' => 'project',
      'PersonCertificate' => 'certificate'
    );

    foreach ($models as $_model => $alias) {
      $experienceDetails = Service::loadModel('PersonExperienceDetail')
      ->orderBy('start_year','DESC')
      ->orderBy('start_month','DESC')
      ->orderBy('start_day','DESC')
      ->select(array('model','model_id','start_year','start_month','start_day','end_year','end_month','end_day','current'))
      ->where(array(
        array('person_experience_id','=',$this->id),
        array('model','like',$_model)
      ))
      ->get();

      $details = array();
      foreach ($experienceDetails as $experienceDetail) {
        
        $__model = $experienceDetail->{lcfirst($experienceDetail->model)};

        if(empty($__model)) {
          continue;
        }

        $details[] = array_merge(
          $__model->buildModelData(),
          array(
            'peroid' => $experienceDetail->getPeriod()
          )
        );

      }

      $data[$_model] = $details;

    }

    $data['careerObjective'] = $careerObjective->career_objective;
    $data['privateWebsites'] = $_privateWebsites;
    $data['skills'] = $_skills;
    $data['languageSkills'] = $_languageSkills;

    return $data;

  }

  public function buildPaginationData() {

    $image = new Image;
    $string = new String;
    $cache = new Cache;

    $person = Person::find($this->person_id);

    $imageUrl = '/images/common/no-img.png';
    if(!empty($person->profile_image_id)) {
      $image = $image
      ->select(array('model','model_id','filename','image_type_id'))
      ->find($person->profile_image_id);

      $imageUrl = $cache->getCacheImageUrl($image,'list');

    }

    // $personCareerObjective = PersonCareerObjective::select('career_objective')
    // ->where('person_experience_id','=',$this->id)
    // ->first();

    // Get lastest working exp.
    $workingExperience = PersonWorkingExperience::where('person_id','=',$person->id)
    ->orderBy('created_at','desc')
    ->select('position')
    ->first();

    $position = '-';
    if(!empty($workingExperience->position)) {
      $position = $workingExperience->position;
    }

    return array(
      'id' => $this->id,
      'name' => $person->name,
      'gender' => $person->getGender(),
      // '_short_name' => $string->truncString($person->name,45),
      // 'careerObjective' => !empty($personCareerObjective->career_objective) ? $string->truncString($personCareerObjective->career_objective,150,true) : '-',
      'position' => $position,
      '_imageUrl' => $imageUrl
    );
    
  }

}
