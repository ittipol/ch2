<?php

namespace App\Models;

class PersonSkill extends Model
{
  protected $table = 'person_skills';
  protected $fillable = ['person_experience_id','created_by','skill'];

  public $formHelper = true;

  public function checkExistBySkill($skill) {
    return $this->where('skill','like',$skill)->exists();
  }
}
