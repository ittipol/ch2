<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\url;
use App\library\messageHelper;
use App\library\filterHelper;
use Redirect;

class ShopController extends Controller
{

  public function __construct() { 
    parent::__construct();
  }

  public function listView() {

    $model = Service::loadModel('Shop');
    $filterHelper = new FilterHelper($model);

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $filters = '';
    if(!empty($this->query['fq'])) {
      $filters = $this->query['fq'];
    }

    $sort = '';
    if(!empty($this->query['sort'])) {
      $sort = $this->query['sort'];
    }

    $filterHelper->setFilters($filters);
    $filterHelper->setSorting($sort);

    $conditions = $filterHelper->buildFilters();
    $order = $filterHelper->buildSorting();

    $model->paginator->criteria(array_merge(array(
      'conditions' => $conditions
    ),$order));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('community/shop');
    $model->paginator->setUrl('shop/{slug}','shopUrl');
    $model->paginator->setQuery('sort',$sort);
    $model->paginator->setQuery('fq',$filters);
    $model->paginator->disableGetImage();

    $searchOptions = array(
      'filters' => $filterHelper->getFilterOptions(),
      'sort' => $filterHelper->getSortingOptions()
    );

    $displayingFilters = array(
      'filters' => $filterHelper->getDisplayingFilterOptions(),
      'sort' => $filterHelper->getDisplayingSorting()
    );

    $this->data = $model->paginator->build();
    $this->setData('searchOptions',$searchOptions);
    $this->setData('displayingFilters',$displayingFilters);

    return $this->view('pages.shop.list');

  }

  public function manage() {

    $model = request()->get('shop');

    $model->modelData->loadData();

    $this->data = $model->modelData->build();

    // $product = Service::loadModel('Product')
    // ->join('shop_relate_to', 'shop_relate_to.model_id', '=', 'products.id')
    // ->where('shop_relate_to.model','like','Product')
    // ->where('shop_relate_to.shop_id','=',request()->get('shopId'))
    // ->select('products.*')
    // ->get();

    $totalProduct = Service::loadModel('ShopRelateTo')
    ->where([
      ['shop_id','=',request()->get('shopId')],
      ['model','like','Product']
    ])->count();

    $product = Service::loadModel('Product');
    $product->paginator->setPerPage(5);
    $product->paginator->criteria(array(
      'order' => array('created_at','DESC')
    ));
    $product->paginator->setUrl('product/detail/{id}','detailUrl');

    $totalJob = Service::loadModel('ShopRelateTo')
    ->where([
      ['shop_id','=',request()->get('shopId')],
      ['model','like','Job']
    ])->count();

    $job = Service::loadModel('Job');
    $job->paginator->setPerPage(5);
    $job->paginator->criteria(array(
      'order' => array('created_at','DESC')
    ));
    $job->paginator->setUrl('job/detail/{id}','detailUrl');

    $totalAdvertising = Service::loadModel('ShopRelateTo')
    ->where([
      ['shop_id','=',request()->get('shopId')],
      ['model','like','Advertising']
    ])->count();

    $advertising = Service::loadModel('Advertising');
    $advertising->paginator->setPerPage(3);
    $advertising->paginator->criteria(array(
      'order' => array('created_at','DESC')
    ));
    $advertising->paginator->setUrl('advertising/detail/{id}','detailUrl');

    $item = Service::loadModel('Item');
    $item->paginator->setPerPage(3);
    $item->paginator->criteria(array(
      'order' => array('created_at','DESC')
    ));
    $item->paginator->setUrl('item/detail/{id}','detailUrl');

    $this->setData('totalProduct',$totalProduct);
    $this->setData('totalJob',$totalJob);
    $this->setData('totalAdvertising',$totalAdvertising);

    $this->setData('products',$product->paginator->getPaginationData());
    $this->setData('jobs',$job->paginator->getPaginationData());
    $this->setData('advertisings',$advertising->paginator->getPaginationData());

    $this->setData('productPostUrl',request()->get('shopUrl').'product_post');
    $this->setData('jobPostUrl',request()->get('shopUrl').'job/post');
    $this->setData('productPostUrl',request()->get('shopUrl').'shop_ad_post');

    $this->setData('settingUrl',request()->get('shopUrl').'setting');
    // $this->setData('productUrl',request()->get('shopUrl').'product');
    // $this->setData('jobUrl',request()->get('shopUrl').'job');
    // $this->setData('advertisingUrl',request()->get('shopUrl').'advertising');

    return $this->view('pages.shop.manage');
  }

