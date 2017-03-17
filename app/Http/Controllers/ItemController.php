<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\message;
use Redirect;

class ItemController extends Controller
{
  public function __construct() { 
    parent::__construct();
  }

  public function listView() {

    $model = Service::loadModel('Item');
    
    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    // $model->paginator->criteria(array(
    //   'fields' => array('items.*')
    // ));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('item/list');
    $model->paginator->setUrl('item/detail/{id}','detailUrl');

    $this->data = $model->paginator->build();

    return $this->view('pages.item.list');
  }

  public function detail() {

    $model = Service::loadModel('Item')->find($this->param['id']);

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

    return $this->view('pages.item.detail');

  }

  public function add() {

    $model = Service::loadModel('Item');

    $model->formHelper->loadFieldData('Province',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'provinces',
      'order' => array(
        array('top','ASC'),
        array('id','ASC')
      )
    ));

    $model->formHelper->loadFieldData('ItemCategory',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'itemCategories'
    ));

    $model->formHelper->loadFieldData('AnnouncementType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'announcementTypes'
    ));

    $this->data = $model->formHelper->build();
    $this->setData('defaultAnnouncementType',2);

    return $this->view('pages.item.form.item_add');
  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Item');

    if($model->fill($request->all())->save()) {
      Message::display('ลงประกาศเรียบร้อยแล้ว','success');
      return Redirect::to('item/detail/'.$model->id);
    }else{
      return Redirect::back();
    }

  }

  public function edit() {

    $model = Service::loadModel('Item')->find($this->param['id']);

    $model->formHelper->loadFieldData('Province',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'provinces',
      'order' => array(
        array('top','ASC'),
        array('id','ASC')
      )
    ));

    $model->formHelper->loadFieldData('ItemCategory',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'itemCategories'
    ));

    $model->formHelper->loadFieldData('AnnouncementType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'announcementTypes'
    ));

    $model->formHelper->loadData(array(
      'json' => array('Image','Tagging')
    ));

    $model->formHelper->setFormData('ItemToCategory',array(
      'item_category_id' => Service::loadModel('ItemToCategory')->where('item_id','=',$this->param['id'])->first()->item_category_id
    ));

    $this->data = $model->formHelper->build();

    return $this->view('pages.item.form.item_edit');

  }

  public function editingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Item')->find($this->param['id']);

    if($model->fill($request->all())->save()) {
      Message::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('item/detail/'.$model->id);
    }else{
      return Redirect::back();
    }
    
  }

  public function delete() {

    $model = Service::loadModel('Item')->find($this->param['id']);

    if(empty($model)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศนี้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    if($model->delete()) {
      Message::display('ข้อมูลถูกลบแล้ว','success');
      return Redirect::to('account/item');
    }else{
      Message::display('ไม่สามารถลบข้อมูลนี้ได้','error');
      return Redirect::to('account/item');
    }

  }

}