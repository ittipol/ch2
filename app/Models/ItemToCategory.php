<?php

namespace App\Models;

class ItemToCategory extends Model
{
  protected $table = 'item_to_categories';
  protected $fillable = ['item_id','item_category_id'];
  public $timestamps  = false;

  public function category() {
    return $this->hasOne('App\Models\ItemCategory','id','item_category_id');
  }

  public function __saveRelatedData($model,$options = array()) {

    if(!empty($options['value']['item_category_id'])) {

      if($model->state == 'update') {
        $this->where('item_id','=',$model->id)->delete();
      }
      
      return $this->fill(array(
        'item_id' => $model->id,
        'item_category_id' => $options['value']['item_category_id']
      ))->save();

    }
    
  }

}
