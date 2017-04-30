<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
use App\library\filterHelper;
use App\library\url;
use App\library\cache;
use Redirect;
use Auth;

class RealEstateController extends Controller
{
  public function __construct() { 
    parent::__construct();
  }

  public function board() {

    $url = new Url;
    $cache = new Cache;

    $model = Service::loadModel('RealEstate');

    $realEstateTypes = Service::loadModel('RealEstateType')->all();

    $boards = array();
    foreach ($realEstateTypes as $type) {

      $realEsates = $model->where('real_estate_type_id','=',$type->id);

      $total = $realEsates->count('id');

      $realEsates = $realEsates
      ->orderBy('created_at','desc')
      ->take(3)
      ->get();

      $_realEsates = array();
      foreach ($realEsates as $realEsate) {

        $_realEsates['items'][] = array_merge($realEsate->buildPaginationData(),array(
          '_imageUrl' => $realEsate->getImage('list'),
          'detailUrl' => $url->setAndParseUrl('real-estate/detail/{id}',array('id'=>$realEsate->id))
        ));
        
      }

      if($total > 3) {
        $_realEsates['all'] = array(
          'title' => '+'.($total-3)
        );
      }

      $boards[] = array(
        'typeName' => $type->name,
        'data' => $_realEsates,
        'total' => $total,
        'itemBoardUrl' => $url->setAndParseUrl('real-estate/board/{real_estate_type_id}',array('real_estate_type_id'=>$type->id)),
      );

    }

    $this->setData('boards',$boards);

    $this->setPageTitle('ประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์');

    return $this->view('pages.real_estate.board');

  }

  public function listView() {

    $model = Service::loadModel('RealEstate');
    // $realEstateTypes = Service::loadModel('RealEstateType');
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

    $conditions[] = array('real_estate_type_id','=',$this->param['real_estate_type_id']);

    $model->paginator->criteria(array_merge(array(
      'conditions' => $conditions
    ),$order));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('real-estate/board/'.$this->param['real_estate_type_id']);
    $model->paginator->setUrl('real-estate/detail/{id}','detailUrl');
    $model->paginator->setQuery('sort',$sort);
    $model->paginator->setQuery('fq',$filters);

    $title = Service::loadModel('RealEstateType')->getTypeName($this->param['real_estate_type_id']);

    $searchOptions = array(
      'filters' => $filterHelper->getFilterOptions(),
      'sort' => $filterHelper->getSortingOptions()
    );

    // $displayingFilters = array(
    //   'filters' => $filterHelper->getDisplayingFilterOptions(),
    //   'sort' => $filterHelper->getDisplayingSorting()
    // );

    $this->data = $model->paginator->build();
    $this->setData('title',$title);
    $this->setData('searchOptions',$searchOptions);
    // $this->setData('displayingFilters',$displayingFilters);

    $this->setPageTitle($title.' - ประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์');

    return $this->view('pages.real_estate.list');
  }

  public function detail() {

    $model = Service::loadModel('RealEstate')->find($this->param['id']);

    if(empty($model)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศนี้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    $model->modelData->loadData(array(
      'json' => array('Image')
    ));

    $this->data = $model->modelData->build();

    if(Auth::check() && (session()->get('Person.id') == $model->created_by)) {
      $url = new Url;
      $this->setData('deleteUrl',$url->url('account/real-estate/delete/'.$model->id));
    }

    $this->setPageTitle($this->data['_modelData']['name'].' - ประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์');

    return $this->view('pages.real_estate.detail');

  }

  public function add() { 

    $model = Service::loadModel('RealEstate');

    $model->formHelper->loadFieldData('Province',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'provinces',
      'order' => array(
        array('top','ASC'),
        array('id','ASC')
      )
    ));

    $model->formHelper->loadFieldData('RealEstateType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'realEstateTypes'
    ));

    $model->formHelper->loadFieldData('AnnouncementType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'announcementTypes'
    ));

    $model->formHelper->loadFieldData('RealEstateFeature',array(
      'conditions' => array(
        ['real_estate_feature_type_id','=',1]
      ),
      'key' =>'id',
      'field' => 'name',
      'index' => 'feature'
    ));

    $model->formHelper->loadFieldData('RealEstateFeature',array(
      'conditions' => array(
        ['real_estate_feature_type_id','=',2]
      ),
      'key' =>'id',
      'field' => 'name',
      'index' => 'facility'
    ));

    $this->data = $model->formHelper->build();
    $this->setData('defaultAnnouncementType',2);

    $this->setPageTitle('ลงประกาศอสังหาริมทรัพย์');

    return $this->view('pages.real_estate.form.real_estate_add');
  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('RealEstate');

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ลงประกาศเรียบร้อยแล้ว','success');
      return Redirect::to('real-estate/detail/'.$model->id);
    }else{
      return Redirect::back();
    }
  }

  public function edit() {

    $model = Service::loadModel('RealEstate')->find($this->param['id']);

    $model->formHelper->loadFieldData('Province',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'provinces',
      'order' => array(
        array('top','ASC'),
        array('id','ASC')
      )
    ));

    $model->formHelper->loadFieldData('RealEstateType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'realEstateTypes'
    ));

    $model->formHelper->loadFieldData('AnnouncementType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'announcementTypes'
    ));

    $model->formHelper->loadFieldData('RealEstateFeature',array(
      'conditions' => array(
        ['real_estate_feature_type_id','=',1]
      ),
      'key' =>'id',
      'field' => 'name',
      'index' => 'feature'
    ));

    $model->formHelper->loadFieldData('RealEstateFeature',array(
      'conditions' => array(
        ['real_estate_feature_type_id','=',2]
      ),
      'key' =>'id',
      'field' => 'name',
      'index' => 'facility'
    ));

    $model->formHelper->loadData(array(
      'json' => array('Image','Tagging')
    ));

    $this->mergeData($model->formHelper->build());

    return $this->view('pages.real_estate.form.real_estate_edit');

  }

  public function editingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('RealEstate')->find($this->param['id']);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('real-estate/detail/'.$model->id);
    }else{
      return Redirect::back();
    }
    
  }

  public function delete() {

    $model = Service::loadModel('RealEstate')->find($this->param['id']);

    if($model->delete()) {
      MessageHelper::display('ยกเลิกการประกาศแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถยกเลิกการประกาศได้','error');
    }

    return Redirect::to('account/real-estate');

  }

}
