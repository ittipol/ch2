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

    $this->setPageTitle('บริษัทและร้านค้า');
    $this->setPageDescription('สร้างร้านค้าออนไลน์ในแบบของคุณ เปิดโอกาสและเพิ่มช่องทางการขายสินค้าให้กับธุรกิจของคุณ เพื่อให้ธุรกิจของคุณเชื่อมต่อไปยังคนนับล้านบนอินเตอร์เน็ต');
    $this->setMetaKeywords('ร้านค้า,ร้านค้าออนไลน์,สินค้า,งาน,ตำแหน่งงาน,ประกาศงาน');

  }

  public function index() {

    $url = new Url;

    $permission = request()->get('shopPermission');

    $model = request()->get('shop');

    $pinnedPosts = Service::loadModel('Timeline')
    ->where([
      ['model','like','Shop'],
      ['model_id','=',request()->get('shopId')],
      ['pinned','=',1],
      ['timeline_post_type_id','=',Service::loadModel('TimelinePostType')->getIdByalias('text')]
    ])
    ->orderBy('timeline_date','desc');

    $_pinnedPosts = array();
    if($pinnedPosts->exists()) {
      foreach ($pinnedPosts->get() as $pinnedPost) {

        $options = array();
        if(!empty($permission['edit']) && $permission['edit']) {
          $options['cancelPinUrl'] = $url->url('shop/'.$this->param['shopSlug'].'/timeline/pinned/cancel/'.$pinnedPost->id);
        }

        if(!empty($permission['delete']) && $permission['delete']) {
          $options['deleteUrl'] = $url->url('shop/'.$this->param['shopSlug'].'/timeline/delete/'.$pinnedPost->id);
        }

        $_pinnedPosts[] = array_merge($pinnedPost->buildModelData(),$options);
      }
    }

    $products = Service::loadModel('Product')
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', 'products.id')
    ->where('shop_relate_to.model','like','Product')
    ->where('shop_relate_to.shop_id','=',$model->id)
    ->select('products.*')
    ->take(24)
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
    $this->setData('permission',$permission);
    $this->setData('productCatalogs',$_productCatalogs);
    $this->setData('pinnedMessages',$_pinnedPosts);

    $this->setPageTitle(request()->get('shop')->name,false);
    $this->setPageImage(request()->get('shop')->getProfileImageUrl());
    $this->setPageDescription(request()->get('shop')->description);

    $this->botAllowed();

    return $this->view('pages.shop.index');
  }

  public function about() {

    $model = request()->get('shop');
    $model->modelData->loadData();

    $this->data = $model->modelData->build();
    $this->setData('openHours',$model->getOpenHours());
    $this->setData('about',$model->getShopAbout());

    $this->setPageTitle('เกี่ยวกับ - '.request()->get('shop')->name,false);
    $this->setPageImage(request()->get('shop')->getProfileImageUrl());
    $this->setPageDescription(request()->get('shop')->getShortDescription());
    $this->setMetaKeywords('เวลาทำการ,ที่อยู่,การติดต่อ,หมายเลขโทรศัพท์,คำอธิบายเกี่ยวกับ,เรื่องราว,Brand Story,พันธกิจ');

    $this->botAllowed();

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
    $model->paginator->setPagingUrl('shop');
    $model->paginator->setUrl('shop/{slug}','shopUrl');
    $model->paginator->setQuery('sort',$sort);
    $model->paginator->setQuery('fq',$filters);
    $model->paginator->disableGetImage();

    $searchOptions = array(
      'filters' => $filterHelper->getFilterOptions(),
      'sort' => $filterHelper->getSortingOptions()
    );

    // $displayingFilters = array(
    //   'filters' => $filterHelper->getDisplayingFilterOptions(),
    //   'sort' => $filterHelper->getDisplayingSorting()
    // );

    $this->data = $model->paginator->build();
    $this->setData('searchOptions',$searchOptions);
    // $this->setData('displayingFilters',$displayingFilters);

    $this->setPageTitle('บริษัทและร้านค้า');

    $this->botAllowed();

    return $this->view('pages.shop.list');

  }

  public function overview() {

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

    // $this->setData('totalBranch',$shopRelateToModel
    // ->where([
    //   ['shop_id','=',$model->id],
    //   ['model','like','Branch']
    // ])->count());

    $this->setData('totalProductCatalog',$shopRelateToModel
    ->where([
      ['shop_id','=',$model->id],
      ['model','like','ProductCatalog']
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

    $this->setPageTitle(request()->get('shop')->name);

    return $this->view('pages.shop.overview');
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
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/product/status/edit/{id}','productStatusUrl');
    $model->paginator->setUrl('shop/'.$this->param['shopSlug'].'/product/{id}','detailUrl');

    $this->data = $model->paginator->build();

    $this->setData('countOrder',Service::loadModel('Order')->where([
      ['shop_id','=',request()->get('shopId')],
      ['order_status_id','=',1]
    ])->count());

    $this->setPageTitle(request()->get('shop')->name);

    return $this->view('pages.shop.product');
  }

  public function paymentMethod() {

    $model = Service::loadModel('PaymentMethod');

    $url = new Url;

    // Get Payment method types
    $paymentMethodTypes = Service::loadModel('PaymentMethodType')->get();

    $_paymentMethods = array();
    foreach ($paymentMethodTypes as $paymentMethodType) {
      
      // Get Payment method
      $paymentMethods = $model
      ->join('shop_relate_to', 'shop_relate_to.model_id', '=', 'payment_methods.id')
      ->select('payment_methods.*')
      ->where([
        ['shop_relate_to.model','like',$model->modelName],
        ['shop_relate_to.shop_id','=',request()->get('shopId')],
        ['payment_method_type_id','=',$paymentMethodType->id]
      ]);

      $data = array();
      if($paymentMethods->exists()) {

        foreach ($paymentMethods->get() as $paymentMethod) {
          $data[] = array_merge(
            $paymentMethod->buildModelData(),
            array(
              'editUrl' => request()->get('shopUrl').'payment_method/edit/'.$paymentMethod->id,
              'deleteUrl' => request()->get('shopUrl').'payment_method/delete/'.$paymentMethod->id
            )
          );
        }

      }

      $_paymentMethods[] = array(
        // 'total' => $paymentMethods->count(),
        'name' => $paymentMethodType->name,
        'addUrl' => request()->get('shopUrl').'payment_method/add/'.$paymentMethodType->alias,
        'data' => $data
      );

    }

    $this->setPageTitle(request()->get('shop')->name);

    $this->setData('paymentMethods',$_paymentMethods);
    $this->setData('hasPaymentMethod',$model->hasPaymentMethod(request()->get('shopId')));

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

    $this->setPageTitle(request()->get('shop')->name);

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

    $this->setPageTitle(request()->get('shop')->name);

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

    $this->setPageTitle(request()->get('shop')->name);

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
      $advertising->paginator->setUrl('shop/'.request()->shopSlug.'/advertising/{id}','detailUrl');

      $this->data = $advertising->paginator->build();

    }

    $this->setData('advertisingPostUrl',request()->get('shopUrl').'advertising/add');

    $this->setPageTitle(request()->get('shop')->name);

    return $this->view('pages.shop.advertising');

  }

  public function create() {

    $model = Service::loadModel('Shop');

    $this->data = $model->formHelper->build();

    $this->setPageTitle('สร้างร้านค้า');
    $this->setMetaKeywords('สร้างร้านค้า,สร้างร้านค้าออนไลน์,ร้านค้าออนไลน์,บริษัท,ร้านค้า,ธุรกิจ');

    $this->botAllowed();

    return $this->view('pages.shop.form.shop_create');
  }

  public function creatingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Shop');

    if(empty($request->get('term_and_condition_accepted')) || !$request->get('term_and_condition_accepted')) {
      return Redirect::back()->withErrors(['ยังไม่ได้ยอมรับเงื่อนไขและข้อตกลง'])->withInput(request()->all());
    }

    if($model->where([
        ['name','like',$request->get('name')],
        ['created_by','=',session()->get('Person.id')]
      ])
      ->exists()) {

      $_message = 'คุณได้เพิ่มบริษัทหรือร้านค้าชื่อว่า '.$request->get('name').' ไปแล้ว โปรดใช้ชื่ออื่น';
      return Redirect::back()->withErrors([$_message])->withInput($request->all());

    }elseif($model->where([
        ['name','like',$request->get('name')],
      ])
      ->exists()) {
      
      $_message = 'มีบริษัทหรือร้านค้าชื่อ '.$request->get('name').' นี้แล้ว';
      return Redirect::back()->withErrors([$_message])->withInput($request->all());

    }

    if($model->fill($request->all())->save()) {

      // $slug = $model->getRelatedData('Slug',array(
      //   'fields' => array('slug'),
      //   'first' => true
      // ))->slug;

      // MessageHelper::display('บริษัท ร้านค้า หรือธุรกิจถูกเพิ่มแล้ว','success');
      // return Redirect::to(route('shop.overview', ['slug' => $slug]));

      session()->flash('shop-create-success',$model->id);
      return Redirect::to('shop/create/success');
    }

    return Redirect::back();
  }

  public function createSuccess() {

    if(!session()->has('shop-create-success')) {
      return Redirect::to('shop/create');
    }

    $url = new Url;

    $shop = Service::loadModel('Shop')->find(session()->get('shop-create-success'));

    $slug = $shop->getRelatedData('Slug',array(
      'fields' => array('slug'),
      'first' => true
    ))->slug;

    $this->setData('shopUrl',$url->url('shop/'.$slug));
    $this->setData('addProductUrl',$url->url('shop/'.$slug.'/product/add'));

    return $this->view('pages.shop.shop_create_success');

  }

  public function setting() {
    return $this->view('pages.shop.setting');
  }

  public function shopNameEdit() {

    $model = request()->get('shop');

    $this->data = $model->formHelper->build();

    return $this->view('pages.shop.form.shop_name_edit');
  }

  public function shopNameEditingSubmit(CustomFormRequest $request) {

    $model = request()->get('shop');

    if($model->name == request()->get('name')) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/setting');
    }

    if($model->where([
        ['name','like',$request->get('name')],
        ['created_by','=',session()->get('Person.id')]
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

      // update shop name in look up
      Service::loadModel('Lookup')->where('shop_id','=',$model->id)->update(['shop_name' => $model->name]);

      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/setting');
    }else{
      return Redirect::back();
    }
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
      return Redirect::to('shop/'.request()->shopSlug.'/setting');
    }else{
      return Redirect::back();
    }

  }

  public function openingHours() {

    $openHours = '';
    $sameTime = 0;

    $model = Service::loadModel('OpenHour')->where([
      ['model','=','Shop'],
      ['model_id','=',request()->get('shopId')]
    ])->first();

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

    $model = Service::loadModel('OpenHour')->where([
      ['model','=','Shop'],
      ['model_id','=',request()->get('shopId')]
    ])->first();

    if(empty($model)) {
      $model = Service::loadModel('OpenHour');
      $model->model = 'Shop';
      $model->model_id = request()->get('shopId');
    }

    if($model->fill(request()->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/setting');
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
    // $this->setData('_geographic',$model->getGeographic());
    
    return $this->view('pages.shop.form.address');

  }

  public function addressSubmit() {

    $address = request()->get('shop')->getRelatedData('Address',
      array(
        'first' => true
      )
    );

    if(empty($address)) {
      $address = Service::loadModel('Address');
      $address->model = 'Shop';
      $address->model_id = request()->get('shopId');
    }

    if($address->fill(request()->all())->save()) {

      // update lookup table
      Service::loadModel('Lookup')->where('shop_id','=',$address->model_id)->update(['address' => $address->getAddress(true)]);
      // Service::loadModel('Lookup')->where([
      //   ['model','like',$address->model],
      //   ['model_id','=',$address->model_id]
      // ])->update(['address' => $address->getAddress()]);

      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/setting');
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
      return Redirect::to('shop/'.request()->shopSlug.'/setting');
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

  public function postMessage() {

    $message = trim(request()->get('message'));

    if(empty($message)) {
      MessageHelper::displayWithDesc('ไม่พบข้อความที่ต้องการโพสต์','กรุณากรอกข้อความ','error');
      return Redirect::to('shop/'.request()->shopSlug);
    }

    $model = Service::loadModel('Timeline');

    $model->model = 'Shop';
    $model->model_id = request()->get('shopId');
    $model->title = 'โพสต์ข้อความ';
    $model->message = $message;
    $model->pinned = 1;
    $model->timeline_post_type_id = Service::loadModel('TimelinePostType')->getIdByalias('text');

    if($model->save()) {
      MessageHelper::display('ข้อความถูกโพสต์แล้ว','success');
    }else{
      MessageHelper::display('เกิดข้ิผิดพลาด ไม่สามารถโพสต์ข้อความได้','error');
    }

    return Redirect::to('shop/'.request()->shopSlug);

  }

  public function cancelMessage() {

    $model = Service::loadModel('Timeline')->find($this->param['id']);
    $model->pinned = 0;

    if($model->save()) {
      MessageHelper::display('ยกเลิกการตรึงข้อความแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถยกเลิกการตรึงข้อความแล้ว','error');
    }

    return Redirect::to('shop/'.$this->param['shopSlug']);

  }

  public function deleteMessage() {

    $model = Service::loadModel('Timeline')->find($this->param['id']);
    
    if($model->delete()) {
      MessageHelper::display('ข้อมูลถูกลบแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถลบข้อมูลนี้ได้','error');
    }

    return Redirect::to('shop/'.$this->param['shopSlug']);

  }

  public function delete() {

    $model = request()->get('shop');

    if(empty($model)) {
      MessageHelper::display('ไม่พบร้านค้านี้','error');
      return Redirect::to('/');
    }

    if($model->delete()) {
      MessageHelper::display(request()->get('shop')->name.' ถูกลบแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถลบร้านค้าแล้วได้','error');
    }

    return Redirect::to('/');
  }

}
