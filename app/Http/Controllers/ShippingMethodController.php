<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\message;
use App\library\url;
use Redirect;

class ShippingMethodController extends Controller
{
  public function add() {
    $model = Service::loadModel('ShippingMethod');

    $model->formHelper->loadFieldData('ShippingService',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'shippingServices',
      'order' => array(
        array('id','ASC')
      )
    ));

    $model->formHelper->loadFieldData('ShippingServiceCostType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'shippingServiceCostTypes',
      'order' => array(
        array('id','ASC')
      )
    ));

    $this->data = $model->formHelper->build();

    return $this->view('pages.shipping_method.form.shipping_method_add');
  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('ShippingMethod');

    $request->request->add(['ShopRelateTo' => array('shop_id' => request()->get('shopId'))]);

    if($model->fill($request->all())->save()) {
      Message::display('วิธีการจัดส่งสินค้าถูกเพิ่มแล้ว','success');
      return Redirect::to(route('shop.shipping_method', ['shopSlug' => request()->shopSlug]));
    }else{
      return Redirect::back();
    }
  }

  public function edit() {
    $model = Service::loadModel('ShippingMethod')->find($this->param['id']);

    $model->formHelper->loadFieldData('ShippingService',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'shippingServices',
      'order' => array(
        array('id','ASC')
      )
    ));

    $model->formHelper->loadFieldData('ShippingServiceCostType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'shippingServiceCostTypes',
      'order' => array(
        array('id','ASC')
      )
    ));

    $this->data = $model->formHelper->build();

    return $this->view('pages.shipping_method.form.shipping_method_edit');
  }

  public function editingSubmit(CustomFormRequest $request) {
    $model = Service::loadModel('ShippingMethod')->find($this->param['id']);

    if($model->fill($request->all())->save()) {
      Message::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to(route('shop.shipping_method', ['shopSlug' => request()->shopSlug]));
    }else{
      return Redirect::back();
    }
  }

  public function delete() {

    $model = Service::loadModel('ShippingMethod')->find($this->param['id']);

    if($model->delete()) {
      Message::display('ข้อมูลถูกลบแล้ว','success');
      return Redirect::to(route('shop.payment_method', ['shopSlug' => request()->shopSlug]));
    }else{
      Message::display('ไม่สามารถลบข้อมูลนี้ได้','error');
      return Redirect::to(route('shop.payment_method', ['shopSlug' => request()->shopSlug]));
    }

  }

  public function addPickingupItem() {

    $model = Service::loadModel('ShippingMethod');

    $value = array(
      'name' => 'รับสินค้าเอง', // default name
      'shipping_service_id' => 0,
      'shipping_service_cost_type_id' => 0,
      'sort' => 2,
      'ShopRelateTo' => array(
        'shop_id' => request()->get('shopId')
      )
    );

    if($model->fill($value)->save()) {
      Message::display('เพิ่มตัวเลือก "รับสินค้าเอง" แล้ว','success');
    }else{
      Message::display('เกิดข้อผิดพลาด ไม่สามารถเพิ่มตัวเลือก "รับสินค้าเอง" ได้','error');
    }

    return Redirect::to(route('shop.shipping_method', ['shopSlug' => request()->shopSlug]));

  }
  
}
