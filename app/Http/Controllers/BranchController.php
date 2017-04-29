<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
use App\library\filterHelper;
use App\library\url;
use Redirect;

class BranchController extends Controller
{

  public function __construct() { 
    parent::__construct();
  }

  public function listView() {

    $model = Service::loadModel('Branch');
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
      array('shop_relate_to.model','like','Branch'),
      array('shop_relate_to.shop_id','=',request()->get('shopId'))
    );

    $criteria = array();

    $criteria = array_merge($criteria,array(
      'joins' => array('shop_relate_to', 'shop_relate_to.model_id', '=', 'branches.id')
    ));

    $criteria = array_merge($criteria,array(
      'conditions' => $conditions
    ));

    if(!empty($order)) {
      $criteria = array_merge($criteria,$order);
    }

    $model->paginator->criteria($criteria);
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('shop/'.request()->shopSlug.'/branch');
    $model->paginator->setUrl('shop/'.request()->shopSlug.'/branch/{id}','detailUrl');
    $model->paginator->setQuery('sort',$sort);
    $model->paginator->setQuery('fq',$filters);

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

    $this->setPageTitle('สาขา - '.request()->get('shop')->name);

    return $this->view('pages.branch.list');

  }

  public function detail() {

    $model = Service::loadModel('Branch')->find($this->param['id']);

    $model->modelData->loadData(array(
      'models' => array('Image','Address','Contact'),
      'json' => array('Image')
    ));

    $this->data = $model->modelData->build();

    // Get Branches
    $jobIds = $model->getRelatedData('RelateToBranch',array(
      'conditions' => array(
        'model' => 'Job'
      ),
      'list' => 'model_id',
      'fields' => array('model_id'),
    ));

    if(!empty($jobIds)) {

      $conditions = array(
        'in' => array(
          array('id',$jobIds)
        )
      );
      
      $job = Service::loadModel('Job');
      $job->paginator->criteria(array(
        'conditions' => $conditions,
        'order' => array('id','DESC')
      ));
      $job->paginator->setPerPage(12);
      $job->paginator->setUrl('job/detail/{id}','detailUrl');

      $this->setData('jobs',$job->paginator->getPaginationData());

    }else{
      $this->setData('jobs',array());
    }

    $shop = request()->get('shop');
    $this->setData('shop',$shop->modelData->build(true));
    $this->setData('shopImageUrl',$shop->getProfileImageUrl());
    $this->setData('shopCoverUrl',$shop->getCoverUrl());
    $this->setData('shopUrl',request()->get('shopUrl'));

    $this->setPageTitle($this->data['_modelData']['name'].' - สาขา - '.request()->get('shop')->name);

    return $this->view('pages.branch.detail');

  }

  public function add() {

    $model = Service::loadModel('Branch');

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

    return $this->view('pages.branch.form.branch_add');
    
  }

  public function addingSubmit(CustomFormRequest $request) {
    $model = Service::loadModel('Branch');

    $request->request->add(['ShopRelateTo' => array('shop_id' => request()->get('shopId'))]);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('สาขา '.$model->name.' ถูกเพิ่มแล้ว','success');
      return Redirect::to('shop/'.$request->shopSlug.'/manage/branch');
    }else{
      return Redirect::back();
    }
  }

  public function edit() {

    $model = Service::loadModel('Branch')->find($this->param['id']);

    $model->formHelper->loadFieldData('Province',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'provinces',
      'order' => array(
        array('top','ASC'),
        array('id','ASC')
      )
    ));

    $model->formHelper->loadData(array(
      'json' => array('Image')
    ));

    $this->data = $model->formHelper->build();

    return $this->view('pages.branch.form.branch_edit');

  }

  public function editingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Branch')->find($this->param['id']);

    if($model->fill($request->all())->save()) {

      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.$request->shopSlug.'/manage/branch');
    }else{
      return Redirect::back();
    }
    
  }

  public function delete() {

    $model = Service::loadModel('Branch')->find($this->param['id']);

    if($model->delete()) {
      MessageHelper::display('ข้อมูลถูกลบแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถลบข้อมูลนี้ได้','error');
    }

    return Redirect::to('shop/'.request()->shopSlug.'/manage/branch');
  }

}
