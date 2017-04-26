<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\url;
use App\library\messageHelper;
use App\library\filterHelper;
use App\library\timelineHelper;
use Redirect;

class ShopController extends Controller
{

  public function __construct() { 
    parent::__construct();
  }

  public function index() {

    $url = new Url;

    $model = request()->get('shop');

    // $pinnedMessages = Service::loadModel('Timeline')->getPinnedMessage('Shop',request()->get('shopId'));
    
    // shop pinned message
    $pinnedMessages = Service::loadModel('Timeline')
    ->where([
      ['model','like','Shop'],
      ['model_id','=',request()->get('shopId')],
      ['pinned','=',1],
      ['type','=','text']
    ])
    ->orderBy('created_at','desc');

    $_pinnedMessages = array();
    if($pinnedMessages->exists()) {
      foreach ($pinnedMessages->get() as $pinnedMessage) {
        $_pinnedMessages[] = $pinnedMessage->buildModelData();
      }
    }

    $products = Service::loadModel('Product')
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', 'products.id')
    ->where('shop_relate_to.model','like','Product')
    ->where('shop_relate_to.shop_id','=',$model->id)
    ->select('products.*')
    ->take(6)
    ->orderBy('products.created_at','desc')
    ->get();

    $_products = array();
    foreach ($products as $product) {
      $_products[] = array_merge($product->buildPaginationData(),array(
        '_imageUrl' => $product->getCacheImageUrl('list'),
        'detailUrl' => $url->url('shop/'.$this->param['shopSlug'].'/product/'.$product->id)
      ));
    }

    // Get Product Catalog
    $productCatalogs = $model->getProductCatalogs();

    $_productCatalogs = array();
    if(!empty($productCatalogs)) {
      foreach ($productCatalogs as $productCatalog) {
        $_productCatalogs[] = array(
          'name' => $productCatalog->name,
          '_imageUrl' => $productCatalog->getCacheImageUrl('list'),
          'detailUrl' => $url->url('shop/'.$this->param['shopSlug'].'/product_catalog/'.$productCatalog->id),
        );
      }
    }

    $this->setData('products',$_products);
    $this->setData('permission',request()->get('shopPermission'));
    $this->setData('productCatalogs',$_productCatalogs);
    $this->setData('pinnedMessages',$_pinnedMessages);

    return $this->view('pages.shop.index');
  }

  public function about() {

    $model = request()->get('shop');
    $model->modelData->loadData();

    $this->data = $model->modelData->build();
    $this->setData('openHours',$model->getOpenHours());
    $this->setData('about',$model->getShopAbout());

    return $this->view('pages.shop.about');

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

    $shopRelateToModel = Service::loadModel('ShopRelateTo');

    $this->setData('totalProduct',$shopRelateToModel
    ->where([
      ['shop_id','=',$model->id],
      ['model','like','Product']
    ])->count());

    $this->setData('totalJob',$shopRelateToModel
    ->where([
      ['shop_id','=',$model->id],
      ['model','like','Job']
    ])->count());

    $this->setData('totalAdvertising',$shopRelateToModel
    ->where([
      ['shop_id','=',$model->id],
      ['model','like','Advertising']
    ])->count());

    $this->setData('totalBranch',$shopRelateToModel
    ->where([
      ['shop_id','=',$model->id],
      ['model','like','Branch']
    ])->count());

    $orderModel = Service::loadModel('Order');

    $this->setData('totalOrder',$orderModel
    ->where('shop_id','=',$model->id)
    ->count());

    $this->setData('countNewOrder',$orderModel->where([
      ['shop_id','=',$model->id],
      ['order_status_id','=',1]
    ])->count());

    $personApplyJobModel = Service::loadModel('PersonApplyJob');

    $this->setData('totalJobApplying',$personApplyJobModel
    ->where('shop_id','=',$model->id)
    ->count());

    $this->setData('countNewJobApplying',$personApplyJobModel
    ->where([
      ['shop_id','=',$model->id],
      ['job_applying_status_id','=',1]
    ])
    ->count());

    // $this->setData('countActiveJobApplying',$personApplyJobModel
    // ->where([
    //   ['shop_id','=',$model->id],
    //   ['job_applying_status_id','=',2]
    // ])
    // ->count());

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
    $model->paginator->setPagingUrl('shop/'.request()->shopSlug.'/manage/product');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/manage/product/{id}','menuUrl');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/product/delete/{id}','deleteUrl');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/product/{id}','detailUrl');

