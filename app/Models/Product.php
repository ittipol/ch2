<?php

namespace App\Models;

class Product extends Model
{
  protected $table = 'products';
  protected $fillable = ['name','description','sku','quantity','stock_status_id','price','weight','weight_unit_id','length','length_unit_id','width','height','specifications','person_id'];
  protected $modelRelations = array('Image','Tagging','ShopRelateTo');
  protected $directory = true;

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  public $imageTypes = array(
    'photo' => array(
      'limit' => 10
    )
  );

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
      'price' => 'required|regex:/^[\d,]*(\.\d{1,2})?$/',
      'length' => 'numeric',
      'width' => 'numeric',
      'height' => 'numeric',
      'weight' => 'numeric'
    ),
    'messages' => array(
      'name.required' => 'ชื่อห้ามว่าง',
      'price.required' => 'จำนวนราคาห้ามว่าง',
      'price.regex' => 'จำนวนราคาไม่ถูกต้อง',
      'length.numeric' => 'ความยาวไม่ถูกต้อง',
      'width.numeric' => 'ความกว้างไม่ถูกต้อง',
      'height.numeric' => 'ความสูงไม่ถูกต้อง',
      'weight.numeric' => 'น้ำหนักไม่ถูกต้อง',
    ),
    'excepts' => array(
      'shop.product.add' => array('length','width','height'),
      'shop.product.edit' => array('price'),
      'shop.product_specification.edit' => array('name','price')
    )
  );

  protected $behavior = array(
    // 'Slug' => array(
    //   'field' => 'name'
    // ),
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

  public function __construct() {  
    parent::__construct();
  }

  public static function boot() {

    parent::boot();

    Product::saving(function($product){

      if(!$product->exists){
        // $product->stock_status_id
        $product->active = 0;
        $product->quantity = 0;
        $product->minimum = 1;
      }

    });

  }

  public function fill(array $attributes) {

    if(!empty($attributes)) {
   
      if(empty($attributes['width'])) {
        unset($attributes['width']);
      }

      if(empty($attributes['length'])) {
        unset($attributes['length']);
      }

      if(empty($attributes['height'])) {
        unset($attributes['height']);
      }

      if(empty($attributes['weight'])) {
        unset($attributes['weight']);
      }

      if(!empty($attributes['specifications'])) {

        $specifications = array();
        foreach ($attributes['specifications'] as $value) {

          if(empty($value['title']) || empty($value['value'])) {
            continue;
          }

          $specifications[] = array(
            'title' => trim($value['title']),
            'value' => trim($value['value'])
          );
        }

        $attributes['specifications'] = '';
        if(!empty($specifications)) {
          $attributes['specifications'] = json_encode($specifications);
        }
   
      }

    }

    return parent::fill($attributes);

  }

}
