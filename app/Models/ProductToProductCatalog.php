<?php

namespace App\Models;

class ProductToProductCatalog extends Model
{
  protected $table = 'product_to_product_catalogs';
  protected $fillable = ['product_id','product_catalog_id'];
  public $timestamps  = false;

  public function productCatalog() {
    return $this->hasOne('App\Models\ProductCatalog','id','product_catalog_id');
  }

  public function product() {
    return $this->hasOne('App\Models\Product','id','product_id');
  }

  public function __saveRelatedData($model,$options = array()) {

    $shopRelateToModel = new ShopRelateTo;

    if($model->exists) {
      $this->where('product_catalog_id','=',$model->id)->delete();
    }
 
    if(empty($options['value']['product_id'])) {
      return true;
    }

    $shopId = $shopRelateToModel
    ->select('shop_id')
    ->where([
      ['model','like',$model->modelName],
      ['model_id','=',$model->id]
    ])->first()->shop_id;

    foreach ($options['value']['product_id'] as $productId) {
      
      $exist = $shopRelateToModel
      ->select('model_id')
      ->where([
        ['shop_id','=',$shopId],
        ['model','=','Product'],
        ['model_id','=',$productId]
      ])->exists();

      if(!$exist) {
        continue;
      }

      $this->newInstance()->fill(array(
        'product_catalog_id' => $model->id,
        'product_id' => $productId
      ))->save();

    }

    return true;
    
  }

  public function deleteByProductCatalogId($id) {
    $this->where('product_catalog_id','=',$id)->delete();
  }

}