    $this->data = $model->paginator->build();

    $this->setData('countOrder',Service::loadModel('Order')->where([
      ['shop_id','=',request()->get('shopId')],
      ['order_status_id','=',1]
    ])->count());

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
      $jobModel->paginator->setPagingUrl('shop/'.request()->shopSlug.'/manage/job');
      $jobModel->paginator->setUrl('shop/'.$this->param['shopSlug'].'/job/edit/{id}','editUrl');
      $jobModel->paginator->setUrl('shop/'.$this->param['shopSlug'].'/job/delete/{id}','deleteUrl');
      $jobModel->paginator->setUrl('shop/'.request()->shopSlug.'/job/{id}','detailUrl');

      $this->data = $jobModel->paginator->build();
    }

    $this->setData('countJobApplying',Service::loadModel('PersonApplyJob')
    ->where([
      ['shop_id','=',request()->get('shopId')],
      ['job_applying_status_id','=',1]
    ])
    ->count());
    
    $this->setData('jobPostUrl',request()->get('shopUrl').'job/add');
    $this->setData('jobApplyListUrl',request()->get('shopUrl').'job_applying');

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
      $advertising->paginator->setPagingUrl('shop/'.request()->shopSlug.'/manage/advertising');
      $advertising->paginator->setUrl('shop/'.$this->param['shopSlug'].'/advertising/edit/{id}','editUrl');
      $advertising->paginator->setUrl('shop/'.$this->param['shopSlug'].'/advertising/delete/{id}','deleteUrl');
      $advertising->paginator->setUrl('advertising/detail/{id}','detailUrl');

      $this->data = $advertising->paginator->build();

    }

    $this->setData('advertisingPostUrl',request()->get('shopUrl').'advertising/add');

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

  public function productCatalog() {

    $url = new Url;
    
    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $model = Service::loadModel('ProductCatalog');
    $model->paginator->criteria(array(
      'joins' => array('shop_relate_to', 'shop_relate_to.model_id', '=', $model->getTable().'.id'),
      'conditions' => array(
        array('shop_relate_to.model','like',$model->modelName),
        array('shop_relate_to.shop_id','=',request()->get('shopId'))
      ),
      'order' => array('id','DESC')
    ));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('shop/'.request()->shopSlug.'/manage/product_catalog');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/product_catalog/{id}','detailUrl');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/manage/product_catalog/{id}','menuUrl');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/product_catalog/product_list/edit/{id}','catalogEditUrl');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/product_catalog/delete/{id}','deleteUrl');

    $this->data = $model->paginator->build();

    return $this->view('pages.shop.product_catalog');
  }

  public function pinnedMessageAddingSubmit() {

    $model = Service::loadModel('Timeline');

    // ตรึงข้อความไว้ยังหน้าแรก

    $model->model = 'Shop';
    $model->model_id = request()->get('shopId');
    $model->title = 'โพสต์ข้อความ';
    $model->message = request()->get('message');
    $model->pinned = 1;
    $model->type = 'text';

    if($model->save()) {
      MessageHelper::display('ข้อความถูกโพสต์แล้ว','success');
    }else{
      MessageHelper::display('เกิดข้ิผิดพลาด ไม่สามารถโพสต์ข้อความได้','error');
    }

    return Redirect::to('shop/'.request()->shopSlug);

  }

}
