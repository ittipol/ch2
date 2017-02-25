<?php

namespace App\Models;

class DataAccessPermission extends Model
{
  protected $table = 'data_access_permissions';
  protected $fillable = ['model','model_id','page_level_id','owner','owner_id'];
  public $timestamps  = false;

  public function item() {
    return $this->hasOne('App\Models\Item','id','model_id');
  }

  public function __saveRelatedData($model,$options = array()) {

    $behavior = $model->getBehavior('DataAccessPermission');

    if(empty($behavior['owner'])) {
      return false;
    }

    $accessLevel = new AccessLevel;

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

      $options['value']['owner'] = $behavior['owner'];

      switch ($behavior['owner']) {
        case 'Person':
            $options['value']['owner_id'] = session()->get('Person.id');
          break;
        
        case 'Shop':

          // $shopId = ShopRelateTo::select('shop_id')
          // ->where([
          //   ['model','like',$model->modelName],
          //   ['model_id','=',$model->id]
          // ])->first()->shop_id;

          $shopId = Slug::select('model_id')
          ->where([
            ['slug','like',$this->param['shopSlug']],
            ['model','like','Shop']
          ])->first()->model_id;

          $options['value']['owner_id'] = $shopId;
          break;
      }

      $level = $accessLevel->getIdByLevel($behavior['defaultAccessLevel']);
      if(!empty($behavior['value']['page_level_id'])) {
        $level = $behavior['value']['page_level_id'];
      }
      
      $options['value']['page_level_id'] = $level;

      return $this->fill($model->includeModelAndModelId($options['value']))->save();
    }

  }

}
