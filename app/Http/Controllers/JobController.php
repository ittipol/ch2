<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
use App\library\url;
use App\library\date;
use App\library\notificationHelper;
use Redirect;

class JobController extends Controller
{
  public function __construct() { 
    parent::__construct();
  }

  public function listView() {

    $model = Service::loadModel('Job');
    
    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $model->paginator->criteria(array(
      'order' => array('create_at','DESC')
    ));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('job/list');
    $model->paginator->setUrl('job/detail/{id}','detailUrl');

    $this->data = $model->paginator->build();

    return $this->view('pages.job.list');
  }

  public function detail() {

    $url = new Url;

    $model = Service::loadModel('Job')->find($this->param['id']);

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

    $this->setData('shop',$shop->modelData->build(true));
    $this->setData('shopImageUrl',$shop->getProfileImageUrl());
    $this->setData('shopCoverUrl',$shop->getCoverUrl());
    $this->setData('shopUrl','shop/'.$slug);
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

    return $this->view('pages.job.detail');

  }

  public function add() {

    $model = Service::loadModel('Job');

    $model->formHelper->loadFieldData('EmploymentType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'employmentTypes'
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
      return Redirect::to('shop/'.$request->shopSlug.'/job');
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
      return Redirect::to('shop/'.request()->shopSlug.'/job');
    }else{
      return Redirect::back();
    }

  }

  public function apply() {

    $model = Service::loadModel('PersonApplyJob');

    $exist = $model->where(array(
      array('person_id','=',session()->get('Person.id')),
      array('job_id','=',$this->param['id'])
    ))->exists();

    if($exist) {
      MessageHelper::display('สมัครงานนี้แล้ว','info');
      return Redirect::to('job/detail/'.$this->param['id']);
    }

    $jobModel = Service::loadModel('Job')->find($this->param['id']);

    if(empty($jobModel)) {
      $this->error = array(
        'message' => 'ไม่พบงานนี้'
      );
      return $this->error();
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

    return $this->view('pages.job.form.job_apply');

  }

  public function applyingSubmit() {

    $model = Service::loadModel('PersonApplyJob');

    $exist = $model->where(array(
      array('person_id','=',session()->get('Person.id')),
      array('job_id','=',$this->param['id'])
    ))->exists();

    if($exist) {
      MessageHelper::display('สมัครงานนี้แล้ว','info');
      return Redirect::to('job/detail/'.$this->param['id']);
    }

    $shopToModel = Service::loadModel('ShopRelateTo')
    ->select('shop_id')
    ->where(array(
      array('model','like','Job'),
      array('model_id','=',$this->param['id'])
    ))
    ->first();

    request()->request->add(['job_id' => $this->param['id']]);
    request()->request->add(['shop_id' => $shopToModel->shop_id]);

    if($model->fill(request()->all())->save()) {

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

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $model->paginator->disableGetImage();
    $model->paginator->criteria(array(
      'conditions' => array(
        array('shop_id','=',request()->get('shopId'))
      )
    ));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('shop/'.request()->shopSlug.'/job_applying_list');
    $model->paginator->setUrl('shop/'.request()->shopSlug.'/job_applying_detail/{id}','detailUrl');
    $model->paginator->setUrl('experience/detail/{person_id}','experienceDetailUrl');

    $this->data = $model->paginator->build();

    return $this->view('pages.job.job_applying_list');

  }

  public function jobApplyingDetail() {

    $url = new Url;

    $model = Service::loadModel('PersonApplyJob')->find($this->param['id']);

    if(empty($model)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศนี้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    $person = $model->person;

    $person->modelData->loadData(array(
      'models' => array('Address','Contact')
    ));

    // relate to branches
    $total = Service::loadModel('relateToBranch')
    ->where(array(
      array('model','like','Job'),
      array('model_id','=',$model->job_id)
    ))
    ->count();

    // Get branch
    $branches = Service::loadModel('JobApplyToBranch')
    ->where('person_apply_job_id','=',$this->param['id'])
    ->get();

    $_branches = array();
    foreach ($branches as $branch) {
      $_branches[] = $branch->branch->name;
    }

    $this->data = $person->personExperience->getPersonExperience();
    $this->setData('jobName',$model->job->name);
    $this->setData('jobApply',$model->modelData->build(true));
    $this->setData('profile',$person->modelData->build(true));
    $this->setData('profileImageUrl',$person->getProfileImageUrl('xsm'));
    $this->setData('hasBranch',!empty($total) ? true : false);
    $this->setData('branches',$_branches);
    $this->setData('messagePostUrl',$url->setAndParseUrl('shop/{shopSlug}/job_applying_message/new/{id}',array('shopSlug'=>$this->param['shopSlug'],'id'=>$model->id)));

    return $this->view('pages.job.job_applying_detail');

  }

  public function accountJobApplyingDetail() {

    $url = new Url;
    $date = new Date;

    $model = Service::loadModel('PersonApplyJob')->find($this->param['id']);

    if(empty($model) || ($model->person_id != session()->get('Person.id'))) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศนี้ หรือข้อมูลนี้อาจถูกลบแล้ว'
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

    $messages = $model->getRelatedData('Message');

    $_messages = array();
    foreach ($messages as $message) {
      $_messages[] = $message->buildModelData();
    }
dd($_messages);
    $this->setData('shopName',$model->shop->name);
    $this->setData('shopUrl',$url->setAndParseUrl('shop/{shopSlug}',array('shopSlug'=>$slug)));
    $this->setData('jobName',$model->job->name);
    $this->setData('jobUrl',$url->setAndParseUrl('job/detail/{id}',array('id'=>$model->job->id)));
    $this->setData('createdDate',$date->covertDateTimeToSting($model->created_at->format('Y-m-d H:i:s')));
    $this->setData('messages',$_messages);
    
    return $this->view('pages.job.account_job_applying_detail');

  }

  public function jobApplyingMessageAdd() {
    
    $model = Service::loadModel('Message');

    $this->data = $model->formHelper->build();

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

    return $this->view('pages.message.form.message_add');

  }

  public function jobApplyingMessageAddingSubmit(CustomFormRequest $request) {

    $personApplyJob = Service::loadModel('PersonApplyJob')->find($this->param['id']);

    $messageHelper = new MessageHelper;
    $messageHelper->setModel($personApplyJob);
    $sender = $messageHelper->getSender($request->get('send_as'));
    $receiver = $messageHelper->getReceiver('person');

    $model = Service::loadModel('Message');
    $model->model = 'PersonApplyJob';
    $model->model_id = $this->param['id'];
    $model->sender = $sender['sender'];
    $model->sender_id = $sender['sender_id'];
    $model->receiver = $receiver['receiver'];
    $model->receiver_id = $receiver['receiver_id'];

    if($model->fill($request->all())->save()) {

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
      return Redirect::to('shop/'.$request->shopSlug.'/job_applying_detail/'.$this->param['id']);
    }else{
      return Redirect::back();
    }

  }

}
