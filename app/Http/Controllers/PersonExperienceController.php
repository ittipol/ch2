<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\message;
use App\library\date;
use App\library\url;
use Redirect;
use Session;

class PersonExperienceController extends Controller
{
  public function __construct() { 
    parent::__construct();
  }

  public function index() {

    $model = Service::loadModel('PersonExperience');

    if(!$model->checkExistByPersonId()) {
      return $this->view('pages.person_experience.start');
    }

    $url = new Url;

    $profile = $model
    ->select(array('id','name','gender','birth_date','private_websites','profile_image_id','active'))
    ->where('person_id','=',Session::get('Person.id'))
    ->first();

    $profile->modelData->loadData(array(
      'models' => array('Address','Contact')
    ));

    $this->setData('profile',$profile->modelData->build(true));
    $this->setData('profileImageUrl',$profile->getProfileImageUrl());

    return $this->view('pages.person_experience.main');

  }

  public function profile() {

    $url = new Url;

    $model = Service::loadModel('PersonExperience');

    $profile = $model
    ->select(array('id','name','gender','birth_date','private_websites','profile_image_id','active'))
    ->where('person_id','=',Session::get('Person.id'))
    ->first();

    $profile->modelData->loadData(array(
      'models' => array('Address','Contact')
    ));

    // Get career objective
    $careerObjective = Service::loadModel('PersonCareerObjective')
    ->select(array('id','career_objective'))
    ->where('person_id','=',Session::get('Person.id'))
    ->first();

    // Get skill
    $skills = Service::loadModel('PersonSkill')->where('person_experience_id','=',$profile->id)->get();

    $url->setUrl('experience/skill_edit/{id}','editUrl');
    $url->setUrl('experience/skill_delete/{id}','deleteUrl');

    $_skills = array();
    foreach ($skills as $skill) {
      $_skills[] = array_merge(array(
        'skill' => $skill->skill,
      ),$url->parseUrl($skill->getAttributes())); 
    }

    // Get language skill
    $languageSkills = Service::loadModel('PersonLanguageSkill')->where('person_experience_id','=',$profile->id)->get();
   
    $url->clearUrls();
    $url->setUrl('experience/language_skill_edit/{id}','editUrl');
    $url->setUrl('experience/language_skill_delete/{id}','deleteUrl');

    $_languageSkills = array();
    foreach ($languageSkills as $languageSkill) {
      $_languageSkills[] = array_merge(array(
        'name' => $languageSkill->language->name,
        'level' => $languageSkill->languageSkillLevel->name
      ),$url->parseUrl($languageSkill->getAttributes()));
    }

    $models = array(
      'PersonWorkingExperience' => 'working',
      'PersonInternship' => 'internship',
      'PersonEducation' => 'education',
      'PersonProject' => 'project',
      'PersonCertificate' => 'certificate'
    );

    foreach ($models as $model => $alias) {
      $experienceDetails = Service::loadModel('PersonExperienceDetail')
      ->orderBy('start_year','DESC')
      ->orderBy('start_month','DESC')
      ->orderBy('start_day','DESC')
      ->select(array('model','model_id','start_year','start_month','start_day','end_year','end_month','end_day','current'))
      ->where(array(
        array('person_experience_id','=',$profile->id),
        array('model','like',$model)
      ))
      ->get();

      $url->clearUrls();
      $url->setUrl('experience/'.$alias.'_edit/{id}','editUrl');
      $url->setUrl('experience/'.$alias.'_delete/{id}','deleteUrl');

      $details = array();
      foreach ($experienceDetails as $experienceDetail) {
        
        $_model = $experienceDetail->{lcfirst($experienceDetail->model)};

        if(empty($_model)) {
          continue;
        }

        $details[] = array_merge(
          $_model->buildModelData(),
          array('peroid' => $experienceDetail->getPeriod()),
          $url->parseUrl($_model->getAttributes())
        );

      }

      $this->setData($model,$details);

    }

    $this->setData('profile',$profile->modelData->build(true));
    $this->setData('careerObjective',$careerObjective->career_objective);
    $this->setData('profileImageUrl',$profile->getProfileImageUrl());
    $this->setData('skills',$_skills);
    $this->setData('languageSkills',$_languageSkills);

    return $this->view('pages.person_experience.profile');

  }

  public function start() {

    $model = Service::loadModel('PersonExperience');

    if(!$model->checkExistByPersonId()) {
      
      $model->fill(array(
        'name' => Session::get('Person.name'),
        'active' => 0
      ))->save();

      Service::loadModel('PersonCareerObjective')
      ->fill(array(
        'person_experience_id' => $model->id
      ))
      ->save();

    }

    return Redirect::to('experience');

  }

  public function profileEdit() {

    $model = Service::loadModel('PersonExperience')->where('person_id','=',Session::get('Person.id'))->first();

    $model->formHelper->loadFieldData('Province',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'provinces',
      'order' => array(
        array('top','ASC'),
        array('id','ASC')
      )
    ));

    $model->formHelper->setData('websiteTypes',json_encode(array(
      array('private-website','เว็บไซต์ส่วนตัว'),
      array('blog','บล็อก'),
      array('company-website','เว็บไซต์บริษัท')
    )));

    $date = new Date;

    $latestYear = date('Y');
    
    $day = array();
    $month = array();
    $year = array();

    for ($i=1; $i <= 31; $i++) { 
      $day[$i] = $i;
    }

    for ($i=1; $i <= 12; $i++) { 
      $month[$i] = $date->getMonthName($i);
    }

    for ($i=1957; $i <= $latestYear; $i++) { 
      $year[$i] = $i+543;
    }

    $model->formHelper->loadData(array(
      'model' => array(
        'Address','Contact'
      )
    ));

    $this->data = $model->formHelper->build();
    $this->setData('profileImage',json_encode($model->getProfileImage()));
    $this->setData('day',$day);
    $this->setData('month',$month);
    $this->setData('year',$year);

    return $this->view('pages.person_experience.form.profile_edit');

  }

  public function profileEditingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('PersonExperience')->where('person_id','=',Session::get('Person.id'))->first();

    if($model->fill($request->all())->save()) {
      Message::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('experience/profile');
    }else{
      return Redirect::back();
    }

  }

  public function careerObjectiveEdit() {

    $model = Service::loadModel('PersonCareerObjective')
    ->select(array('id','career_objective'))
    ->where('person_id','=',Session::get('Person.id'))
    ->first();

    $this->data = $model->formHelper->build();
    $this->setData('careerObjective',$model->career_objective);

    return $this->view('pages.person_experience.form.career_objective_edit');

  }

  public function careerObjectiveEditingSubmit() {

    $model = Service::loadModel('PersonCareerObjective')
    ->select(array('id','career_objective'))
    ->where('person_id','=',Session::get('Person.id'))
    ->first();

    if($model->fill(request()->all())->save()) {
      Message::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('experience/profile');
    }else{
      return Redirect::back();
    }

  }

}
