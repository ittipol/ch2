<?php

namespace App\Models;

class DataAccessPermission extends Model
{
  protected $table = 'data_access_permissions';
  protected $fillable = ['model','model_id','page_level_id'];
  public $timestamps  = false;

  public function item() {
    return $this->hasOne('App\Models\Item','id','model_id');
  }

  public function __saveRelatedData($model,$options = array()) {

    $permission = $model->getModelRelationData('DataAccessPermission',
      array(
        'first' => true
      )
    );

    if(!empty($permission)){
      return $permission
      ->fill($options['value'])
      ->save();
    }else{

      if(empty($options['value']['page_level_id'])) {
        $options['value']['page_level_id'] = 4;
      }
      
      return $this->fill($model->includeModelAndModelId($options['value']))->save();
    }

  }

}
