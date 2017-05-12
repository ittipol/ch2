<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\messageHelper;
use App\library\url;
use Session;
use Redirect;

class PersonPrivateWebsiteController extends Controller
{
  public function listView() {

    $url = new Url;

    $profile = Service::loadModel('PersonExperience')
    ->select(array('id','active'))
    ->where('created_by','=',Session::get('Person.id'))
    ->first();
    
    $privateWebsites = Service::loadModel('PersonPrivateWebsite')
    ->where('person_experience_id','=',$profile->id)
    ->select('id','website_type_id','website_url')
    ->get();

    $url->setUrl('person/private_website/edit/{id}','editUrl');
    $url->setUrl('person/private_website/delete/{id}','deleteUrl');

    $_privateWebsites = array();
    foreach ($privateWebsites as $privateWebsite) {
      $_privateWebsites[] = array_merge(array(
        'website' => $privateWebsite->website_url,
        'websiteType' => $privateWebsite->websiteType->name,
      ),$url->parseUrl($privateWebsite->getAttributes())); 
    }

    $this->setData('privateWebsites',$_privateWebsites);

    return $this->view('pages.person_experience.private_website');

  }

  public function add() {

    $model = Service::loadModel('PersonPrivateWebsite');

    $websiteTypes = Service::loadModel('WebsiteType')->get();

    $_websiteTypes = array();
    foreach ($websiteTypes as $value) {
      $_websiteTypes[] = array(
        $value['id'],
        $value['name']
      );
    }

    $model->formHelper->setFormData('private_websites',json_encode(array()));
    $model->formHelper->setData('websiteTypes',json_encode($_websiteTypes));

    $this->data = $model->formHelper->build();

    return $this->view('pages.person_experience.form.pereson_private_website_add');

  }

  public function addingSubmit() {

    $model = Service::loadModel('PersonPrivateWebsite');

    $personExperience = Service::loadModel('PersonExperience')
    ->select(array('id'))
    ->where('created_by','=',session()->get('Person.id'))
    ->first();

    $hasData = false;
    foreach (request()->get('private_websites') as $value) {

      $websiteUrl = trim($value['value']);

      if(!empty($websiteUrl)) {

        $hasData = true;

        $model->newInstance()->fill(array(
          'person_experience_id' => $personExperience->id,
          'website_url' => $websiteUrl,
          'website_type_id' => $value['type'],
        ))->save();
      }
    }

    if($hasData) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
    }
    return Redirect::to('person/private_website/list');

  }

  public function edit() {

    $model = Service::loadModel('PersonPrivateWebsite')->where('id','=',$this->param['id'])->first();

    $model->formHelper->loadFieldData('WebsiteType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'websiteTypes'
    ));

    $this->data = $model->formHelper->build();

    return $this->view('pages.person_experience.form.pereson_private_website_edit');

  }

  public function editingSubmit() {

    $model = Service::loadModel('PersonPrivateWebsite')->where('id','=',$this->param['id'])->first();

    if(empty($model) || ($model->created_by != session()->get('Person.id'))) {
      $this->error = array(
        'message' => 'ขออภัย ไม่สามารถแก้ไขข้อมูลนี้ได้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    if(empty(request()->get('website_url'))) {
      return Redirect::back()->withErrors(['เว็บไซต์ห้ามว่าง']);
    }

    if($model->fill(request()->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('person/private_website/list');
    }else{
      return Redirect::back()->withErrors(['เกิดข้อผิดพลาด ไม่สามารถบันถึกได้']);
    }
    
  }

  public function delete() {

    $model = Service::loadModel('PersonPrivateWebsite')->find($this->param['id']);

    if($model->delete()) {
      MessageHelper::display('ข้อมูลถูกลบแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถลบข้อมูลนี้ได้','error');
    }

    return Redirect::to('person/private_website/list');

  }

}
