<?php

namespace App\Models;

use App\library\cache;
use App\library\string;

class PersonApplyJob extends Model
{
  protected $table = 'person_apply_jobs';
  protected $fillable = ['person_id','job_id','shop_id','message'];
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

    $personExperience = $this->person->personExperience;

    $imageUrl = '/images/common/no-img.png';
    if(!empty($personExperience->profile_image_id)) {
      $image = $image
      ->select(array('model','model_id','filename','image_type_id'))
      ->find($personExperience->profile_image_id);

      $imageUrl = $cache->getCacheImageUrl($image,'list');

    }

    return array(
      // 'jobName' => $this->job->name,
      '_jobNameShort' => $string->subString($this->job->name,45),
      'personName' => $personExperience->name,
      '_imageUrl' => $imageUrl
    );

  }

  public function setUpdatedAt($value) {}
}
