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

    $this->setPageTitle('เพิ่มตัวเลือกสินค้า');

    return $this->view('pages.product_option.form.product_option_add');

  }

  public function addingSubmit(CustomFormRequest $request) {
    
    $model = Service::loadModel('ProductOption');

    $request->request->add(['product_id' => $this->param['product_id']]);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ตัวเลือกสินค้าถูกเพิ่มแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/product/option/'.$this->param['product_id']);
    }else{
      return Redirect::back();
    }

  }

  public function edit() {

    $model = Service::loadModel('ProductOption')->find($this->param['id']);

    $model->formHelper->loadData(array(
      'json' => array('Image')
    ));

    $this->data = $model->formHelper->build();

    $this->setPageTitle('แก้ไขตัวเลือกสินค้า');

    return $this->view('pages.product_option.form.product_option_edit');

  }
}
