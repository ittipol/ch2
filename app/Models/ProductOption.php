<?php

namespace App\Models;

class ProductOption extends Model
{
  protected $table = 'product_options';
  protected $fillable = ['product_id','name','quantity','price','created_by'];
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
      'name' => 'required|max:255',
      'price' => 'regex:/^[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$/',
      'quantity' => 'numeric',
    ),
    'messages' => array(
      'name.required' => 'ชื่อตัวเลือกห้ามว่าง',
      'price.regex' => 'จำนวนราคาไม่ถูกต้อง',
      'quantity.numeric' => 'โปรดกรอกจำนวนสินค้าด้วยตัวเลข',
    )
  );

  public function buildPaginationData() {

    $quantity = $this->quantity;
    if($this->quantity === null) {
      $quantity = 'ไม่ได้ระบุ';
    }

    $price = $this->price;
    if($this->price === null) {
      $price = 'ไม่ได้ระบุ';
    }
    
    return array(
      'name' => $this->name,
      'quantity' => $quantity,
      'price' => $price,
    );

  }
}
