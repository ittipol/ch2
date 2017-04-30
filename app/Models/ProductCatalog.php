<?php

namespace App\Models;

use App\library\string;
use App\library\cache;
use App\library\url;

class ProductCatalog extends Model
{
  protected $table = 'product_catalogs';
  protected $fillable = ['name','description','created_by'];
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

  protected $sortingFields = array(
    'title' => 'จัดเรียงตาม',
    'options' => array(
      array(
        'name' => 'ตัวอักษร A - Z ก - ฮ',
        'value' => 'name:asc'
      ),
      array(
        'name' => 'ตัวอักษร Z - A ฮ - ก',
        'value' => 'name:desc'
      ),
      array(
        'name' => 'วันที่เก่าที่สุดไปหาใหม่ที่สุด',
        'value' => 'created_at:asc'
      ),
      array(
        'name' => 'วันที่ใหม่ที่สุดไปหาเก่าที่สุด',
        'value' => 'created_at:desc'
      ),
    ),
    'default' => 'created_at:desc'
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

  public function buildModelData() {

    return array(
      'id' => $this->id,
      'name' => $this->name,
      'description' => nl2br($this->description)
    );
    
  }

  public function buildPaginationData() {

    return array(
      'name' => $this->name,
      'totalProduct' => $this->getTotalProductInCatalog()
    );
    
  }

  public function buildLookupData() {

    $string = new String;
    $url = new url;

    $shop = ShopRelateTo::select('shop_id')
    ->where(array(
      array('model','like','Branch'),
      array('model_id','=',$this->id)
    ))
    ->first()
    ->shop;

    $slug = $shop->getRelatedData('Slug',array(
      'fields' => array('slug'),
      'first' => true
    ))->slug;

    return array(
      'title' => $string->truncString($this->name,90),
      'description' => $string->truncString($this->description,250),
      'data' => array(
        'branch' => array(
        'title' => 'แคตตาล็อกสินค้าจาก',
          'value' => $shop->name
        )
      ),
      'detailUrl' => $url->url('shop/'.$slug.'/product_catalog/'.$this->id),
      'image' => $this->getImage('list'),
      'isDataTitle' => 'แคตตาล็อกสินค้า'
    );

  }
}
