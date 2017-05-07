<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
use Redirect;

class ProductOptionController extends Controller
{

  public function add() {

    $model = Service::loadModel('ProductOption');

    $this->data = $model->formHelper->build();

    $this->setPageTitle('เพิ่มหัวข้อตัวเลือกสินค้า');

    return $this->view('pages.product_option.form.product_option_add');

  }

  public function addingSubmit(CustomFormRequest $request) {
    
    $model = Service::loadModel('ProductOption');

    $request->request->add(['product_id' => $this->param['product_id']]);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('หัวข้อตัวเลือกสินค้าถูกเพิ่มแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/product/option/'.$this->param['product_id']);
    }else{
      return Redirect::back();
    }

  }

  public function edit() {

    $model = Service::loadModel('ProductOption')->find($this->param['id']);

    $this->data = $model->formHelper->build();

    $this->setPageTitle('แก้ไขหัวข้อตัวเลือกสินค้า');

    return $this->view('pages.product_option.form.product_option_edit');

  }

  public function editingSubmit(CustomFormRequest $request) {
    
    $model = Service::loadModel('ProductOption')->find($this->param['id']);

    $request->request->add(['product_id' => $this->param['product_id']]);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/product/option/'.$this->param['product_id']);
    }else{
      return Redirect::back();
    }

  }

  public function delete() {

    $model = Service::loadModel('ProductOption')->find($this->param['id']);

    if($model->delete()) {

      // delete product in cart
      // Service::loadModel('Cart')
      // ->where('product_option_id','=',$this->param['id'])
      // ->delete();

      MessageHelper::display('ข้อมูลถูกลบแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถลบข้อมูลได้','error');
    }

    return Redirect::to('shop/'.request()->shopSlug.'/product/option/'.$this->param['product_id']);

  }

  public function optionValueAdd() {

    $model = Service::loadModel('ProductOptionValue');

    $this->data = $model->formHelper->build();

    $this->setPageTitle('เพิ่มตัวเลือกสินค้า');

    return $this->view('pages.product_option.form.product_option_value_add');

  }

  public function optionValueAddingSubmit(CustomFormRequest $request) {
    
    $model = Service::loadModel('ProductOptionValue');

    $request->request->add(['product_option_id' => $this->param['product_option_id']]);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ตัวเลือกสินค้าถูกเพิ่มแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/product/option/'.$this->param['product_id']);
    }else{
      return Redirect::back();
    }

  }

  public function optionValueEdit() {

    $model = Service::loadModel('ProductOptionValue')->find($this->param['id']);

    $model->formHelper->loadData(array(
      'json' => array('Image')
    ));

    $this->data = $model->formHelper->build();

    $this->setPageTitle('แก้ไขตัวเลือกสินค้า');

    return $this->view('pages.product_option.form.product_option_value_edit');

  }

  public function optionValueEditingSubmit(CustomFormRequest $request) {
    
    $model = Service::loadModel('ProductOptionValue')->find($this->param['id']);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/product/option/'.$this->param['product_id']);
    }else{
      return Redirect::back();
    }

  }

  public function optionValueDelete() {

    $model = Service::loadModel('ProductOptionValue')->find($this->param['id']);

    if($model->delete()) {

      // delete product in cart
      // Service::loadModel('Cart')
      // ->where('product_option_id','=',$this->param['id'])
      // ->delete();

      MessageHelper::display('ข้อมูลถูกลบแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถลบข้อมูลได้','error');
    }

    return Redirect::to('shop/'.request()->shopSlug.'/product/option/'.$this->param['product_id']);

  }
  
}
