<?php

namespace App\Models;

use App\library\date;

class PersonEducation extends Model
{
  protected $table = 'person_educations';
  protected $fillable = ['created_by','academy','description','graduated'];
  protected $modelRelations = array('PersonExperienceDetail');

  public $formHelper = true;

  protected $validation = array(
    'rules' => array(
      'academy' => 'required|max:255',
    ),
    'messages' => array(
      'academy.required' => 'สถานศึกษาห้ามว่าง',
    )
  );

  public function fill(array $attributes) {

    if(!empty($attributes)) {      

      if(empty($attributes['graduated'])) {
        $attributes['graduated'] = null;
      }

      $date = new Date;
      $attributes['PersonExperienceDetail'] = $date->setPeriodData($attributes);
      unset($attributes['date_start']);
      unset($attributes['date_end']);
      unset($attributes['current']);

      $attributes['PersonExperienceDetail']['experience_type_id'] = 3;
      
    }

    return parent::fill($attributes);

  }

  public function buildModelData() {

    $message = array();
    if(!empty($this->graduated)) {
      $message[] = 'จบการศึกษา';
    }

    if(!empty($this->academy)) {
      $message[] = $this->academy;
    }

    $message = implode(' ที่ ', $message);

    return array(
      'academy' => $this->academy,
      'description' => $this->description,
      // 'graduated' => $this->graduated,
      'message' => $message
    );
  }

}