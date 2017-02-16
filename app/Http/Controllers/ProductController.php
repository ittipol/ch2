<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\message;
use Redirect;
use Session;

class ProductController extends Controller
{
  public function __construct() { 
    parent::__construct();
    $this->model = Service::loadModel('Product');
  }
// ข้อความถึงผู้ขาย:
  public function index() {
    dd('pd index');
  }

  public function detail() {
    dd('pd detail');
  }

  public function add() {

    $model = Service::loadModel('Product');

    $this->data = $model->formHelper->build();

    return $this->view('pages.product.form.product_add');
  }

  public function addingSubmit(CustomFormRequest $request) {
dd($request->all());
    if($this->model->fill($request->all())->save()) {

      $slugName = $this->model->getModelRelationData('Slug',array(
        'fields' => 'name'
      ))->name;

      Message::display('ข้อมูลถูกเพิ่มแล้ว','success');
      return Redirect::to('product/'.$slugName);
    }else{
      return Redirect::back();
    }

  }

  public function edit($productId) {

    $model = $this->model->find($productId);

    if(empty($model) || ($model->person_id != session()->get('Person.id'))) {
      $this->error = array(
        'message' => 'ขออภัย ไม่สามารถแก้ไขข้อมูลนี้ได้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    // if($model->person_id != Session::get('Person.id')) {
    //   $this->error = array(
    //     'message' => 'คุณไม่สามารถแก้ไขประกาศขายนี้ได้'
    //   );
    //   return $this->error();
    // }

    $this->formHelper->setModel($model);
    $this->formHelper->loadFormData();
    $this->formHelper->district();
    
    return $this->view('pages.product.form.edit.product');

  }

  public function editingSubmit(CustomFormRequest $request,$productId) {

    $product = $this->model->find($productId);

    if($product->fill($request->all())->save()) {

      $slugName = $product->getModelRelationData('Slug',array(
        'fields' => 'name'
      ))->name;

      Message::display('ข้อมูลถูกเพิ่มแล้ว','success');
      return Redirect::to('product/'.$slugName);
    }else{
      return Redirect::back();
    }
  }

}
