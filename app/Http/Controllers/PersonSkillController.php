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
    ->where('created_by','=',session()->get('Person.id'))
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

    $this->data = $model->formHelper->build();

    return $this->view('pages.person_experience.form.pereson_skill_edit');

  }

  public function editingSubmit() {

    $model = Service::loadModel('PersonSkill')->where('id','=',$this->param['id'])->first();

    if($model->fill(request()->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('experience/profile/edit');
    }else{
      return Redirect::back();
    }
    
  }

  public function delete() {

    $model = Service::loadModel('PersonSkill')->find($this->param['id']);

    if($model->delete()) {
      MessageHelper::display('ข้อมูลถูกลบแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถลบข้อมูลนี้ได้','error');
    }

    return Redirect::to('experience/profile/edit');
  }

}
