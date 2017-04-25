<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
use App\library\filterHelper;
use App\library\url;
use App\library\cache;
use Redirect;

class ItemController extends Controller
{
  public function __construct() { 
    parent::__construct();
  }

  public function board() {

    $url = new Url;
    $cache = new Cache;

    $model = Service::loadModel('Item');

    $itemCategories = Service::loadModel('ItemCategory')->all();

    $boards = array();
    foreach ($itemCategories as $category) {
   
      $items = $model
      ->join('item_to_categories', 'item_to_categories.item_id', '=', 'items.id')
      ->where('item_to_categories.item_category_id','=',$category->id);

      $total = $items->count('items.id');

      $items = $items
      ->select('items.*')
      ->orderBy('items.created_at','desc')
      ->take(3)
      ->get();

      $_items = array();
      foreach ($items as $item) {

        $_items['items'][] = array_merge($item->buildPaginationData(),array(
          '_imageUrl' => $this->getImage('list'),
          'detailUrl' => $url->setAndParseUrl('item/detail/{id}',array('id'=>$item->id))
        ));
        
      }

      if($total > 3) {
        $_items['all'] = array(
          'title' => '+'.($total-3)
        );
      }

      $boards[] = array(
        'categoryName' => $category->name,
        'data' => $_items,
        'total' => $total,
        'itemBoardUrl' => $url->setAndParseUrl('item/board/{category_id}',array('category_id'=>$category->id)),
      );

    }

    $this->setData('boards',$boards);

    return $this->view('pages.item.board');

  }

  public function listView() {

    $model = Service::loadModel('Item');
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

    $conditions[] = array('item_to_categories.item_category_id','=',$this->param['category_id']);

    $model->paginator->criteria(array_merge(array(
      'joins' => array('item_to_categories', 'item_to_categories.item_id', '=', 'items.id'),
      'conditions' => $conditions
    ),$order));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('item/board/'.$this->param['category_id']);
    $model->paginator->setUrl('item/detail/{id}','detailUrl');
    $model->paginator->setQuery('sort',$sort);
    $model->paginator->setQuery('fq',$filters);

    $title = Service::loadModel('ItemCategory')->getCategoryName($this->param['category_id']);

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

    return $this->view('pages.item.list');
  }

  public function detail() {

    $model = Service::loadModel('Item')->find($this->param['id']);

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

    return $this->view('pages.item.detail');

  }

  public function add() {

    $model = Service::loadModel('Item');

    $model->formHelper->loadFieldData('Province',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'provinces',
      'order' => array(
        array('top','ASC'),
        array('id','ASC')
      )
    ));

    $model->formHelper->loadFieldData('ItemCategory',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'itemCategories'
    ));

    $model->formHelper->loadFieldData('AnnouncementType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'announcementTypes'
    ));

    $this->data = $model->formHelper->build();
    $this->setData('defaultAnnouncementType',2);

    return $this->view('pages.item.form.item_add');
  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Item');

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ลงประกาศเรียบร้อยแล้ว','success');
      return Redirect::to('item/detail/'.$model->id);
    }else{
      return Redirect::back();
    }

  }

  public function edit() {

    $model = Service::loadModel('Item')->find($this->param['id']);

    $model->formHelper->loadFieldData('Province',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'provinces',
      'order' => array(
        array('top','ASC'),
        array('id','ASC')
      )
    ));

    $model->formHelper->loadFieldData('ItemCategory',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'itemCategories'
    ));

    $model->formHelper->loadFieldData('AnnouncementType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'announcementTypes'
    ));

    $model->formHelper->loadData(array(
      'json' => array('Image','Tagging')
    ));

    $model->formHelper->setFormData('ItemToCategory',array(
      'item_category_id' => Service::loadModel('ItemToCategory')->where('item_id','=',$this->param['id'])->first()->item_category_id
    ));

    $this->data = $model->formHelper->build();

    return $this->view('pages.item.form.item_edit');

  }

  public function editingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Item')->find($this->param['id']);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('item/detail/'.$model->id);
    }else{
      return Redirect::back();
    }
    
  }

  public function delete() {

    $model = Service::loadModel('Item')->find($this->param['id']);

    if(empty($model)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศนี้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    if($model->delete()) {
      MessageHelper::display('ข้อมูลถูกลบแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถลบข้อมูลนี้ได้','error');
    }

    return Redirect::to('account/item');

  }

}