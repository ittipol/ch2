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

  // public function fill(array $attributes) {

  //   if(!empty($attributes)) {

  //     if(!empty($attributes['private_websites'])) {

  //       $websites = array();
  //       foreach ($attributes['private_websites'] as $value) {
  //         if(empty($value['value'])) {
  //           continue;
  //         }

  //         $websites[] = array(
  //           'type' => trim($value['type']),
  //           'name' => trim($value['value'])
  //         );
  //       }

  //       $attributes['private_websites'] = '';
  //       if(!empty($websites)) {
  //         $attributes['private_websites'] = json_encode($websites);
  //       }

  //     }
      
  //   }

  //   return parent::fill($attributes);

  // }

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

    $personCareerObjective = PersonCareerObjective::select('career_objective')
    ->where('person_experience_id','=',$this->id)
    ->first();

    return array(
      'id' => $this->id,
      'name' => $person->name,
      // '_short_name' => $string->truncString($person->name,45),
      'careerObjective' => !empty($personCareerObjective->career_objective) ? $string->truncString($personCareerObjective->career_objective,150,true) : '-',
      '_imageUrl' => $imageUrl
    );
    
  }

}
