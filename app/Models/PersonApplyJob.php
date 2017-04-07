<?php

namespace App\Models;

use App\library\cache;
use App\library\string;
use App\library\date;

class PersonApplyJob extends Model
{
  protected $table = 'person_apply_jobs';
  protected $fillable = ['person_id','job_id','shop_id','message','approved'];
  protected $modelRelations = array('JobApplyToBranch');

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  public function person() {
    return $this->hasOne('App\Models\Person','id','person_id');
  }

  public function job() {
    return $this->hasOne('App\Models\Job','id','job_id');
  }

  public function shop() {
    return $this->hasOne('App\Models\Shop','id','shop_id');
  }

  public function buildModelData() {

    return array(
      'message' => $this->message
    );

  }

  public function buildPaginationData() {

    $image = new Image;
    $cache = new Cache;
    $string = new String;
    $date = new Date;

    $personExperience = $this->person->personExperience;

    $imageUrl = '/images/common/no-img.png';
    if(!empty($this->person->profile_image_id)) {
      $image = $image
      ->select(array('model','model_id','filename','image_type_id'))
      ->find($this->person->profile_image_id);

      $imageUrl = $cache->getCacheImageUrl($image,'list');
    }

    $jobImage = $this->job->getRelatedData('Image',array(
      'first' => true
    ));

    $jobImageUrl = '/images/common/no-img.png';
    if(!empty($jobImage)) {
      $jobImageUrl = $cache->getCacheImageUrl($jobImage,'list');
    }

    return array(
      'personName' => $this->person->name,
      '_imageUrl' => $imageUrl,
      '_jobNameShort' => $string->subString($this->job->name,45),
      '_jobImageUrl' => $jobImageUrl,
      'createdDate' => $date->covertDateTimeToSting($this->created_at->format('Y-m-d H:i:s')),
    );

  }

  public function setUpdatedAt($value) {}
}
