<?php

namespace App\Models;

use App\library\date;

class PersonInternship extends Model
{
  protected $table = 'person_internships';
  protected $fillable = ['created_by','company','description'];
  protected $modelRelations = array('PersonExperienceDetail');

  public $formHelper = true;

  protected $validation = array(
    'rules' => array(
      'company' => 'required|max:255'
    ),
    'messages' => array(
      'company.required' => 'บริษัทหรือสถานที่ฝึกงานห้ามว่าง'
    )
  );

  public function fill(array $attributes) {

    if(!empty($attributes)) {

      $date = new Date;
      $attributes['PersonExperienceDetail'] = $date->setPeriodData($attributes);
      unset($attributes['date_start']);
      unset($attributes['date_end']);
      unset($attributes['current']);

      $attributes['PersonExperienceDetail']['experience_type_id'] = 2;
      
    }

    return parent::fill($attributes);

  }

  public function buildModelData() {
    return array(
      'company' => $this->company,
      'description' => $this->description
    );
  }

}
