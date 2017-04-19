<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
use App\library\filterHelper;
use App\library\url;
use App\library\cache;
use Redirect;

class AdvertisingController extends Controller
{
  public function __construct() { 
    parent::__construct();
  }

  public function board() {

    $url = new Url;
    $cache = new Cache;

    $model = Service::loadModel('Advertising');

    $advertisingTypes = Service::loadModel('AdvertisingType')->all();

    $boards = array();
    foreach ($advertisingTypes as $type) {

      $advertisings = $model->where('advertising_type_id','=',$type->id);

      $total = $advertisings->count('id');

      $advertisings = $advertisings
      ->orderBy('created_at','desc')
      ->take(3)
      ->get();

      $_advertisings = array();
      foreach ($advertisings as $advertising) {
        
        $image = $advertising->getRelatedData('Image',array(
          'first' => true
        ));

        $imageUrl = '/images/common/no-img.png';
        if(!empty($image)) {
          $imageUrl = $cache->getCacheImageUrl($image,'list');
        }

        $_advertisings['items'][] = array_merge($advertising->buildPaginationData(),array(
          '_imageUrl' => $imageUrl,
          'detailUrl' => $url->setAndParseUrl('advertising/detail/{id}',array('id'=>$advertising->id))
        ));

      }

      if($total > 3) {
        $_advertisings['all'] = array(
          'title' => '+'.($total-3)
        );
      }

      $boards[] = array(
        'typeName' => $type->name,
        'data' => $_advertisings,
        'total' => $total,
        'itemBoardUrl' => $url->setAndParseUrl('advertising/board/{advertising_type_id}',array('advertising_type_id'=>$type->id)),
      );

    }

    $this->setData('boards',$boards);

    return $this->view('pages.advertising.board');

  }

  public function listView() {

    $model = Service::loadModel('Advertising');
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

    $conditions = $filterHelper->buildFilters();
    $order = $filterHelper->buildSorting();

    $conditions[] = array('advertising_type_id','=',$this->param['advertising_type_id']);

    $model->paginator->criteria(array_merge(array(
      'conditions' => $conditions
    ),$order));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('advertising/board/'.$this->param['advertising_type_id']);
    $model->paginator->setUrl('advertising/detail/{id}','detailUrl');
    $model->paginator->setQuery('sort',$sort);
    $model->paginator->setQuery('fq',$filters);

    $title = Service::loadModel('AdvertisingType')->getTypeName($this->param['advertising_type_id']);

    $searchOptions = array(
      'filters' => $filterHelper->getFilterOptions(),
      'sort' => $filterHelper->getSortingOptions()
    );

    $displayingFilters = array(
      'filters' => $filterHelper->getDisplayingFilterOptions(),
      'sort' => $filterHelper->getDisplayingSorting()
    );

    $this->data = $model->paginator->build();
    $this->setData('title',$title);
    $this->setData('searchOptions',$searchOptions);
    $this->setData('displayingFilters',$displayingFilters);

    return $this->view('pages.advertising.list');
  }

  public function shopAdvertisinglistView() {

    $model = Service::loadModel('Advertising');
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

    $conditions[] = array(
      array('shop_relate_to.model','like','Advertising'),
      array('shop_relate_to.shop_id','=',request()->get('shopId'))
    );

    $criteria = array();

    $criteria = array_merge($criteria,array(
      'joins' => array('shop_relate_to', 'shop_relate_to.model_id', '=', 'advertisings.id')
    ));

    $criteria = array_merge($criteria,array(
      'conditions' => $conditions
    ));

    if(!empty($order)) {
      $criteria = array_merge($criteria,$order);
    }

    $model->paginator->criteria($criteria);
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('shop/'.request()->shopSlug.'/advertising');
    $model->paginator->setUrl('shop/'.request()->shopSlug.'/advertising/{id}','detailUrl');
    $model->paginator->setQuery('sort',$sort);
    $model->paginator->setQuery('fq',$filters);

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

    return $this->view('pages.advertising.shop_advertising_list');

  }

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

    // Get Shop Address
    $shop = $model->getRelatedData('ShopRelateTo',array(
      'first' => true,
    ))->shop;

    // Get Slug
    $slug = $shop->getRelatedData('Slug',array(
      'first' => true,
    ))->slug;

    // Get Branches
    $branchIds = $model->getRelatedData('RelateToBranch',array(
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
          'detailUrl' => $url->setAndParseUrl('shop/{shopSlug}/branch/{id}',array(
            'shopSlug' => $slug,
            'id' => $branch->id
          ))
        );
      }
    }

    $this->data = $model->modelData->build();
    $this->setData('shop',$shop->modelData->build(true));
    $this->setData('shopImageUrl',$shop->getProfileImageUrl());
    $this->setData('shopCoverUrl',$shop->getCoverUrl());
    $this->setData('shopUrl','shop/'.$slug);
    $this->setData('branchLocations',json_encode($branchLocations));
    $this->setData('hasBranchLocation',$hasBranchLocation);

    return $this->view('pages.advertising.detail');

  }

  public function shopAdvertisingDetail() {

    $model = Service::loadModel('Advertising')->find($this->param['id']);

    $model->modelData->loadData(array(
      'models' => array('Image','Tagging'),
      'json' => array('Image')
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
          'detailUrl' => $url->setAndParseUrl('shop/{shopSlug}/branch/{id}',array(
            'shopSlug' => request()->shopSlug,
            'id' => $branch->id
          ))
        );
      }
    }

    $this->data = $model->modelData->build();
    $this->setData('branchLocations',json_encode($branchLocations));
    $this->setData('hasBranchLocation',$hasBranchLocation);

    return $this->view('pages.advertising.shop_advertising_detail');

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

    $request->request->add(['ShopRelateTo' => array('shop_id' => request()->get('shopId'))]);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ลงประกาศแล้ว','success');
      return Redirect::to('shop/'.$request->shopSlug.'/advertising');
    }else{
      return Redirect::back();
    }

  }

  public function edit() {

    $model = Service::loadModel('Advertising')->find($this->param['id']);

    $model->formHelper->loadData(array(
      'models' => array('Image','Tagging'),
      'json' => array('Image','Tagging')
    ));

    $model->formHelper->loadFieldData('AdvertisingType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'advertisingTypes'
    ));

    $relateToBranch = $model->getRelatedData('RelateToBranch',array(
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

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/manage/advertising');
    }else{
      return Redirect::back();
    }

  }

}
