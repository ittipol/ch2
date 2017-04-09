<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\messageHelper;
use Redirect;

class PersonSkillController extends Controller
{
  public function add() {

    $model = Service::loadModel('PersonSkill');

    $this->data = $model->formHelper->build();

    return $this->view('pages.person_experience.form.pereson_skill_add');

  }

  public function addingSubmit() {

    $model = Service::loadModel('PersonSkill');

    $personExperience = Service::loadModel('PersonExperience')
    ->select(array('id'))
    ->where('person_id','=',session()->get('Person.id'))
    ->first();

    foreach (request()->get('skills') as $value) {

      $value = trim($value['value']);

      if(!empty($value) && !$model->checkExistBySkill($value)) {
        $model->newInstance()->fill(array(
          'person_experience_id' => $personExperience->id,
          'skill' => $value
        ))->save();
      }
    }

    MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
    return Redirect::to('experience/profile/edit');

  }

  public function edit() {

    $model = Service::loadModel('PersonSkill')->where('id','=',$this->param['id'])->first();

    if(empty($model) || ($model->person_id != session()->get('Person.id'))) {
      $this->error = array(
        'message' => 'ขออภัย ไม่สามารถแก้ไขข้อมูลนี้ได้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    $this->data = $model->formHelper->build();

    return $this->view('pages.person_experience.form.pereson_skill_edit');

  }

  public function editingSubmit() {

    $model = Service::loadModel('PersonSkill')->where('id','=',$this->param['id'])->first();

    if(empty($model) || ($model->person_id != session()->get('Person.id'))) {
      $this->error = array(
        'message' => 'ขออภัย ไม่สามารถแก้ไขข้อมูลนี้ได้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    if($model->fill(request()->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('experience/profile/edit');
    }else{
      return Redirect::back();
    }
    
  }

}
