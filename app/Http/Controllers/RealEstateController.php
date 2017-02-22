<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\message;
use Redirect;

class RealEstateController extends Controller
{
  public function __construct() { 
    parent::__construct();
  }

  public function listView() {

    $model = Service::loadModel('RealEstate');

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('real-estate/list');
    $model->paginator->setUrl('real-estate/detail/{id}','detailUrl');

    $this->mergeData($model->paginator->build());

    return $this->view('pages.real_estate.list');
  }

  public function detail() {

    $model = Service::loadModel('RealEstate')->find($this->param['id']);

    if(empty($model)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศนี้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    $model->modelData->loadData(array(
      'json' => array('Image')
    ));

    $this->data = $model->modelData->build();

    return $this->view('pages.real_estate.detail');

  }

  public function add() { 

    $model = Service::loadModel('RealEstate');

    $model->formHelper->loadFieldData('Province',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'provinces',
      'order' => array(
        array('top','ASC'),
        array('id','ASC')
      )
    ));

    $model->formHelper->loadFieldData('RealEstateType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'realEstateTypes'
    ));

    $model->formHelper->loadFieldData('AnnouncementType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'announcementTypes'
    ));

    $model->formHelper->loadFieldData('RealEstateFeature',array(
      'conditions' => array(
        ['real_estate_feature_type_id','=',1]
      ),
      'key' =>'id',
      'field' => 'name',
      'index' => 'feature'
    ));

    $model->formHelper->loadFieldData('RealEstateFeature',array(
      'conditions' => array(
        ['real_estate_feature_type_id','=',2]
      ),
      'key' =>'id',
      'field' => 'name',
      'index' => 'facility'
    ));

    $this->mergeData($model->formHelper->build());
    $this->mergeData(array('defaultAnnouncementType' => 2));

    return $this->view('pages.real_estate.form.real_estate_add');
  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('RealEstate');

    if($model->fill($request->all())->save()) {
      Message::display('ลงประกาศเรียบร้อยแล้ว','success');
      return Redirect::to('real-estate/detail/'.$model->id);
    }else{
      return Redirect::back();
    }
  }

  public function edit() {

    $model = Service::loadModel('RealEstate')->find($this->param['id']);

    $model->formHelper->loadFieldData('Province',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'provinces',
      'order' => array(
        array('top','ASC'),
        array('id','ASC')
      )
    ));

    $model->formHelper->loadFieldData('RealEstateType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'realEstateTypes'
    ));

    $model->formHelper->loadFieldData('AnnouncementType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'announcementTypes'
    ));

    $model->formHelper->loadFieldData('RealEstateFeature',array(
      'conditions' => array(
        ['real_estate_feature_type_id','=',1]
      ),
      'key' =>'id',
      'field' => 'name',
      'index' => 'feature'
    ));

    $model->formHelper->loadFieldData('RealEstateFeature',array(
      'conditions' => array(
        ['real_estate_feature_type_id','=',2]
      ),
      'key' =>'id',
      'field' => 'name',
      'index' => 'facility'
    ));

    $model->formHelper->loadData(array(
      'json' => array('Image','Tagging')
    ));

    $this->mergeData($model->formHelper->build());

    return $this->view('pages.real_estate.form.real_estate_edit');

  }

  public function editingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('RealEstate')->find($this->param['id']);

    if($model->fill($request->all())->save()) {
      Message::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('real-estate/detail/'.$model->id);
    }else{
      return Redirect::back();
    }
    
  }

}
