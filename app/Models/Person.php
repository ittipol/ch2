<?php

namespace App\Models;

class Person extends Model
{
  protected $table = 'people';
  protected $fillable = ['user_id','name','gender','birth_date'];

  public function __construct() {  
    parent::__construct();
  }

  public function personExperience() {
    return $this->hasOne('App\Models\PersonExperience','person_id','id');
  }

  public function __saveRelatedData($model,$options = array()) {

    $options['value'] = array_merge($options['value'],array(
      'user_id' => $model->id
    ));

    return $this->fill($options['value'])->save();
  }

}
