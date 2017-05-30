<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\date;
use App\library\messageHelper;
use App\library\filterHelper;
use App\library\notificationHelper;
use App\library\url;
use App\library\cache;
use Redirect;

class JobController extends Controller
{
  public function __construct() { 
    parent::__construct();
  }

  public function board() {

    $url = new Url;
    $cache = new Cache;

    $model = Service::loadModel('Job');

    $employmentTypes = Service::loadModel('EmploymentType')->all();

    $boards = array();
    foreach ($employmentTypes as $type) {

      $jobs = $model->where('employment_type_id','=',$type->id);

      $total = $jobs->count('id');

      $jobs = $jobs
      ->orderBy('created_at','desc')
      ->take(4);

      $_jobs = array();
      if($jobs->exists()) {

        foreach ($jobs->get() as $job) {

          $image = $job->getRelatedData('Image',array(
            'first' => true
          ));

          $imageUrl = '/images/common/no-img.png';
          if(!empty($image)) {
            $imageUrl = $cache->getCacheImageUrl($image,'list');
          }

          $_jobs['items'][] = array_merge($job->buildPaginationData(),array(
            '_imageUrl' => $imageUrl,
            'detailUrl' => $url->setAndParseUrl('job/detail/{id}',array('id'=>$job->id))
          ));
          
        }

        if($total > 4) {
          $_jobs['all'] = array(
            'title' => '+'.($total-4)
          );
        }

      }

      $boards[] = array(
        'typeName' => $type->name,
        'data' => $_jobs,
        'total' => $total,
        'itemBoardUrl' => $url->setAndParseUrl('job/{employment_type_id}',array('employment_type_id'=>$type->id)),
      );

    }

    $this->setData('boards',$boards);

    $this->setPageTitle('งานจากบริษัทและร้านค้า');

    return $this->view('pages.job.board');

  }

  public function listView() {

    $model = Service::loadModel('Job');
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

    $conditions[] = array('employment_type_id','=',$this->param['employment_type_id']);

    $model->paginator->criteria(array_merge(array(
      'conditions' => $conditions
    ),$order));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('job/'.$this->param['employment_type_id']);
    $model->paginator->setUrl('job/detail/{id}','detailUrl');
    $model->paginator->setQuery('sort',$sort);
    $model->paginator->setQuery('fq',$filters);

    $title = Service::loadModel('EmploymentType')->getTypeName($this->param['employment_type_id']);

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

    $this->setPageTitle($title.' - ประกาศงาน');
    
    return $this->view('pages.job.list');
  }

  public function shopJoblistView() {

    $model = Service::loadModel('Job');
    $filterHelper = new FilterHelper($model);

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
      array('shop_relate_to.model','like','Job'),
      array('shop_relate_to.shop_id','=',request()->get('shopId'))
    );

    $criteria = array();

    $criteria = array_merge($criteria,array(
      'joins' => array('shop_relate_to', 'shop_relate_to.model_id', '=', 'jobs.id')
    ));

    $criteria = array_merge($criteria,array(
      'conditions' => $conditions
    ));

    if(!empty($order)) {
      $criteria = array_merge($criteria,$order);
    }

    $model->paginator->criteria($criteria);
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('shop/'.request()->shopSlug.'/job');
    $model->paginator->setUrl('shop/'.request()->shopSlug.'/job/{id}','detailUrl');
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

    $this->setPageTitle('ประกาศงาน - '.request()->get('shop')->name);

