<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\messageHelper;
use Redirect;

class PersonLanguageSkillController extends Controller
{
  public function add() {

    $model = Service::loadModel('PersonLanguageSkill');

    $this->getLanguages();
    $this->data = $model->formHelper->build();

    return $this->view('pages.person_experience.form.pereson_language_skill_add');

  }

  public function addingSubmit() {

    $model = Service::loadModel('PersonLanguageSkill');

    $personExperience = Service::loadModel('PersonExperience')
    ->select(array('id'))
    ->where('created_by','=',session()->get('Person.id'))
    ->first();

    foreach (request()->get('languages') as $value) {

      if(!empty($value) && !$model->checkExistByLanguageId($value['language'])) {
        $model->newInstance()->fill(array(
          'person_experience_id' => $personExperience->id,
          'language_id' => $value['language'],
          'language_skill_level_id' => $value['level']
        ))->save();
      }
    }

    MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
    return Redirect::to('experience/profile/edit');

  }

  public function edit() {

    $model = Service::loadModel('PersonLanguageSkill')->where('id','=',$this->param['id'])->first();

    $this->getLanguages(false);
    $this->data = $model->formHelper->build();

    return $this->view('pages.person_experience.form.pereson_language_skill_edit');

  }

  public function editingSubmit() {

    $model = Service::loadModel('PersonLanguageSkill')->where('id','=',$this->param['id'])->first();

    if($model->fill(request()->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('experience/profile/edit');
    }else{
      return Redirect::back();
    }
    
  }

  private function getLanguages($json = true) {

    $languages = Service::loadModel('Language')->where('active','=',1)->select('id','name')->get();

    $languageSkillLevels = Service::loadModel('LanguageSkillLevel')->all();

    if($json) {

      $_languages = array();
      foreach ($languages as $language) {
        $_languages[] = array(
          $language->id,
          $language->name
        );
      }

      $levels = array();
      foreach ($languageSkillLevels as $level) {
        $levels[] = array(
          $level->id,
          $level->name
        );
      }

      $this->setData('languages',json_encode($_languages));
      $this->setData('levels',json_encode($levels));
    }else{

      $_languages = array();
      foreach ($languages as $language) {
        $_languages[$language->id] = $language->name;
      }

      $levels = array();
      foreach ($languageSkillLevels as $level) {
        $levels[$level->id] = $level->name;
      }

      $this->setData('languages',$_languages);
      $this->setData('levels',$levels);
    }
    
  }

  public function delete() {

    $model = Service::loadModel('PersonLanguageSkill')->find($this->param['id']);

    if($model->delete()) {
      MessageHelper::display('ข้อมูลถูกลบแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถลบข้อมูลนี้ได้','error');
    }

    return Redirect::to('experience/profile/edit');
  }

}
