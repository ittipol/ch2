<?php

namespace App\Models;

class PersonProject extends Model
{
  protected $table = 'person_projects';
  protected $fillable = ['person_id','name','description'];
  protected $modelRelations = array('PersonExperienceDetail');

  public $formHelper = true;

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
    ),
    'messages' => array(
      'name.required' => 'ห้วข้อโปรเจคห้ามว่าง',
    )
  );

  public function fill(array $attributes) {

    if(!empty($attributes)) {

      $personExperienceDetail = new PersonExperienceDetail;
      $attributes['PersonExperienceDetail'] = $personExperienceDetail->setPeriodData($attributes);
      unset($attributes['date_start']);
      unset($attributes['date_end']);
      unset($attributes['current']);

      $attributes['PersonExperienceDetail']['experience_type_id'] = 4;
      
    }

    return parent::fill($attributes);

  }

  public function buildModelData() {

    return array(
      'name' => $this->name,
      'description' => $this->description
    );

  }
}
