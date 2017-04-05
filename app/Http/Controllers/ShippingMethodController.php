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

    if(!$model->special) {
      $this->data = $model->formHelper->build();
      return $this->view('pages.shipping_method.form.shipping_method_edit');
    }

    // Special
    if($model->special_shipping_method_id == Service::loadModel('SpecialShippingMethod')->getIdByalias('picking-up-product')) {

      $relateToBranch = $model->getRelatedData('RelateToBranch',array(
        'fields' => array('branch_id')
      ));

      $branches = array();
      if(!empty($relateToBranch)) {
        foreach ($relateToBranch as $value) {
          $branches['branch_id'][] = $value->branch->id;
        }
      }
      // Get Selected Branch
      $model->formHelper->setFormData('RelateToBranch',$branches);

      $model->formHelper->setData('branches',request()->get('shop')->getRelatedShopData('Branch'));

    }

    $this->data = $model->formHelper->build();
    $this->setData('shippingMethod',$model->modelData->build(true));

    return $this->view('pages.shipping_method.form.special_shipping_method_edit');

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

  public function pickingUpItem() {

    $model = Service::loadModel('ShippingMethod');
    $specialShippingMethod = Service::loadModel('SpecialShippingMethod')->getByAlias('picking-up-product');

    // check if exist
    if(!$model->hasSpecialShippingMethod($specialShippingMethod->id,request()->get('shopId'))) {

      $value = array(
        'name' => 'รับสินค้าเอง', // default name
        'shipping_service_id' => $specialShippingMethod->shipping_service_id,
        'shipping_service_cost_type_id' => $specialShippingMethod->shipping_service_cost_type_id,
        'special' => 1,
        'special_shipping_method_id' => $specialShippingMethod->id,
        'sort' => $specialShippingMethod->sort,
        'ShopRelateTo' => array(
          'shop_id' => request()->get('shopId')
        )
      );

      if($model->fill($value)->save()) {
        Message::display('เพิ่มตัวเลือก "รับสินค้าเอง" แล้ว','success');
      }else{
        Message::display('เกิดข้อผิดพลาด ไม่สามารถเพิ่มตัวเลือก "รับสินค้าเอง" ได้','error');
      }

    }else{
      Message::display('ตัวเลือก "รับสินค้าเอง" ถูกเพิ่มแล้ว','error');
    }

    return Redirect::to(route('shop.shipping_method', ['shopSlug' => request()->shopSlug]));

  }

}
