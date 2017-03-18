<?php

namespace App\Models;

use App\library\date;

class PersonExperienceDetail extends Model
{
  protected $table = 'person_experience_details';
  protected $fillable = ['person_experience_id','model','model_id','person_id','experience_type_id','start_year','start_month','start_day','end_year','end_month','end_day','current'];

  public function personWorkingExperience() {
    return $this->hasOne('App\Models\PersonWorkingExperience','id','model_id');
  }

  public function personInternship() {
    return $this->hasOne('App\Models\PersonInternship','id','model_id');
  }

  public function personEducation() {
    return $this->hasOne('App\Models\PersonEducation','id','model_id');
  }

  public function personProject() {
    return $this->hasOne('App\Models\PersonProject','id','model_id');
  }

  public function personCertificate() {
    return $this->hasOne('App\Models\PersonCertificate','id','model_id');
  }

  public static function boot() {

    parent::boot();

    // before saving
    PersonExperienceDetail::saving(function($model){

      if(!$model->exists){

        $personExperience = new PersonExperience;
        $personExperience = $personExperience
        ->select(array('id'))
        ->where('person_id','=',session()->get('Person.id'))
        ->first();

        $model->person_experience_id = $personExperience->id;

      }

    });

  }

  public function __saveRelatedData($model,$options = array()) {
    $personExperienceDetail = $model->getRelatedData('PersonExperienceDetail',
      array(
        'first' => true
      )
    );

    if(!empty($personExperienceDetail)){
      return $personExperienceDetail
      ->fill($options['value'])
      ->save();
    }else{
      return $this->fill($model->includeModelAndModelId($options['value']))->save();
    }

  }

  public function getPeriod() {

    $date = new Date;

    $period = array();
    $startDate = array();
    $endDate = array();

    if(!empty($this->start_day)) {
      $startDate[] = $this->start_day;
    }

    if(!empty($this->start_month)) {
      $startDate[] = $date->getMonthName($this->start_month);
    }

    if(!empty($this->start_year)) {
      $startDate[] = $this->start_year+543;
    }

    if(!empty($startDate)) {
      $period[] = implode(' ', $startDate);
    }

    if(!empty($this->current) && $this->current) {
      $period[] = 'ถึงปัจจุบัน';
    }else{
      if(!empty($this->end_day)) {
        $endDate[] = $this->end_day;
      }

      if(!empty($this->end_month)) {
        $endDate[] = $date->getMonthName($this->end_month);
      }

      if(!empty($this->end_year)) {
        $endDate[] = $this->end_year+543;
      }

      if(!empty($startDate)) {
        $period[] = implode(' ', $endDate);
      }

    }

    return implode(' - ', $period);

  }

  public function getExperienceDetails($personExperienceId) {

    $data = array();

    $models = array(
      'PersonWorkingExperience' => 'working',
      'PersonInternship' => 'internship',
      'PersonEducation' => 'education',
      'PersonProject' => 'project',
      'PersonCertificate' => 'certificate'
    );

    foreach ($models as $_model => $alias) {
      $experienceDetails = $this
      ->orderBy('start_year','DESC')
      ->orderBy('start_month','DESC')
      ->orderBy('start_day','DESC')
      ->select(array('model','model_id','start_year','start_month','start_day','end_year','end_month','end_day','current'))
      ->where(array(
        array('person_experience_id','=',$personExperienceId),
        array('model','like',$_model)
      ))
      ->get();

      $details = array();
      foreach ($experienceDetails as $experienceDetail) {
        
        $__model = $experienceDetail->{lcfirst($experienceDetail->model)};

        if(empty($__model)) {
          continue;
        }

        $details[] = array_merge(
          $__model->buildModelData(),
          array(
            'peroid' => $experienceDetail->getPeriod()
          )
        );

      }

      $data[$_model] = $details;

    }

    return $data;

  }

}
