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

  // public function 

  public function buildPaginationData() {

    return array(
      'name' => $this->name,
      'totalProduct' => ProductToProductCatalog::where('product_catalog_id','=',$this->id)->count()
    );
    
  }
}
