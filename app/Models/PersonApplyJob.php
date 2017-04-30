<?php

namespace App\Models;

use App\library\cache;
use App\library\string;
use App\library\date;

class PersonApplyJob extends Model
{
  protected $table = 'person_apply_jobs';
  protected $fillable = ['created_by','job_id','shop_id','applicant_message','job_position_description','job_applying_status_id','times'];
  protected $modelRelations = array('JobApplyToBranch','AttachedFile');

  protected $modelRelationDataOption = array(
    'AttachedFile' => array(
      'deleteBeforeSaving' => true
    )
  );

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  protected $sortingFields = array(
    'title' => 'จัดเรียงตาม',
    'options' => array(
      array(
        'name' => 'วันที่เก่าที่สุดไปหาใหม่ที่สุด',
        'value' => 'created_at:asc'
      ),
      array(
        'name' => 'วันที่ใหม่ที่สุดไปหาเก่าที่สุด',
        'value' => 'created_at:desc'
      ),
    ),
    'default' => 'created_at:desc'
  );

  public function person() {
    return $this->hasOne('App\Models\Person','id','created_by');
  }

  public function job() {
    return $this->hasOne('App\Models\Job','id','job_id');
  }

  public function shop() {
    return $this->hasOne('App\Models\Shop','id','shop_id');
  }

  public function jobApplyingStatus() {
    return $this->hasOne('App\Models\JobApplyingStatus','id','job_applying_status_id');
  }

  public function getMessage() {
    return nl2br($this->applicant_message);
  }

  public function getJobPositionDescription() {
    return nl2br($this->job_position_description);
  }

  public function getJobApplyHistory($build = false) {
    $jobApplyHistory = JobApplyingHistory::where([
      ['job_id','=',$this->job_id],
      ['job_applying_status_id','=',$this->job_applying_status_id],
      ['times','=',$this->times]
    ]);
    
    if(!$jobApplyHistory->exists()) {
      return null;
    }

    if($build) {
      return $jobApplyHistory->first()->buildModelData();
    }

    return $jobApplyHistory->first();

  }

  public function buildModelData() {

    return array(
      'shopName' => $this->shop->name,
      'job_applying_status_id' => $this->job_applying_status_id,
      'jobApplyingStatusName' => $this->JobApplyingStatus->name
    );

  }

  public function buildPaginationData() {

    $image = new Image;
    $cache = new Cache;
    $string = new String;
    $date = new Date;

    $personExperience = $this->person->personExperience;

    $imageUrl = null;
    if(!empty($this->person->profile_image_id)) {
      $image = $image
      ->select(array('model','model_id','filename','image_type_id'))
      ->find($this->person->profile_image_id);

      $imageUrl = $cache->getCacheImageUrl($image,'list');
    }

    // $jobImage = $this->job->getRelatedData('Image',array(
    //   'first' => true
    // ));

    // $jobImageUrl = null;
    // if(!empty($jobImage)) {
    //   $jobImageUrl = $cache->getCacheImageUrl($jobImage,'list');
    // }

    return array(
      'personName' => $this->person->name,
      '_imageUrl' => $imageUrl,
      '_jobNameShort' => $string->truncString($this->job->name,45),
      '_jobImageUrl' => $this->job->getImage('list'),
      'createdDate' => $date->covertDateTimeToSting($this->created_at->format('Y-m-d H:i:s')),
    );

  }

  public function setUpdatedAt($value) {}
}
