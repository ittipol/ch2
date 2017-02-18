<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\message;
use App\library\url;
use Redirect;

class FreelanceController extends Controller
{
  public function index() {

  }

  public function detail() {

    $model = Service::loadModel('Freelance')->find($this->param['id']);

    if(empty($model)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศนี้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    $model->modelData->loadData(array(
      'models' => array('Image','Tagging'),
      'json' => array('Image')
    ));

    $this->data = $model->modelData->build();

    return $this->view('pages.freelance.detail');

  }

  public function manage() {

    $model = Service::loadModel('Freelance');

    $page = 1;
    if(!empty($this->query)) {
      $page = $this->query['page'];
    }

    $model->paginator->myData();
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('freelance/list');
    $model->paginator->setUrl('freelance/detail/{id}','detailUrl');
    $model->paginator->setUrl('person/freelance_edit/{id}','editUrl');
    $model->paginator->setUrl('person/freelance_delete/{id}','deleteUrl');

    $this->data = $model->paginator->build();

    return $this->view('pages.freelance.manage');

  }

  public function add() {

    $model = Service::loadModel('Freelance');

    $model->formHelper->loadFieldData('FreelanceType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'freelanceTypes'
    ));

    $this->data = $model->formHelper->build();

    return $this->view('pages.freelance.form.freelance_add');

  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Freelance');

    if($model->fill($request->all())->save()) {
      Message::display('ลงประกาศแล้ว','success');
      return Redirect::to('person/freelance');
    }else{
      return Redirect::back();
    }

  }

  public function edit() {

    $model = Service::loadModel('Freelance')->find($this->param['id']);

    if(empty($model) || ($model->person_id != session()->get('Person.id'))) {
      $this->error = array(
        'message' => 'ขออภัย ไม่สามารถแก้ไขข้อมูลนี้ได้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    $model->formHelper->loadData(array(
      'models' => array('Image','Tagging'),
      'json' => array('Image','Tagging')
    ));

    $model->formHelper->loadFieldData('FreelanceType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'freelanceTypes'
    ));

    $this->data = $model->formHelper->build();

    return $this->view('pages.freelance.form.freelance_edit');
  }

  public function editingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Freelance')->find($this->param['id']);

    if(empty($model) || ($model->person_id != session()->get('Person.id'))) {
      $this->error = array(
        'message' => 'ขออภัย ไม่สามารถแก้ไขข้อมูลนี้ได้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    if($model->fill($request->all())->save()) {
      Message::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('person/freelance');
    }else{
      return Redirect::back();
    }

  }

}