  public function product() {

    $url = new Url;
    
    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $model = Service::loadModel('Product');
    $model->paginator->criteria(array(
      'joins' => array('shop_relate_to', 'shop_relate_to.model_id', '=', $model->getTable().'.id'),
      'conditions' => array(
        array('shop_relate_to.model','like',$model->modelName),
        array('shop_relate_to.shop_id','=',request()->get('shopId'))
      ),
      'order' => array('id','DESC')
    ));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('shop/'.request()->shopSlug.'/product');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/product/manage/{id}','menuUrl');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/product/delete/{id}','deleteUrl');
    $model->paginator->setUrl('product/detail/{id}','detailUrl');

    $this->data = $model->paginator->build();

    $this->setData('countOrder',Service::loadModel('Order')->where([
      ['shop_id','=',request()->get('shopId')],
      ['order_status_id','=',1]
    ])->count());

    $this->setData('productPostUrl',request()->get('shopUrl').'product_post');
    $this->setData('orderUrl',request()->get('shopUrl').'order');
    $this->setData('paymentMethodUrl',request()->get('shopUrl').'payment_method');
    $this->setData('shippingMethodUrl',request()->get('shopUrl').'shipping_method');

    return $this->view('pages.shop.product');
  }

  public function paymentMethod() {

    $model = Service::loadModel('PaymentMethod');

    $url = new Url;
    
    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $model->paginator->criteria(array(
      'joins' => array('shop_relate_to', 'shop_relate_to.model_id', '=', $model->getTable().'.id'),
      'conditions' => array(
        array('shop_relate_to.model','like',$model->modelName),
        array('shop_relate_to.shop_id','=',request()->get('shopId'))
      ),
      'order' => array('id','DESC')
    ));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('shop/'.request()->shopSlug.'/payment_method');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/payment_method/{id}','detailUrl');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/payment_method/edit/{id}','editUrl');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/payment_method/delete/{id}','deleteUrl');

    $this->data = $model->paginator->build();

    $this->setData('paymentMethodAddUrl',request()->get('shopUrl').'payment_method/add');

    return $this->view('pages.shop.payment_method');
  }

  public function shippingMethod() {
    
    $model = Service::loadModel('ShippingMethod');

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $model->paginator->criteria(array(
      'joins' => array('shop_relate_to', 'shop_relate_to.model_id', '=', $model->getTable().'.id'),
      'conditions' => array(
        array('shop_relate_to.model','like',$model->modelName),
        array('shop_relate_to.shop_id','=',request()->get('shopId')),
        array($model->getTable().'.special','=',0)
      ),
      'order' => array($model->getTable().'.sort','ASC')
    ));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('shop/'.request()->shopSlug.'/shipping_method');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/shipping_method/{id}','detailUrl');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/shipping_method/edit/{id}','editUrl');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/shipping_method/delete/{id}','deleteUrl');

    $this->data = $model->paginator->build();

    $pickingUpItem = $model->getSpecificSpecialShippingMethods(Service::loadModel('SpecialShippingMethod')->getIdByalias('picking-up-product'),request()->get('shopId'),true);

    $this->setData('pickingUpItem',$pickingUpItem);
    if(!empty($pickingUpItem)) {
      $this->setData('pickingUpItemEditUrl',request()->get('shopUrl').'shipping_method/edit/'.$pickingUpItem['id']);
      $this->setData('pickingUpItemDeleteUrl',request()->get('shopUrl').'shipping_method/delete/'.$pickingUpItem['id']);
    }

    $this->setData('shippingMethodAddUrl',request()->get('shopUrl').'shipping_method/add');
    $this->setData('addPickingupItemUrl',request()->get('shopUrl').'pickingup_item');

    return $this->view('pages.shop.shipping_method');

  }

  public function job() {

    $url = new Url;

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $shopTos = Service::loadModel('ShopRelateTo')
    ->select('model_id')
    ->where(array(
      array('model','like','Job'),
      array('shop_id','=',request()->get('shopId'))
    ));

    if($shopTos->exists()) {

      $jobModel = Service::loadModel('Job');
      $jobModel->paginator->criteria(array(
        'conditions' => array(
          'in' => array(
            array('id',Service::getList($shopTos->get(),'model_id'))
          )
        ),
        'order' => array('id','DESC')
      ));
      $jobModel->paginator->setPage($page);
      $jobModel->paginator->setPagingUrl('shop/'.request()->shopSlug.'/job');
      $jobModel->paginator->setUrl('shop/'.$this->param['shopSlug'].'/job/edit/{id}','editUrl');
      $jobModel->paginator->setUrl('shop/'.$this->param['shopSlug'].'/job/delete/{id}','deleteUrl');
      $jobModel->paginator->setUrl('job/detail/{id}','detailUrl');

      $this->data = $jobModel->paginator->build();
    }

    $this->setData('countJobApplying',Service::loadModel('PersonApplyJob')
    ->where('shop_id','=',request()->get('shopId'))
    ->whereIn('job_applying_status_id',array(1,2))
    ->count());
    
    $this->setData('jobPostUrl',request()->get('shopUrl').'job/post');
    $this->setData('jobApplyListUrl',request()->get('shopUrl').'job_applying');
    $this->setData('branchManageUrl',request()->get('shopUrl').'branch/manage');

    return $this->view('pages.shop.job');
  }

  public function branch() {

    $url = new Url;

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $shopTos = Service::loadModel('ShopRelateTo')
    ->select('model_id')
    ->where(array(
      array('model','like','branch'),
      array('shop_id','=',request()->get('shopId'))
    ));

    if($shopTos->exists()) {
      $branch = Service::loadModel('Branch');
      $branch->paginator->criteria(array(
        'conditions' => array(
          'in' => array(
            array('id',Service::getList($shopTos->get(),'model_id'))
          )
        ),
        'order' => array('id','DESC')
      ));
      $branch->paginator->setPage($page);
      $branch->paginator->setPagingUrl('shop/'.request()->shopSlug.'/branch');
      $branch->paginator->setUrl('shop/'.$this->param['shopSlug'].'/branch/{id}','detailUrl');
      $branch->paginator->setUrl('shop/'.$this->param['shopSlug'].'/branch/edit/{id}','editUrl');
      $branch->paginator->setUrl('shop/'.$this->param['shopSlug'].'/branch/delete/{id}','deleteUrl');

      $this->data = $branch->paginator->build();
    }
    
    $this->setData('branchAddUrl',request()->get('shopUrl').'branch/add');

    return $this->view('pages.shop.branch');
  }

  public function advertising() {

    $url = new Url;

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $shopTos = Service::loadModel('ShopRelateTo')
    ->select('model_id')
    ->where(array(
      array('model','like','Advertising'),
      array('shop_id','=',request()->get('shopId'))
    ));

    if($shopTos->exists()) {

      $advertising = Service::loadModel('Advertising');
      $advertising->paginator->criteria(array(
        'conditions' => array(
          'in' => array(
            array('id',Service::getList($shopTos->get(),'model_id'))
          )
        ),
        'order' => array('created_at','DESC')
      ));
      $advertising->paginator->setPage($page);
      $advertising->paginator->setPagingUrl('shop/'.request()->shopSlug.'/advertising');
      $advertising->paginator->setUrl('shop/'.$this->param['shopSlug'].'/shop_ad_edit/{id}','editUrl');
      $advertising->paginator->setUrl('shop/'.$this->param['shopSlug'].'/shop_ad_delete/{id}','deleteUrl');
      $advertising->paginator->setUrl('advertising/detail/{id}','detailUrl');

      $this->data = $advertising->paginator->build();

    }

    $this->setData('advertisingPostUrl',request()->get('shopUrl').'shop_ad_post');

    return $this->view('pages.shop.advertising');

  }

  public function create() {

    $model = Service::loadModel('Shop');

    $this->mergeData($model->formHelper->build());

    return $this->view('pages.shop.form.shop_create');
  }

  public function creatingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Shop');

    if($model->where([
        ['name','like',$request->get('name')],
        ['person_id','=',session()->get('Person.id')]
      ])
      ->exists()) {

      $_message = 'คุณได้เพิ่มบริษัทหรือร้านค้าชื่อว่า '.$request->get('name').' ไปแล้ว โปรดใช้ชื่ออื่น';
      return Redirect::back()->withErrors([$_message]);

    }elseif($model->where([
        ['name','like',$request->get('name')],
      ])
      ->exists()) {
      
      $_message = 'มีบริษัทหรือร้านค้าชื่อ '.$request->get('name').' นี้แล้ว';
      return Redirect::back()->withErrors([$_message]);

    }

    if($model->fill($request->all())->save()) {

      $slug = $model->getRelatedData('Slug',array(
        'fields' => array('slug'),
        'first' => true
      ))->slug;

      MessageHelper::display('บริษัท ร้านค้า หรือธุรกิจถูกเพิ่มเข้าสู่ชุมชนแล้ว','success');
      return Redirect::to(route('shop.manage', ['slug' => $slug]));
    }else{
      return Redirect::back();
    }

  }

  public function setting() {

    $this->setData('profileImageUrl',request()->get('shopUrl').'profile_image');
    $this->setData('descriptionUrl',request()->get('shopUrl').'description');
    $this->setData('addressUrl',request()->get('shopUrl').'address');
    $this->setData('contactUrl',request()->get('shopUrl').'contact');
    $this->setData('openHoursUrl',request()->get('shopUrl').'opening_hours');

    return $this->view('pages.shop.setting');

  }

  public function description() {

    $model = request()->get('shop');

    $this->data = $model->formHelper->build();

    return $this->view('pages.shop.form.description');

  }

  public function descriptionSubmit() {

    $model = request()->get('shop');

    if($model->fill(request()->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/manage');
    }else{
      return Redirect::back();
    }

  }

  public function openingHours() {

    $openHours = '';
    $sameTime = 0;

    $model = Service::loadModel('OpenHour')->where('shop_id','=',request()->get('shopId'))->first();

    if(!empty($model)) {

      $time = json_decode($model->time,true);
      $openHours = array();
      foreach ($time as $day => $value) {

        $_startTime = explode(':', $value['start_time']);
        $_endTime = explode(':', $value['end_time']);

        $openHours[$day] = array(
          'open' => $value['open'],
          'start_time' => array(
            'hour' => (int)$_startTime[0],
            'min' => (int)$_startTime[1]
          ),
          'end_time' => array(
            'hour' => (int)$_endTime[0],
            'min' => (int)$_endTime[1]
          )
        );
      }

      $openHours = json_encode($openHours);
      $sameTime = $model->same_time;
    }else{
      $model = Service::loadModel('OpenHour');
    }

    $this->data = $model->formHelper->build();
    $this->setData('openHours',$openHours);
    $this->setData('sameTime',$sameTime);

    return $this->view('pages.shop.form.open_hours');

  }

  public function openingHoursSubmit() {

    $model = Service::loadModel('OpenHour')->where('shop_id','=',request()->get('shopId'))->first();

    if(empty($model)) {
      $model = Service::loadModel('OpenHour');
      $model->shop_id = request()->get('shopId');
    }

    if($model->fill(request()->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/manage');
    }else{
      return Redirect::back();
    }

  }

  public function address() {

    $model = request()->get('shop')->getRelatedData('Address',
      array(
        'first' => true
      )
    );

    if(empty($model)) {
      $model = Service::loadModel('Address');
    }

    $model->formHelper->loadFieldData('Province',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'provinces',
      'order' => array(
        array('top','ASC'),
        array('id','ASC')
      )
    ));

    $this->data = $model->formHelper->build();
    $this->setData('_geographic',$model->getGeographic());

    return $this->view('pages.shop.form.address');

  }

  public function addressSubmit() {

    $model = request()->get('shop')->getRelatedData('Address',
      array(
        'first' => true
      )
    );

    if(empty($model)) {
      $model = Service::loadModel('Address');
      $model->model = 'Shop';
      $model->model_id = request()->get('shopId');
    }

    if($model->fill(request()->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/manage');
    }else{
      return Redirect::back();
    }

  }

  public function contact() {
    
    $model = request()->get('shop')->getRelatedData('Contact',
      array(
        'first' => true
      )
    );

    if(empty($model)) {
      $model = Service::loadModel('Contact');
    }

    $this->data = $model->formHelper->build();

    return $this->view('pages.shop.form.contact');

  }

  public function contactSubmit() {

    $model = request()->get('shop')->getRelatedData('Contact',
      array(
        'first' => true
      )
    );

    if(empty($model)) {
      $model = Service::loadModel('Contact');
      $model->model = 'Shop';
      $model->model_id = request()->get('shopId');
    }

    if($model->fill(request()->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/manage');
    }else{
      return Redirect::back();
    }

  }

}
