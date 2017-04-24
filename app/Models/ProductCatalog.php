<?php

namespace App\Models;

class ProductCatalog extends Model
{
  protected $table = 'product_catalogs';
  protected $fillable = ['name','description','person_id'];
  protected $modelRelations = array('Image','Tagging','ProductToProductCatalog','ShopRelateTo');

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  public $imageTypes = array(
    'banner' => array(
      'limit' => 1
    )
  );

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
    ),
    'messages' => array(
      'name.required' => 'ชื่อแคตตาล็อกห้ามว่าง',
    )
  );

  protected $behavior = array(
    'Lookup' => array(
      'format' =>  array(
        'name' => '{{name}}'
      )
    ),
    'DataAccessPermission' => array(
      'owner' => 'Shop',
      'defaultAccessLevel' => 99
    )
  );

  public function getTotalProductInCatalog() {
    return ProductToProductCatalog::where('product_catalog_id','=',$this->id)->count();
  }

  public function getProductsInCatalog($build = false) {

    $products = Product::join('product_to_product_catalogs', 'product_to_product_catalogs.product_id', '=', 'products.id')
    ->where('product_to_product_catalogs.product_catalog_id','=',$this->id)
    ->select('products.*')
    ->orderBy('products.name','asc');

    if(!$products->exists()) {
      return null;
    }

    if(!$build) {
      return $products->get();
    }

    $_products = array();
    foreach ($products->get() as $product) {
      $_products[] = $product->buildModelData();
    }

    return $_products;

  }

  public function buildPaginationData() {

    return array(
      'name' => $this->name,
      // 'totalProduct' => $this->getTotalProductInCatalog()
    );
    
  }
}
