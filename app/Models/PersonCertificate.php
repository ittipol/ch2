<?php

namespace App\Models;

use App\library\date;

class PersonCertificate extends Model
{
  protected $table = 'person_certificates';
  protected $fillable = ['created_by','name','description'];
  protected $modelRelations = array('PersonExperienceDetail','Image');

  public $formHelper = true;

  public $imageTypes = array(
    'photo' => array(
      'limit' => 5
    )
  );

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
    ),
    'messages' => array(
      'name.required' => 'ชื่อประกาศนียบัตรหรือหัวข้อการฝึกอบรมห้ามว่าง',
    )
  );

  public function fill(array $attributes) {

    if(!empty($attributes)) {

      $date = new Date;
      $attributes['PersonExperienceDetail'] = $date->setPeriodData($attributes);
      unset($attributes['date_start']);
      unset($attributes['date_end']);
      unset($attributes['current']);

      $attributes['PersonExperienceDetail']['experience_type_id'] = 5;

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
