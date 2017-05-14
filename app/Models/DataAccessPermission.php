<?php

namespace App\Models;

class DataAccessPermission extends Model
{
  protected $table = 'data_access_permissions';
  protected $fillable = ['model','model_id','access_level','owner','owner_id'];
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

    $permission = $model->getRelatedData('DataAccessPermission',
      array(
        'first' => true
      )
    );

    if(!empty($permission)){

      if(empty($options['value']['access_level'])) {
        return true;
      }

      return $permission
      ->fill(array(
        'access_level' => $options['value']['access_level']
      ))
      ->save();
    }else{

      $value['owner'] = $behavior['owner'];

      switch ($behavior['owner']) {
        case 'Person':
            $value['owner_id'] = session()->get('Person.id');
          break;
        
        case 'Shop':

          $shop = ShopRelateTo::select('shop_id')
          ->where([
            ['model','like',$model->modelName],
            ['model_id','=',$model->id]
          ]);

          $value['owner_id'] = null;
          if($shop->exists()) {
            $value['owner_id'] = $shop->first()->shop_id;
          }

          // $shopId = Slug::select('model_id')
          // ->where([
          //   ['slug','like',$this->param['shopSlug']],
          //   ['model','like','Shop']
          // ])->first()->model_id;
          break;
      }

      $level = $behavior['defaultAccessLevel'];
      if(!empty($options['value']['access_level'])) {
        $level = $options['value']['access_level'];
      }
      
      $value['access_level'] = $level;

      return $this->fill($model->includeModelAndModelId($value))->save();
    }

  }

}
