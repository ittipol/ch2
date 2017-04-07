<?php

namespace App\Models;

class PersonLanguageSkill extends Model
{
  protected $table = 'person_language_skills';
  protected $fillable = ['person_experience_id','person_id','language_id','language_skill_level_id'];

  public $formHelper = true;

  public function language() {
    return $this->hasOne('App\Models\Language','id','language_id');
  }

  public function languageSkillLevel() {
    return $this->hasOne('App\Models\LanguageSkillLevel','id','language_skill_level_id');
  }

  public function checkExistByLanguageId($languageId) {
    return $this->where('language_id','=',$languageId)->exists();
  }

}
