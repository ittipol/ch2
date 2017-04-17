<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
use App\library\filterHelper;
use App\library\date;
use App\library\url;
use Redirect;
use Session;

class PersonExperienceController extends Controller
{
  public function __construct() { 
    parent::__construct();
  }

  public function listView() {

    $model = Service::loadModel('PersonExperience');
    $filterHelper = new FilterHelper($model);
    
    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $filters = '';
    if(!empty($this->query['fq'])) {
      $filters = $this->query['fq'];
    }

    $sort = '';
    if(!empty($this->query['sort'])) {
      $sort = $this->query['sort'];
    }

    $filterHelper->setFilters($filters);
    $filterHelper->setSorting($sort);

    $conditions = $filterHelper->buildFilters();
    $order = $filterHelper->buildSorting();
    $joins = $filterHelper->getJoining();

    $criteria = array();

    if(!empty($joins)) {
      $criteria = array_merge($criteria,array(
        'joins' => $joins
      ));
    }

    if(!empty($conditions)) {
      $criteria = array_merge($criteria,array(
        'conditions' => $conditions
      ));
    }

    if(!empty($order)) {
      $criteria = array_merge($criteria,$order);
    }

    $criteria = array_merge($criteria,array(
      'fields' => array('person_experiences.*')
    ));

    $model->paginator->criteria($criteria);
    $model->paginator->disableGetImage();
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('experience/profile/list');
    $model->paginator->setUrl('experience/profile/{id}','detailUrl');
    $model->paginator->setQuery('sort',$sort);
    $model->paginator->setQuery('fq',$filters);

    $searchOptions = array(
      'filters' => $filterHelper->getFilterOptions(),
      'sort' => $filterHelper->getSortingOptions()
    );

    $displayingFilters = array(
      'filters' => $filterHelper->getDisplayingFilterOptions(),
      'sort' => $filterHelper->getDisplayingSorting()
    );


    $this->data = $model->paginator->buildPermissionData();
    $this->setData('searchOptions',$searchOptions);
    $this->setData('displayingFilters',$displayingFilters);

    return $this->view('pages.person_experience.list');
  }

  public function detail() {

    $person = Service::loadModel('Person')->find(Session::get('Person.id'));

    $person->modelData->loadData(array(
      'models' => array('Address','Contact')
    ));

    $this->data = $person->personExperience->getPersonExperience();
    $this->setData('profile',$person->modelData->build(true));
    $this->setData('profileImageUrl',$person->getProfileImageUrl());

    return $this->view('pages.person_experience.detail');

  }

  public function manage() {

    $url = new Url;

    $model = Service::loadModel('PersonExperience');

    if(!$model->checkExistByPersonId()) {
      return $this->view('pages.person_experience.start');
    }

    $person = Service::loadModel('Person')->find(Session::get('Person.id'));

    $this->data = $person->personExperience->getPersonExperience();
    $this->setData('experienceDetailUrl',$url->setAndParseUrl('experience/profile/{id}',array('id' => $person->personExperience->id)));

    return $this->view('pages.person_experience.manage');

  }

  public function profile() {

    $url = new Url;

    // $model = Service::loadModel('PersonExperience');

    $profile = Service::loadModel('PersonExperience')
    ->select(array('id','active'))
    ->where('person_id','=',Session::get('Person.id'))
    ->first();

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

    // Get page permission
    $pagePermission = $profile->getRelatedData('DataAccessPermission',
      array(
        'first' => true,
        'fields' => array('access_level')
      )
    );

    $_formData = array();
    if(!empty($pagePermission)) {
      $_formData = array(
        'access_level' => $pagePermission->access_level
      );
    }

    $accessLevels = Service::loadModel('AccessLevel')->getLevel();

    $this->setData('profile',$profile->modelData->build(true));
    $this->setData('careerObjective',$careerObjective->career_objective);
    $this->setData('skills',$_skills);
    $this->setData('languageSkills',$_languageSkills);
    $this->setData('accessLevels',$accessLevels);
    $this->setData('_formData',$_formData);

    return $this->view('pages.person_experience.profile');

  }

  public function start() {

    $model = Service::loadModel('PersonExperience');

    if(!$model->checkExistByPersonId()) {
      
      $model->fill(array(
        'name' => $person->name,
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

  public function profileEditingSubmit() {

    $model = Service::loadModel('PersonExperience')->where('person_id','=',Session::get('Person.id'))->first();

    $saved = Service::loadModel('DataAccessPermission')->__saveRelatedData($model,array(
      'value' => array(
        'access_level' => request()->get('access_level')
      )
    ));

    if($saved) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('experience/profile/edit');
    }else{
      MessageHelper::display('เกิดข้อผิดพลาด ไม่สามารถบันทึกได้','error');
      return Redirect::to('experience/profile/edit');
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
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('experience/profile/edit');
    }else{
      return Redirect::back();
    }

  }

}
