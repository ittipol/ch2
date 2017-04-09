<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
use App\library\url;
use Redirect;

class PaymentMethodController extends Controller
{
  public function detail() {

    $model = Service::loadModel('PaymentMethod')->find($this->param['id']);

    $model->modelData->loadData(array(
      'models' => array('Image'),
      'json' => array('Image')
    ));

    $this->data = $model->modelData->build();

    $shop = request()->get('shop');
    $this->setData('shop',$shop->modelData->build(true));
    $this->setData('shopImageUrl',$shop->getProfileImageUrl());
    $this->setData('shopCoverUrl',$shop->getCoverUrl());
    $this->setData('shopUrl',request()->get('shopUrl'));

    return $this->view('pages.payment_method.detail');
    
  }

  public function add() {
    $model = Service::loadModel('PaymentMethod');

    $this->data = $model->formHelper->build();

    return $this->view('pages.payment_method.form.payment_method_add');
  }

  public function addingSubmit(CustomFormRequest $request) {
    $model = Service::loadModel('PaymentMethod');

    $request->request->add(['ShopRelateTo' => array('shop_id' => request()->get('shopId'))]);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('วิธีการชำระเงินถูกเพิ่มแล้ว','success');
      return Redirect::to(route('shop.payment_method', ['shopSlug' => request()->shopSlug]));
    }else{
      return Redirect::back();
    }
  }

  public function edit() {
    $model = Service::loadModel('PaymentMethod')->find($this->param['id']);

    $model->formHelper->loadData(array(
      'json' => array('Image')
    ));

    $this->data = $model->formHelper->build();

    return $this->view('pages.payment_method.form.payment_method_edit');
  }

  public function editingSubmit(CustomFormRequest $request) {
    $model = Service::loadModel('PaymentMethod')->find($this->param['id']);

    if($model->fill($request->all())->save()) {

      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to(route('shop.payment_method', ['shopSlug' => request()->shopSlug]));
    }else{
      return Redirect::back();
    }
  }

  public function delete() {

    $model = Service::loadModel('PaymentMethod')->find($this->param['id']);

    if($model->delete()) {
      MessageHelper::display('ข้อมูลถูกลบแล้ว','success');
      return Redirect::to(route('shop.payment_method', ['shopSlug' => request()->shopSlug]));
    }else{
      MessageHelper::display('ไม่สามารถลบข้อมูลนี้ได้','error');
      return Redirect::to(route('shop.payment_method', ['shopSlug' => request()->shopSlug]));
    }

  }
}