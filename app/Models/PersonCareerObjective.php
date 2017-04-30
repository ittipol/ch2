<?php

namespace App\Models;

class PersonCareerObjective extends Model
{
  protected $table = 'person_career_objectives';
  protected $fillable = ['person_experience_id','created_by','career_objective'];

  public $formHelper = true;
}
