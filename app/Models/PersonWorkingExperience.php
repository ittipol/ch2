<?php

namespace App\Models;

use App\library\date;

class PersonWorkingExperience extends Model
{
  protected $table = 'person_working_experiences';
  protected $fillable = ['created_by','company','position','description'];
  protected $modelRelations = array('PersonExperienceDetail');

  public $formHelper = true;

  protected $validation = array(
    'rules' => array(
      'company' => 'required|max:255',
      'position' => 'required|max:255',
    ),
    'messages' => array(
      'company.required' => 'บริษัทหรือสถานที่ทำงานห้ามว่าง',
      'position.required' => 'ตำแหน่งห้ามว่าง',
    )
  );

  public function fill(array $attributes) {

    if(!empty($attributes)) {

      $date = new Date;
      $attributes['PersonExperienceDetail'] = $date->setPeriodData($attributes);
      unset($attributes['date_start']);
      unset($attributes['date_end']);
      unset($attributes['current']);

      $attributes['PersonExperienceDetail']['experience_type_id'] = 1;
     
    }

    return parent::fill($attributes);

  }

  public function buildModelData() {

    $message = array();

    if(!empty($this->position)) {
      $message[] = $this->position;
    }

    if(!empty($this->company)) {
      $message[] = $this->company;
    }

    $message = implode(' ที่ ', $message);

    return array(
      'company' => $this->company,
      'position' => $this->position,
      'description' => $this->description,
      'message' => $message
    );
  }

}
