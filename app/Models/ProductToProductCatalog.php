<?php

namespace App\Models;

class ProductToProductCatalog extends Model
{
  protected $table = 'product_to_product_catalogs';
  protected $fillable = ['product_id','product_catalog_id'];
  public $timestamps  = false;

  public function __saveRelatedData($model,$options = array()) {

    if($model->state == 'update') {
      $this->where('product_catalog_id','=',$model->id)->delete();
    }
    
    foreach ($options['value']['product_id'] as $productId) {
      
      $this->newInstance()->fill(array(
        'product_catalog_id' => $model->id,
        'product_id' => $productId
      ))->save();

    }
    
  }

}
