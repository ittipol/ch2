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
  // การรับประกัน
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
      return Redirect::to('shop/'.request()->shopSlug.'/product');
    }else{
      return Redirect::back();
    }

  }

  public function edit($productId) {

    $model = $this->model->find($productId);

    $this->formHelper->setModel($model);
    $this->formHelper->loadFormData();
    $this->formHelper->district();
    
    return $this->view('pages.product.form.edit.product');

  }

  public function editingSubmit(CustomFormRequest $request,$productId) {

    $product = $this->model->find($productId);

    if($product->fill($request->all())->save()) {
      Message::display('ข้อมูลถูกเพิ่มแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/product');
    }else{
      return Redirect::back();
    }
  }

}
