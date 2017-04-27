<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
use App\library\date;
use Redirect;

class PersonProjectController extends Controller
{
  public function add() {

    $model = Service::loadModel('PersonProject');

    $date = new Date;

    for ($i=1; $i <= 12; $i++) { 
      $month[$i] = $date->getMonthName($i);
    }

    $this->data = $model->formHelper->build();
    $this->setData('currentYear',date('Y'));
    $this->setData('month',json_encode($month));

    return $this->view('pages.person_experience.form.person_project_add');

  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('PersonProject');

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('experience/profile/edit');
    }else{
      return Redirect::back();
    }

  }

  public function edit() {

    $model = Service::loadModel('PersonProject')->find($this->param['id']);

    $date = new Date;

    for ($i=1; $i <= 12; $i++) { 
      $month[$i] = $date->getMonthName($i);
    }

    // Get Period
    $period = $model->getRelatedData('PersonExperienceDetail',
      array(
        'first' => true,
        'fields' => array('start_year','start_month','start_day','end_year','end_month','end_day','current')
      )
    );

    $model->formHelper->setFormData('period',json_encode($period->getAttributes()));
    
    $this->data = $model->formHelper->build();
    $this->setData('currentYear',date('Y'));
    $this->setData('month',json_encode($month));

    return $this->view('pages.person_experience.form.person_project_edit');

  }

  public function editingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('PersonProject')->find($this->param['id']);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('experience/profile/edit');
    }else{
      return Redirect::back();
    }
    
  }

  public function delete() {

    $model = Service::loadModel('PersonProject')->find($this->param['id']);

    if($model->delete()) {
      MessageHelper::display('ข้อมูลถูกลบแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถลบข้อมูลนี้ได้','error');
    }

    return Redirect::to('experience/profile/edit');
  }

}