    return $this->view('pages.job.shop_job_list');

  }

  public function detail() {

    $url = new Url;

    $model = Service::loadModel('Job')->find($this->param['id']);

    if(empty($model)) {
      $this->error = array(
        'message' => 'ไม่พบประกาศ'
      );
      return $this->error();
    }

    $model->modelData->loadData(array(
      'models' => array('Image'),
      'json' => array('Image')
    ));

    $shop = $model->getRelatedData('ShopRelateTo',array(
      'first' => true,
    ))->shop;

    $slug = $shop->getRelatedData('Slug',array(
      'first' => true,
    ))->slug;
    
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

    $branchLocations = array();
    $hasBranchLocation = false;
    // foreach ($branches as $branch) {

    //   $address = $branch->modelData->loadAddress();

    //   if(!empty($address)){

    //     $hasBranchLocation = true;

    //     $graphics = json_decode($address['_geographic'],true);
        
    //     $branchLocations[] = array(
    //       'id' => $branch->id,
    //       'address' => $branch->name,
    //       'latitude' => $graphics['latitude'],
    //       'longitude' => $graphics['longitude'],
    //       'detailUrl' => $url->setAndParseUrl('shop/{shopSlug}/branch/{id}',array(
    //         'shopSlug' => $slug,
    //         'id' => $branch->id
    //       ))
    //     );
    //   }
    // }

    $this->data = $model->modelData->build();
    $this->setData('shop',$shop->modelData->build(true));
    $this->setData('shopImageUrl',$shop->getProfileImageUrl());
    $this->setData('shopCoverUrl',$shop->getCoverUrl());
    $this->setData('shopUrl','shop/'.$slug);
    $this->setData('branchLocations',json_encode($branchLocations));
    $this->setData('hasBranchLocation',$hasBranchLocation);

    // Get person apply job
    $personApplyJob = Service::loadModel('PersonApplyJob')->where(array(
      array('created_by','=',session()->get('Person.id')),
      array('job_id','=',$this->param['id'])
    ));


    if($personApplyJob->exists()) {

      $personApplyJob = $personApplyJob->first();

      $this->setData('personApplyJob',$personApplyJob->buildModelData());

      if(($personApplyJob->job_applying_status_id == 4) || ($personApplyJob->job_applying_status_id == 5)) {
        $this->setData('jobApplyUrl',$url->setAndParseUrl('job/apply/{id}',array('id' => $this->param['id'])));
      }

    }else{
      $this->setData('jobApplyUrl',$url->setAndParseUrl('job/apply/{id}',array('id' => $this->param['id'])));
    }

    $this->setData('alreadyApply',$personApplyJob->exists());

    $this->setPageTitle($this->data['_modelData']['name'].' - งาน');
    $this->setPageImage($model->getImage('list'));
    $this->setPageDescription($model->getShortDescription());
    
    return $this->view('pages.job.detail');

  }

  public function shopJobDetail() {

    $url = new Url;

    $model = Service::loadModel('Job')->find($this->param['id']);

    if(empty($model)) {
      $this->error = array(
        'message' => 'ไม่พบประกาศ'
      );
      return $this->error();
    }

    $model->modelData->loadData(array(
      'models' => array('Image','Tagging'),
      'json' => array('Image')
    ));

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

    $branchLocations = array();
    $hasBranchLocation = false;
    // foreach ($branches as $branch) {

    //   $address = $branch->modelData->loadAddress();

    //   if(!empty($address)){

    //     $hasBranchLocation = true;

    //     $graphics = json_decode($address['_geographic'],true);
        
    //     $branchLocations[] = array(
    //       'id' => $branch->id,
    //       'address' => $branch->name,
    //       'latitude' => $graphics['latitude'],
    //       'longitude' => $graphics['longitude'],
    //       'detailUrl' => $url->setAndParseUrl('shop/{shopSlug}/branch/{id}',array(
    //         'shopSlug' => request()->shopSlug,
    //         'id' => $branch->id
    //       ))
    //     );
    //   }

    // }

    $shop = request()->get('shop');

    $this->data = $model->modelData->build();
    $this->setData('shop',$shop->modelData->build(true));
    $this->setData('shopImageUrl',$shop->getProfileImageUrl());
    $this->setData('shopCoverUrl',$shop->getCoverUrl());
    // $this->setData('shopUrl',request()->get('shopUrl'));
    $this->setData('branchLocations',json_encode($branchLocations));
    $this->setData('hasBranchLocation',$hasBranchLocation);

    // Get person apply job
    $personApplyJob = Service::loadModel('PersonApplyJob')->where(array(
      array('created_by','=',session()->get('Person.id')),
      array('job_id','=',$this->param['id'])
    ));


    if($personApplyJob->exists()) {

      $personApplyJob = $personApplyJob->first();

      $this->setData('personApplyJob',$personApplyJob->buildModelData());

      if(($personApplyJob->job_applying_status_id == 4) || ($personApplyJob->job_applying_status_id == 5)) {
        $this->setData('jobApplyUrl',$url->setAndParseUrl('job/apply/{id}',array('id' => $this->param['id'])));
      }

    }else{
      $this->setData('jobApplyUrl',$url->setAndParseUrl('job/apply/{id}',array('id' => $this->param['id'])));
    }

    $this->setData('alreadyApply',$personApplyJob->exists());

    $this->setPageTitle($this->data['_modelData']['name'].' - งาน @ '.request()->get('shop')->name);
    $this->setPageImage($model->getImage('list'));
    $this->setPageDescription($model->getShortDescription());
    
    return $this->view('pages.job.shop_job_detail');

  }

  public function add() {

    $model = Service::loadModel('Job');

    $model->formHelper->loadFieldData('EmploymentType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'employmentTypes'
    ));

    $model->formHelper->loadFieldData('CareerType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'careerTypes'
    ));

    $model->formHelper->setData('branches',request()->get('shop')->getRelatedShopData('Branch'));

    $this->data = $model->formHelper->build();

    return $this->view('pages.job.form.job_add');
  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Job');

    $request->request->add(['ShopRelateTo' => array('shop_id' => request()->get('shopId'))]);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ลงประกาศแล้ว','success');
      return Redirect::to('shop/'.$request->shopSlug.'/manage/job');
    }else{
      return Redirect::back();
    }

  }

  public function edit() {

    $model = Service::loadModel('Job')->find($this->param['id']);

    $model->formHelper->loadData(array(
      'models' => array('Image','Tagging'),
      'json' => array('Image','Tagging')
    ));

    $model->formHelper->loadFieldData('EmploymentType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'employmentTypes'
    ));

    $model->formHelper->loadFieldData('CareerType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'careerTypes'
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

    // Get Selected Branch
    $model->formHelper->setFormData('RelateToBranch',$branches);
    // Get All branches in shop
    $model->formHelper->setData('branches',request()->get('shop')->getRelatedShopData('Branch'));

    $this->data = $model->formHelper->build();

    return $this->view('pages.job.form.job_edit');
  }

  public function editingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Job')->find($this->param['id']);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/manage/job');
    }else{
      return Redirect::back();
    }

  }

  public function apply() {

    $jobModel = Service::loadModel('Job')->find($this->param['id']);

    if(empty($jobModel)) {
      $this->error = array(
        'message' => 'ไม่พบข้อมูลตำแหน่งงาน ข้อมูลอาจถูกลบไปแล้ว'
      );
      return $this->error();
    }    

    $model = Service::loadModel('PersonApplyJob');

    $_model = $model->where(array(
      array('created_by','=',session()->get('Person.id')),
      array('job_id','=',$this->param['id'])
    ));

    $exist = $_model->exists();

    if($exist) {

      $model = $_model->first();

      if(($model->job_applying_status_id != 4) && ($model->job_applying_status_id != 5)) {
        MessageHelper::display('สมัครงานนี้แล้ว','info');
        return Redirect::to('job/detail/'.$this->param['id']);
      }

      $relateToBranch = $model->getRelatedData('JobApplyToBranch');

      $branches = array();
      if(!empty($relateToBranch)) {
        foreach ($relateToBranch as $value) {
          $branches['branch_id'][] = $value->branch->id;
        }
      }

      $model->formHelper->setFormData('JobApplyToBranch',$branches);

    }

    $branchIds = $jobModel->getRelatedData('RelateToBranch',array(
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

    $_branches = array();
    foreach ($branches as $branch) {
      $_branches[$branch->id] = $branch->name;
    }

    $shopToModel = Service::loadModel('ShopRelateTo')
    ->select('shop_id')
    ->where(array(
      array('model','like','Job'),
      array('model_id','=',$this->param['id'])
    ))
    ->first();

    $this->data = $model->formHelper->build();

    $this->setData('shopName',$shopToModel->shop->name);
    $this->setData('jobName',$jobModel->name);
    $this->setData('branches',$_branches);

    if($exist) {

      $attachedFiles = $model->getRelatedData('AttachedFile');

      $_attachedFiles = array();
      if(!empty($attachedFiles)) {
        foreach ($attachedFiles as $file) {
          $_attachedFiles[] = $file->buildModelData();
        }
      }

      $this->setData('attachedFiles',$_attachedFiles);

      return $this->view('pages.job.form.job_apply_edit');
    }

    return $this->view('pages.job.form.job_apply');

  }

  public function applyingSubmit() {

    $model = Service::loadModel('PersonApplyJob');

    $_model = $model->where(array(
      array('created_by','=',session()->get('Person.id')),
      array('job_id','=',$this->param['id'])
    ));

    $jobApplyingStatus = Service::loadModel('JobApplyingStatus')->getIdByAlias('job-applying');

    if($_model->exists()) {

      $model = $_model->first();

      if(($model->job_applying_status_id != 4) && ($model->job_applying_status_id != 5)) {
        MessageHelper::display('สมัครงานนี้แล้ว','info');
        return Redirect::to('job/detail/'.$this->param['id']);
      }

      $model->times = $model->times + 1;

    }else{

      $shopToModel = Service::loadModel('ShopRelateTo')
      ->select('shop_id')
      ->where(array(
        array('model','like','Job'),
        array('model_id','=',$this->param['id'])
      ))
      ->first();

      request()->request->add(['shop_id' => $shopToModel->shop_id]);

      $model->job_id = $this->param['id'];
      $model->times = 1;

    }

    $model->job_applying_status_id = $jobApplyingStatus;

    if($model->fill(request()->all())->save()) {

      Service::loadModel('JobApplyingHistory')->fill(array(
        'job_id' => $model->job_id,
        'job_applying_status_id' => $jobApplyingStatus,
        'times' => $model->times
      ))->save();

      $notificationHelper = new NotificationHelper;
      $notificationHelper->setModel($model);
      $notificationHelper->create('job-apply');

      MessageHelper::display('สมัครงานนี้เรียบร้อยแล้ว','success');
      return Redirect::to('job/detail/'.$this->param['id']);
    }else{
      return Redirect::back();
    }

  }

  public function jobApplyingList() {

    $model = Service::loadModel('PersonApplyJob');
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

    $conditions[] = array('shop_id','=',request()->get('shopId'));

    $model->paginator->criteria(array_merge(array(
      'conditions' => $conditions
    ),$order));
    $model->paginator->disableGetImage();
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('shop/'.request()->shopSlug.'/job_applying');
    $model->paginator->setUrl('shop/'.request()->shopSlug.'/job_applying/detail/{id}','detailUrl');
    $model->paginator->setUrl('experience/detail/{created_by}','experienceDetailUrl');
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

    return $this->view('pages.job.job_applying_list');

  }

  public function jobApplyingDetail() {

    $url = new Url;

    $model = Service::loadModel('PersonApplyJob')->find($this->param['id']);

    if(empty($model)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศ หรือข้อมูลอาจถูกลบแล้ว'
      );
      return $this->error();
    }

    $person = $model->person;

    $person->modelData->loadData(array(
      'models' => array('Address','Contact')
    ));

    // // relate to branches
    // $total = Service::loadModel('relateToBranch')
    // ->where(array(
    //   array('model','like','Job'),
    //   array('model_id','=',$model->job_id)
    // ))
    // ->count();

    // // Get branch
    // $branches = Service::loadModel('JobApplyToBranch')
    // ->where('person_apply_job_id','=',$this->param['id'])
    // ->get();

    // $_branches = array();
    // foreach ($branches as $branch) {
    //   $_branches[] = $branch->branch->name;
    // }

    $attachedFiles = $model->getRelatedData('AttachedFile',array(
      'fileds' => array('id','filename','filesize')
    ));

    $_attachedFiles = array();
    if(!empty($attachedFiles)) {
      foreach ($attachedFiles as $file) {
        $_attachedFiles[] = $file->buildModelData();
      }
    }

    $messages = $model->getRelatedData('Message',array(
      'conditions' => array(
        array('parent_id','=',null)
      )
    ));

    $_messages = array();
    if(!empty($messages)) {
      foreach ($messages as $message) {
        $_messages[] = array_merge($message->buildModelData(),array(
          'replyUrl' => $url->setAndParseUrl('shop/{shopSlug}/job_applying/message_reply/{id}',array('shopSlug'=>$this->param['shopSlug'],'id'=>$message->id))
        ));
      }  
    }

    if(!empty($person->personExperience)) {
      $this->data = $person->personExperience->getPersonExperience();
      $this->setData('hasResume',true);
    }else{
      $this->setData('hasResume',false);
    }

    $this->setData('jobName',$model->job->name);
    $this->setData('personApplyJob',$model->modelData->build(true));
    $this->setData('messageFromApplicant',$model->getMessage());
    $this->setData('jobApplyHistory',$model->getJobApplyHistory(true));
    $this->setData('profile',$person->modelData->build(true));
    $this->setData('profileImageUrl',$person->getProfileImageUrl('xsm'));
    $this->setData('hasBranch',false);
    // $this->setData('hasBranch',!empty($total) ? true : false);
    // $this->setData('branches',$_branches);
    $this->setData('attachedFiles',$_attachedFiles);
    $this->setData('messages',$_messages);

    if($model->job_applying_status_id == 1) {
      $this->setData('jobApplyingAcceptUrl',$url->setAndParseUrl('shop/{shopSlug}/job_applying/accept/{id}',array('shopSlug'=>$this->param['shopSlug'],'id'=>$model->id)));
      $this->setData('jobApplyingCancelUrl',$url->setAndParseUrl('shop/{shopSlug}/job_applying/canceled/{id}',array('shopSlug'=>$this->param['shopSlug'],'id'=>$model->id)));
    }elseif($model->job_applying_status_id == 2) {
      $this->setData('jobApplyingPassedUrl',$url->setAndParseUrl('shop/{shopSlug}/job_applying/passed/{id}',array('shopSlug'=>$this->param['shopSlug'],'id'=>$model->id)));
      $this->setData('jobApplyingNotPassUrl',$url->setAndParseUrl('shop/{shopSlug}/job_applying/not_pass/{id}',array('shopSlug'=>$this->param['shopSlug'],'id'=>$model->id)));
      $this->setData('jobApplyingCancelUrl',$url->setAndParseUrl('shop/{shopSlug}/job_applying/canceled/{id}',array('shopSlug'=>$this->param['shopSlug'],'id'=>$model->id)));
    }elseif($model->job_applying_status_id == 3) {
      $this->setData('jobApplyingCancelUrl',$url->setAndParseUrl('shop/{shopSlug}/job_applying/canceled/{id}',array('shopSlug'=>$this->param['shopSlug'],'id'=>$model->id)));
    }

    $this->setData('newMessagePostUrl',$url->setAndParseUrl('shop/{shopSlug}/job_applying/new_message/{id}',array('shopSlug'=>$this->param['shopSlug'],'id'=>$model->id)));
    $this->setData('replyMessageUrl',$url->setAndParseUrl('shop/{shopSlug}/job_applying/message_reply',array('shopSlug'=>$this->param['shopSlug'],'id'=>$model->id)));

    // send as
    $this->getSendAs();

    return $this->view('pages.job.job_applying_detail');

  }

  public function jobApplyingAccept() {

    $model = Service::loadModel('PersonApplyJob')->find($this->param['id']);

    if($model->job_applying_status_id != 1) {
      $this->error = array(
        'message' => 'เกิดข้อผิดพลาดการทำงานไม่ถูกต้อง'
      );
      return $this->error();
    }

    $jobApplyingStatus = Service::loadModel('JobApplyingStatus')->getIdByAlias('job-applying-accept');

    $model->job_applying_status_id = $jobApplyingStatus;

    if($model->save()) {
      Service::loadModel('JobApplyingHistory')->fill(array(
        'job_id' => $model->job_id,
        'job_applying_status_id' => $jobApplyingStatus,
        // 'message' => request()->get('message'),
        'times' => $model->times
      ))->save();

      $notificationHelper = new NotificationHelper;
      $notificationHelper->setModel($model);
      $notificationHelper->create('job-applying-accept');

      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
    }else{
      MessageHelper::display('เกิดข้อผิดพลาด ไม่สามารถบันทึกข้อมูลได้','error');
    }

    return Redirect::to('shop/'.$this->param['shopSlug'].'/job_applying/detail/'.$model->id);

  }

  public function jobApplyingPassed() {

    $model = Service::loadModel('PersonApplyJob')->find($this->param['id']);

    if($model->job_applying_status_id != 2) {
      $this->error = array(
        'message' => 'เกิดข้อผิดพลาดการทำงานไม่ถูกต้อง'
      );
      return $this->error();
    }

    if(empty(request()->get('job_position_description'))) {
      return Redirect::back()->withErrors(['อัตราค่าจ้าง วันที่เริ่มทำงาน หรือข้อตกลงต่างๆ ของตำแหน่งงานนี้ห้ามว่าง']);
    }

    $jobApplyingStatus = Service::loadModel('JobApplyingStatus')->getIdByAlias('job-applying-passed');

    $model->job_applying_status_id = $jobApplyingStatus;
    $model->job_position_description = request()->get('job_position_description');

    if($model->save()) {
      Service::loadModel('JobApplyingHistory')->fill(array(
        'job_id' => $model->job_id,
        'job_applying_status_id' => $jobApplyingStatus,
        // 'message' => request()->get('message'),
        'times' => $model->times
      ))->save();

      $notificationHelper = new NotificationHelper;
      $notificationHelper->setModel($model);
      $notificationHelper->create('job-applying-passed');

      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
    }else{
      MessageHelper::display('เกิดข้อผิดพลาด ไม่สามารถบันทึกข้อมูลได้','error');
    }

    return Redirect::to('shop/'.$this->param['shopSlug'].'/job_applying/detail/'.$model->id);

  }

  public function jobApplyingNotPass() {
    
    $model = Service::loadModel('PersonApplyJob')->find($this->param['id']);

    if($model->job_applying_status_id != 2) {
      $this->error = array(
        'message' => 'เกิดข้อผิดพลาดการทำงานไม่ถูกต้อง'
      );
      return $this->error();
    }

    $jobApplyingStatus = Service::loadModel('JobApplyingStatus')->getIdByAlias('job-applying-not-pass');

    dd($jobApplyingStatus);

    $model->job_applying_status_id = $jobApplyingStatus;
    $model->save();

    Service::loadModel('JobApplyingHistory')->fill(array(
      'job_id' => $model->job_id,
      'job_applying_status_id' => $jobApplyingStatus,
      'message' => request()->get('message'),
      'times' => $model->times
    ))->save();

    $notificationHelper = new NotificationHelper;
    $notificationHelper->setModel($model);
    $notificationHelper->create('job-applying-not-pass');

    MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
    return Redirect::to('shop/'.$this->param['shopSlug'].'/job_applying/detail/'.$model->id);

  }

  public function jobApplyingCancel() {

    $model = Service::loadModel('PersonApplyJob')->find($this->param['id']);

    if(($model->job_applying_status_id != 1) && ($model->job_applying_status_id != 2) && ($model->job_applying_status_id != 3)) {
      $this->error = array(
        'message' => 'เกิดข้อผิดพลาดการทำงานไม่ถูกต้อง'
      );
      return $this->error();
    }

    $jobApplyingStatus = Service::loadModel('JobApplyingStatus')->getIdByAlias('job-applying-canceled');

    $model->job_applying_status_id = $jobApplyingStatus;
    $model->save();

    Service::loadModel('JobApplyingHistory')->fill(array(
      'job_id' => $model->job_id,
      'job_applying_status_id' => $jobApplyingStatus,
      'message' => request()->get('message'),
      'times' => $model->times
    ))->save();

    $notificationHelper = new NotificationHelper;
    $notificationHelper->setModel($model);
    $notificationHelper->create('job-applying-cancel');

    MessageHelper::display('ยกเลิกการสมัครแล้ว','success');
    return Redirect::to('shop/'.$this->param['shopSlug'].'/job_applying/detail/'.$model->id);

  }

  public function jobPositionAccept() {
    
    $model = Service::loadModel('PersonApplyJob')->find($this->param['id']);

    if($model->job_applying_status_id != 3) {
      $this->error = array(
        'message' => 'เกิดข้อผิดพลาดการทำงานไม่ถูกต้อง'
      );
      return $this->error();
    }

    $jobApplyingStatus = Service::loadModel('JobApplyingStatus')->getIdByAlias('job-position-accept');

    $model->job_applying_status_id = $jobApplyingStatus;
    $model->save();

    Service::loadModel('JobApplyingHistory')->fill(array(
      'job_id' => $model->job_id,
      'job_applying_status_id' => $jobApplyingStatus,
      'message' => request()->get('message'),
      'times' => $model->times
    ))->save();

    $notificationHelper = new NotificationHelper;
    $notificationHelper->setModel($model);
    $notificationHelper->create('job-position-accept');

    MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
    return Redirect::to('account/job_applying/'.$this->param['id']);

  }

  public function jobPositionDecline() {
    
    $model = Service::loadModel('PersonApplyJob')->find($this->param['id']);

    if($model->job_applying_status_id != 3) {
      $this->error = array(
        'message' => 'เกิดข้อผิดพลาดการทำงานไม่ถูกต้อง'
      );
      return $this->error();
    }
    
    $jobApplyingStatus = Service::loadModel('JobApplyingStatus')->getIdByAlias('job-position-decline');

    $model->job_applying_status_id = $jobApplyingStatus;
    $model->save();

    Service::loadModel('JobApplyingHistory')->fill(array(
      'job_id' => $model->job_id,
      'job_applying_status_id' => $jobApplyingStatus,
      'message' => request()->get('message'),
      'times' => $model->times
    ))->save();

    $notificationHelper = new NotificationHelper;
    $notificationHelper->setModel($model);
    $notificationHelper->create('job-position-decline');

    MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
    return Redirect::to('account/job_applying/'.$this->param['id']);
  }

  public function accountJobApplyingDetail() {

    $url = new Url;
    $date = new Date;

    $model = Service::loadModel('PersonApplyJob')->find($this->param['id']);

    if(empty($model) || ($model->created_by != session()->get('Person.id'))) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศ หรือข้อมูลอาจถูกลบแล้ว'
      );
      return $this->error();
    }

    $slug = Service::loadModel('Slug')
    ->where([
      ['model','=','Shop'],
      ['model_id','=',$model->shop_id]
    ])
    ->select('slug')
    ->first();

    $messages = $model->getRelatedData('Message',array(
      'conditions' => array(
        array('parent_id','=',null)
      )
    ));

    $_messages = array();
    if(!empty($messages)) {
      foreach ($messages as $message) {
        $_messages[] = array_merge($message->buildModelData(),array(
          'replyUrl' => $url->setAndParseUrl('account/job_applying/message_reply/{id}',array('id'=>$message->id))
        ));
      }
    }

    $this->setData('personApplyJob',$model->modelData->build(true));
    $this->setData('jobPositionDescription',$model->getJobPositionDescription());
    // $this->setData('jobApplyHistory',$model->getJobApplyHistory(true));
    $this->setData('shopName',$model->shop->name);
    $this->setData('shopUrl',$url->setAndParseUrl('shop/{shopSlug}',array('shopSlug'=>$slug)));
    $this->setData('jobName',$model->job->name);
    $this->setData('jobUrl',$url->setAndParseUrl('job/detail/{id}',array('id'=>$model->job->id)));
    $this->setData('createdDate',$date->covertDateTimeToSting($model->created_at->format('Y-m-d H:i:s')));
    $this->setData('messages',$_messages);

    if($model->job_applying_status_id == 3) {
      $this->setData('jobPositionAcceptUrl',$url->setAndParseUrl('account/job_applying/job_position_accept/{id}',array('id'=>$model->id)));
      $this->setData('jobPositionDeclineUrl',$url->setAndParseUrl('account/job_applying/job_position_decline/{id}',array('id'=>$model->id)));
    }elseif(($model->job_applying_status_id == 4) || ($model->job_applying_status_id == 5)) {
      $this->setData('jobApplyUrl',$url->setAndParseUrl('job/apply/{id}',array('id' => $model->job_id)));
    }

    $this->setData('newMessagePostUrl',$url->setAndParseUrl('account/job_applying/new_message/{id}',array('id'=>$model->id)));
    $this->setData('replyMessageUrl',$url->setAndParseUrl('account/job_applying/message_reply/{id}',array('id'=>$model->id)));
    
    return $this->view('pages.job.account_job_applying_detail');

  }

  public function jobApplyingMessageSend(CustomFormRequest $request) {

    $personApplyJob = Service::loadModel('PersonApplyJob')->find($this->param['id']);

    $messageHelper = new MessageHelper;
    $messageHelper->setModel($personApplyJob);

    if(!empty($this->param['shopSlug'])) {
      $sender = $messageHelper->getSender($request->get('send_as'));
      $receiver = $messageHelper->getReceiver('person');
    }else {
      $sender = $messageHelper->getSender();
      $receiver = $messageHelper->getReceiver('shop');
    }

    $model = Service::loadModel('Message');
    $model->model = 'PersonApplyJob';
    $model->model_id = $this->param['id'];
    $model->sender = $sender['sender'];
    $model->sender_id = $sender['sender_id'];
    $model->receiver = $receiver['receiver'];
    $model->receiver_id = $receiver['receiver_id'];

    if($model->fill($request->all())->save()) {

      if(!empty($this->param['shopSlug'])) {

        $options = array();
        if($request->get('send_as') == 'shop') {
          $options = array(
            'sender' => array(
              'model' => 'Shop',
              'id' => request()->get('shopId')
            )
          );
        }

        $notificationHelper = new NotificationHelper;
        $notificationHelper->setModel($model);
        $notificationHelper->create('job-applying-message-send-to-person',$options);

        MessageHelper::display('ข้อความถูกส่งแล้ว','success');
        return Redirect::to('shop/'.$request->shopSlug.'/job_applying/detail/'.$this->param['id']);

      }else{
        $notificationHelper = new NotificationHelper;
        $notificationHelper->setModel($model);
        $notificationHelper->create('job-applying-message-reply-send-to-shop');

        MessageHelper::display('ข้อความถูกส่งแล้ว','success');
        return Redirect::to('account/job_applying/'.$this->param['id']);

      }

    }else{
      return Redirect::back();
    }

  }

  public function jobApplyingMessageReplySend(CustomFormRequest $request) {

    $message = Service::loadModel('Message')->find($request->get('id'));

    if(empty($message) || !$message->hasPermission() || !$message->isTopParent()) {
      $this->error = array(
        'message' => 'ไม่พบข้อความ หรือไม่สามารถตอบกลับข้อความนี้ได้'
      );
      return $this->error();
    }

    $personApplyJob = Service::loadModel($message->model)->find($message->model_id);

    $messageHelper = new MessageHelper;
    $messageHelper->setModel($personApplyJob);

    if(!empty($this->param['shopSlug'])) {
      $sender = $messageHelper->getSender($request->get('send_as'));
      $receiver = $messageHelper->getReceiver('person');
    }else {
      $sender = $messageHelper->getSender();
      $receiver = $messageHelper->getReceiver('shop');
    }

    $model = Service::loadModel('Message');
    $model->model = 'PersonApplyJob';
    $model->model_id = $message->model_id;
    $model->parent_id = $request->get('id');
    $model->sender = $sender['sender'];
    $model->sender_id = $sender['sender_id'];
    $model->receiver = $receiver['receiver'];
    $model->receiver_id = $receiver['receiver_id'];

    if($model->fill($request->all())->save()) {

      if(!empty($this->param['shopSlug'])) {
        $options = array();
        if($request->get('send_as') == 'shop') {
          $options = array(
            'sender' => array(
              'model' => 'Shop',
              'id' => request()->get('shopId')
            )
          );
        }

        $notificationHelper = new NotificationHelper;
        $notificationHelper->setModel($model);
        $notificationHelper->create('job-applying-message-reply-send-to-person',$options);

        MessageHelper::display('ข้อความตอบกลับถูกส่งแล้ว','success');
        return Redirect::to('shop/'.$request->shopSlug.'/job_applying/detail/'.$message->model_id);

      }else{
        $notificationHelper = new NotificationHelper;
        $notificationHelper->setModel($model);
        $notificationHelper->create('job-applying-message-reply-send-to-shop');

        MessageHelper::display('ข้อความตอบกลับถูกส่งแล้ว','success');
        return Redirect::to('account/job_applying/'.$message->model_id);

      }

    }else{
      return Redirect::back();
    }

  }

  private function getSendAs() {
    $sendAs = array(
      array(
        'text' => 'ส่งในนามบริษัทหรือร้านค้า',
        'value' => 'shop',
        'select' => true,
      ),
      array(
        'text' => 'ส่งในนานบุคคล',
        'value' => 'person',
        'select' => false,
      )
    );

    $this->setData('sendAs',$sendAs);
  }

  public function delete() {

    $model = Service::loadModel('Job')->find($this->param['id']);

    if($model->delete()) {
      MessageHelper::display('ข้อมูลถูกลบแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถลบข้อมูลนี้ได้','error');
    }

    return Redirect::to('shop/'.request()->shopSlug.'/manage/job');
  }

}
