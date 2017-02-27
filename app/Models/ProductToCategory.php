<?php

namespace App\Models;

class ProductToCategory extends Model
{
  protected $table = 'product_to_categories';
  protected $fillable = ['product_id','category_id'];
  public $timestamps  = false;

  public function category() {
    return $this->hasOne('App\Models\Category','id','category_id');
  }

  public function __saveRelatedData($model,$options = array()) {

    if(!empty($options['value']['category_id'])) {

      if($model->state == 'update') {
        $this->where('product_id','=',$model->id)->delete();
      }
      
      return $this->fill(array(
        'product_id' => $model->id,
        'category_id' => $options['value']['category_id']
      ))->save();

    }
    
  }
}
