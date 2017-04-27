<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
use App\library\filterHelper;
use App\library\url;
use App\library\cache;
use Redirect;
use Session;

class FreelanceController extends Controller
{
  public function board() {

    $url = new Url;
    $cache = new Cache;

    $model = Service::loadModel('Freelance');

    $employmentTypes = Service::loadModel('FreelanceType')->all();

    $boards = array();
    foreach ($employmentTypes as $type) {

      $freelances = $model->where('freelance_type_id','=',$type->id);

      $total = $freelances->count('id');

      $freelances = $freelances
      ->orderBy('created_at','desc')
      ->take(3)
      ->get();

      $_freelances = array();
      foreach ($freelances as $freelance) {

        $_freelances['items'][] = array_merge($freelance->buildPaginationData(),array(
          '_imageUrl' => $freelance->getImage('list'),
          'detailUrl' => $url->setAndParseUrl('freelance/detail/{id}',array('id'=>$freelance->id))
        ));
        
      }

      if($total > 3) {
        $_freelances['all'] = array(
          'title' => '+'.($total-3)
        );
      }

      $boards[] = array(
        'typeName' => $type->name,
        'data' => $_freelances,
        'total' => $total,
        'itemBoardUrl' => $url->setAndParseUrl('freelance/board/{freelance_type_id}',array('freelance_type_id'=>$type->id)),
      );

    }

    $this->setData('boards',$boards);

    return $this->view('pages.freelance.board');

  }

  public function listView() {

    $model = Service::loadModel('Freelance');
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

    $conditions[] = array('freelance_type_id','=',$this->param['freelance_type_id']);

    $model->paginator->criteria(array_merge(array(
      'conditions' => $conditions
    ),$order));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('freelance/board/'.$this->param['freelance_type_id']);
    $model->paginator->setUrl('freelance/detail/{id}','detailUrl');
    $model->paginator->setQuery('sort',$sort);
    $model->paginator->setQuery('fq',$filters);

    $title = Service::loadModel('FreelanceType')->getTypeName($this->param['freelance_type_id']);

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
    
    return $this->view('pages.freelance.list');

  }

  public function detail() {

    $url = new Url;

    $model = Service::loadModel('Freelance')->find($this->param['id']);

    if(empty($model)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศนี้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    $model->modelData->loadData(array(
      'models' => array('Image','Tagging'),
      'json' => array('Image')
    ));

    $person = Service::loadModel('Person')->find(Session::get('Person.id'));

    $person->modelData->loadData(array(
      'models' => array('Address','Contact')
    ));

    $this->data = $model->modelData->build();
    $this->setData('profile',$person->modelData->build(true));
    $this->setData('profileImageUrl',$person->getProfileImageUrl());
    $this->setData('experienceDetailUrl',$url->setAndParseUrl('experience/profile/{id}',array('id' => $person->personExperience->id)));

    return $this->view('pages.freelance.detail');

  }

  public function manage() {

    $model = Service::loadModel('Freelance');

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $model->paginator->myData();
    $model->paginator->criteria(array(
      'order' => array(
        array('created_at','DESC')
      )
    ));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('freelance/list');
    $model->paginator->setUrl('freelance/detail/{id}','detailUrl');
    $model->paginator->setUrl('person/freelance/edit/{id}','editUrl');
    $model->paginator->setUrl('person/freelance/delete/{id}','deleteUrl');

    $this->data = $model->paginator->build();

    return $this->view('pages.freelance.manage');

  }

  public function add() {

    $model = Service::loadModel('Freelance');

    $model->formHelper->loadFieldData('FreelanceType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'freelanceTypes'
    ));

    $this->data = $model->formHelper->build();

    return $this->view('pages.freelance.form.freelance_add');

  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Freelance');

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ลงประกาศแล้ว','success');
      return Redirect::to('freelance/detail'.$model->id);
    }else{
      return Redirect::back();
    }

  }

  public function edit() {

    $model = Service::loadModel('Freelance')->find($this->param['id']);

    $model->formHelper->loadData(array(
      'models' => array('Image','Tagging'),
      'json' => array('Image','Tagging')
    ));

    $model->formHelper->loadFieldData('FreelanceType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'freelanceTypes'
    ));

    $this->data = $model->formHelper->build();

    return $this->view('pages.freelance.form.freelance/edit');
  }

  public function editingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Freelance')->find($this->param['id']);
    
    if($model->fill($request->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('person/freelance');
    }else{
      return Redirect::back();
    }

  }

  public function delete() {

    $model = Service::loadModel('Freelance')->find($this->param['id']);

    if($model->delete()) {
      MessageHelper::display('ข้อมูลถูกลบแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถลบข้อมูลได้','error');
    }

    return Redirect::to('person/freelance');

  }

}
