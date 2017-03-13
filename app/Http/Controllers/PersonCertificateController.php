<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\message;
use App\library\date;
use Redirect;

class PersonCertificateController extends Controller
{
  public function add() {

    $model = Service::loadModel('PersonCertificate');

    $date = new Date;

    for ($i=1; $i <= 12; $i++) { 
      $month[$i] = $date->getMonthName($i);
    }

    $this->data = $model->formHelper->build();
    $this->setData('currentYear',date('Y'));
    $this->setData('month',json_encode($month));

    return $this->view('pages.person_experience.form.person_certificate_add');

  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('PersonCertificate');

    if($model->fill($request->all())->save()) {
      Message::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('experience/profile/edit');
    }else{
      return Redirect::back();
    }

  }

  public function edit() {

    $model = Service::loadModel('PersonCertificate')->find($this->param['id']);

    if(empty($model) || ($model->person_id != session()->get('Person.id'))) {
      $this->error = array(
        'message' => 'ขออภัย ไม่สามารถแก้ไขข้อมูลนี้ได้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    $date = new Date;

    for ($i=1; $i <= 12; $i++) { 
      $month[$i] = $date->getMonthName($i);
    }

    // Get Period
    $period = $model->getRelatedModelData('PersonExperienceDetail',
      array(
        'first' => true,
        'fields' => array('start_year','start_month','start_day','end_year','end_month','end_day','current')
      )
    );

    $model->formHelper->setFormData('period',json_encode($period->getAttributes()));

    $model->formHelper->loadData(array(
      'models' => array('Image'),
      'json' => array('Image')
    ));

    $this->data = $model->formHelper->build();
    $this->setData('currentYear',date('Y'));
    $this->setData('month',json_encode($month));

    return $this->view('pages.person_experience.form.person_certificate_edit');

  }

  public function editingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('PersonCertificate')->find($this->param['id']);

    if(empty($model) || ($model->person_id != session()->get('Person.id'))) {
      $this->error = array(
        'message' => 'ขออภัย ไม่สามารถแก้ไขข้อมูลนี้ได้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    if($model->fill($request->all())->save()) {
      Message::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('experience/profile/edit');
    }else{
      return Redirect::back();
    }
    
  }

}
