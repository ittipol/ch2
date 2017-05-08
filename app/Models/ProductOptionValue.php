<?php

namespace App\Models;

class ProductOptionValue extends Model
{
  protected $table = 'product_option_values';
  protected $fillable = ['product_option_id','name','use_quantity','quantity','use_price','price','display_type','created_by'];
  protected $modelRelations = array('Image');

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  public $imageTypes = array(
    'photo' => array(
      'limit' => 1
    )
  );

  protected $validation = array(
    'rules' => array(
      'display_type' => 'required',
      'name' => 'required|max:255',
      'price' => 'regex:/^[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$/',
      'quantity' => 'numeric',
    ),
    'messages' => array(
      'display_type' => 'รูปแบบการแสดงห้ามว่าง',
      'name.required' => 'ชื่อตัวเลือกห้ามว่าง',
      'price.regex' => 'จำนวนราคาไม่ถูกต้อง',
      'quantity.numeric' => 'โปรดกรอกจำนวนสินค้าด้วยตัวเลข',
    )
  );

  private $displayType = array(
    '1' => 'แสดงเฉพาะชื่อตัวเลือก',
    '2' => 'แสดงเฉพาะรูปภาพ',
    '3' => 'แสดงชื่อตัวเลือกพร้อมรูปภาพ',
  );

  public function productOption() {
    return $this->hasOne('App\Models\ProductOption','id','product_option_id');
  }

  public static function boot() {

    parent::boot();

    ProductOptionValue::saving(function($productOptionValue){

      if(empty($productOptionValue->price)) {
        $productOptionValue->price = 0;
      }

      if(empty($productOptionValue->quantity)) {
        $productOptionValue->quantity = 0;
      }

    });

  }

  public function fill(array $attributes) {
    
    if(!empty($attributes)) {

      if(empty($attributes['use_quantity'])) {
        $attributes['use_quantity'] = 0;
        $attributes['quantity'] = null;
      }

      if(empty($attributes['use_price'])) {
        $attributes['use_price'] = 0;
        $attributes['price'] = 0;
      }

    }

    return parent::fill($attributes);
  }

  public function getOptionDisplayType($type) {
    
    if(array_key_exists($type, $this->displayType)) {
      return $this->displayType[$type];
    }

    return null;

  }

  public function buildPaginationData() {

    $quantity = $this->quantity;
    if($this->use_quantity == 0) {
      $quantity = 'ใช้จำนวนหลัก';
    }

    $price = $this->price;
    if($this->use_price == 0) {
      $price = 'ใช้ราคาหลัก';
    }

    return array(
      'name' => $this->name,
      'quantity' => $quantity,
      'price' => $price,
      'displayType' => $this->getOptionDisplayType($this->display_type)
    );

  }
}
