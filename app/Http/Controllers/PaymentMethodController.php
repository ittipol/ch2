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

    switch ($this->param['type']) {
      case 'bank-transfer':
          $page = 'bank_transfer_add';
        break;
      
      case 'promptpay':
          $page = 'promptpay_add';
        break;

      case 'paypal':
          $page = 'paypal_add';
        break;

      default:
          return Redirect::to(route('shop.payment_method', ['shopSlug' => request()->shopSlug]));
        break;

    }

    // Get Payment method type
    $paymentMethodType = Service::loadModel('PaymentMethodType')->getByAlias($this->param['type']);
    $serviceProviders = $paymentMethodType->getServiceProvider();

    $_serviceProviders = array();
    if(!empty($serviceProviders)) {
      foreach ($serviceProviders as $serviceProvider) {
        $_serviceProviders[$serviceProvider->id] = $serviceProvider->name;
      }
    }

    $this->setData('serviceProviders',$_serviceProviders);

    return $this->view('pages.payment_method.form.'.$page);
  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('PaymentMethod');

    // Checking error
    $errorMessages = $this->checkFormError($this->param['type'],$request->all());

    if($errorMessages === false) {
      return Redirect::to(route('shop.payment_method', ['shopSlug' => request()->shopSlug]));
    }elseif(!empty($errorMessages)) {
      return Redirect::back()->withErrors([$errorMessages]);
    }

    $model->buildPaymentMethodName($this->param['type'],$request->all());

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

    $paymentMethodType = Service::loadModel('PaymentMethodType')->find($model->payment_method_type_id);

    switch ($paymentMethodType->alias) {
      case 'bank-transfer':
          $page = 'bank_transfer_edit';
        break;
      
      case 'promptpay':
          $page = 'promptpay_edit';
        break;

      case 'paypal':
          $page = 'paypal_edit';
        break;

      default:
          return Redirect::to(route('shop.payment_method', ['shopSlug' => request()->shopSlug]));
        break;

    }

    // Get Payment method type
    $serviceProviders = $paymentMethodType->getServiceProvider();

    $_serviceProviders = array();
    if(!empty($serviceProviders)) {
      foreach ($serviceProviders as $serviceProvider) {
        $_serviceProviders[$serviceProvider->id] = $serviceProvider->name;
      }
    }

    $this->setData('serviceProviders',$_serviceProviders);

    return $this->view('pages.payment_method.form.'.$page);
  }

  public function editingSubmit(CustomFormRequest $request) {
    $model = Service::loadModel('PaymentMethod')->find($this->param['id']);

    $paymentMethodType = Service::loadModel('PaymentMethodType')->find($model->payment_method_type_id);

    // Checking error
    $errorMessages = $this->checkFormError($paymentMethodType->alias,$request->all());

    if($errorMessages === false) {
      return Redirect::to(route('shop.payment_method', ['shopSlug' => request()->shopSlug]));
    }elseif(!empty($errorMessages)) {
      return Redirect::back()->withErrors([$errorMessages]);
    }

    $model->buildPaymentMethodName($paymentMethodType->alias,$request->all());

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

  private function checkFormError($paymentMethodType,$value) {

    $errorMessages = array();

    switch ($paymentMethodType) {
      case 'bank-transfer':

          if(empty($value['branch_name'])) {
            $errorMessages[] = 'ชื่อสาขาห้ามว่าง';
          }

          if(empty($value['account_number'])) {
            $errorMessages[] = 'เลขที่บัญชีห้ามว่าง';
          }

        break;
      
      case 'promptpay':

        break;

      case 'paypal':

        break;

      default:
          // return Redirect::to(route('shop.payment_method', ['shopSlug' => request()->shopSlug]));
          return false;
        break;

    }

    $paymentMethodType = Service::loadModel('PaymentMethodType')->getByAlias($paymentMethodType);

    if($paymentMethodType->hasPaymentServiceProvider()) {

      if(empty($value['payment_service_provider_id'])) {
        $errorMessages[] = 'ผู้ให้บริการห้ามว่าง';
      }else{
        $exist = Service::loadModel('PaymentServiceProviderToPaymentMethodType')
        ->where([
          ['payment_service_provider_id','=',$value['payment_service_provider_id']],
          ['payment_method_type_id','=',$paymentMethodType->id]
        ])->exists();

        if(!$exist) {
          $errorMessages[] = 'ผู้ให้บริการไม่ถูกต้อง';
        }

      }

    }

    return $errorMessages;

  }


}