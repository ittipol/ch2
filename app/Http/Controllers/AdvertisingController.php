<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\message;
use App\library\url;
use Redirect;

class AdvertisingController extends Controller
{
  public function __construct() { 
    parent::__construct();
  }

  // public function listView() {

  //   $model = Service::loadModel('Advertising');
    
  //   $page = 1;
  //   if(!empty($this->query['page'])) {
  //     $page = $this->query['page'];
  //   }

  //   $model->paginator->setPage($page);
  //   $model->paginator->setPagingUrl('advertising/list');
  //   $model->paginator->setUrl('advertising/detail/{id}','detailUrl');

  //   $this->data = $model->paginator->build();

  //   return $this->view('pages.dvertising.list');
  // }

  public function detail() {

    $model = Service::loadModel('Advertising')->find($this->param['id']);

    if(empty($model)) {
      $this->error = array(
        'message' => 'ไม่พบประกาศนี้'
      );
      return $this->error();
    }

    $model->modelData->loadData(array(
      'models' => array('Image','Tagging'),
      'json' => array('Image')
    ));

    $this->mergeData($model->modelData->build());

    // Get Shop Address
    $shop = $model->getModelRelationData('ShopRelateTo',array(
      'first' => true,
    ))->shop;

    // Get Slug
    $slug = $shop->getModelRelationData('Slug',array(
      'first' => true,
    ))->slug;

    // Get Branches
    $branchIds = $model->getModelRelationData('RelateToBranch',array(
      'list' => 'branch_id',
      'fields' => array('branch_id'),
    ));

    $branches = array();
    if(!empty($branchIds)){
      $branches = Service::loadModel('Branch')
      ->select(array('id','name'))
      ->whereIn('id',$branchIds)
      ->get();
    }
    
    $url = new Url;

    $branchLocations = array();
    $hasBranchLocation = false;
    foreach ($branches as $branch) {

      $address = $branch->modelData->loadAddress();

      if(!empty($address)){

        $hasBranchLocation = true;

        $graphics = json_decode($address['_geographic'],true);
        
        $branchLocations[] = array(
          'id' => $branch->id,
          'address' => $branch->name,
          'latitude' => $graphics['latitude'],
          'longitude' => $graphics['longitude'],
          'detailUrl' => $url->setAndParseUrl('branch/detail/{id}',$branch->getAttributes())
        );
      }
    }

    $this->setData('shopName',$shop->name);
    // $this->setData('shopAddress',$shop->modelData->loadAddress());
    $this->setData('branchLocations',json_encode($branchLocations));
    $this->setData('hasBranchLocation',$hasBranchLocation);

    // Get person apply job
    $personApplyJob = Service::loadModel('PersonApplyJob')->where(array(
      array('person_id','=',session()->get('Person.id')),
      array('job_id','=',$this->param['id'])
    ))->exists();

    $this->setData('personApplyJob',$personApplyJob);

    if(!$personApplyJob) {
      $this->setData('jobApplyUrl',$url->setAndParseUrl('job/apply/{id}',array('id' => $this->param['id'])));
    }

    return $this->view('pages.advertising.detail');

  }

  public function add() {

    $model = Service::loadModel('Advertising');

    $model->formHelper->loadFieldData('AdvertisingType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'advertisingTypes'
    ));

    $model->formHelper->setData('branches',request()->get('shop')->getRelatedShopData('Branch'));

    $this->data = $model->formHelper->build();

    return $this->view('pages.advertising.form.advertising_add');
  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Advertising');

    $request->request->add(['ShopRelateTo' => array('shop_id' => request()->get('shop')->id)]);

    if($model->fill($request->all())->save()) {
      Message::display('ลงประกาศแล้ว','success');
      return Redirect::to('shop/'.$request->shopSlug.'/advertising');
    }else{
      return Redirect::back();
    }

  }

  public function edit() {

    $model = Service::loadModel('Advertising')->find($this->param['id']);

    if(empty($model)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่สามารถแก้ไขข้อมูลนี้ได้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    $model->formHelper->loadData(array(
      'models' => array('Image','Tagging'),
      'json' => array('Image','Tagging')
    ));

    $model->formHelper->loadFieldData('AdvertisingType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'advertisingTypes'
    ));

    $relateToBranch = $model->getModelRelationData('RelateToBranch',array(
      'fields' => array('branch_id')
    ));

    $branches = array();
    if(!empty($relateToBranch)) {
      foreach ($relateToBranch as $value) {
        $branches['branch_id'][] = $value->branch->id;
      }
    }

    $model->formHelper->setFormData('RelateToBranch',$branches);

    $model->formHelper->setData('branches',request()->get('shop')->getRelatedShopData('Branch'));

    $this->data = $model->formHelper->build();

    return $this->view('pages.advertising.form.advertising_edit');
  }

  public function editingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Advertising')->find($this->param['id']);

    if(empty($model)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่สามารถแก้ไขข้อมูลนี้ได้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    if($model->fill($request->all())->save()) {
      Message::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/advertising');
    }else{
      return Redirect::back();
    }

  }

}
