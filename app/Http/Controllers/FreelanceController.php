<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
use App\library\url;
use Redirect;
use Session;

class FreelanceController extends Controller
{
  public function index() {

  }

  public function detail() {

    $url = new Url;

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

    $person = Service::loadModel('Person')->find(Session::get('Person.id'));

    $person->modelData->loadData(array(
      'models' => array('Address','Contact')
    ));

    $this->data = $model->modelData->build();
    $this->setData('profile',$person->modelData->build(true));
    $this->setData('profileImageUrl',$person->getProfileImageUrl());
    $this->setData('experienceDetailUrl',$url->setAndParseUrl('experience/profile/{id}',array('id' => $person->personExperience->id)));

    return $this->view('pages.freelance.detail');

  }

  public function manage() {

    $model = Service::loadModel('Freelance');

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $model->paginator->myData();
    $model->paginator->criteria(array(
      'order' => array(
        array('created_at','DESC')
      )
    ));
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
      MessageHelper::display('ลงประกาศแล้ว','success');
      return Redirect::to('person/freelance');
    }else{
      return Redirect::back();
    }

  }

  public function edit() {

    $model = Service::loadModel('Freelance')->find($this->param['id']);

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
    
    if($model->fill($request->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('person/freelance');
    }else{
      return Redirect::back();
    }

  }

}
