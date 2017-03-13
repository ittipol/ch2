<?php

namespace App\Models;

use App\library\currency;
use App\library\string;
use App\library\date;

class Product extends Model
{
  protected $table = 'products';
  protected $fillable = ['name','description','product_model','sku','quantity','quantity_available','minimum','product_unit','price','weight','weight_unit_id','length','length_unit_id','width','height','specifications','message_out_of_order','shipping_calculate_from','flag_message_from','flag_message','active','person_id'];
  protected $modelRelations = array('Image','Tagging','ProductToCategory','ShopRelateTo');
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
      'quantity' => 'numeric',
      'minimum' => 'required|numeric',
      'product_unit' => 'required|max:255',
      'length' => 'numeric',
      'width' => 'numeric',
      'height' => 'numeric',
      'weight' => 'numeric',
    ),
    'messages' => array(
      'name.required' => 'ชื่อสินค้าห้ามว่าง',
      'price.required' => 'จำนวนราคาห้ามว่าง',
      'price.regex' => 'จำนวนราคาไม่ถูกต้อง',
      'quantity.numeric' => 'โปรดกรอกจำนวนสินค้าด้วยตัวเลข',
      'minimum.required' => 'จำนวนการซื้อขั้นต่ำห้ามว่าง',
      'minimum.numeric' => 'โปรดกรอกจำนวนการซื้อขั้นต่ำด้วยตัวเลข',
      'product_unit.required' => 'หน่วยสินค้าห้ามว่าง',
      'length.numeric' => 'ความยาวไม่ถูกต้อง',
      'width.numeric' => 'ความกว้างไม่ถูกต้อง',
      'height.numeric' => 'ความสูงไม่ถูกต้อง',
      'weight.numeric' => 'น้ำหนักไม่ถูกต้อง',
    ),
    'excepts' => array(
      'shop.product.add' => array('length','width','height','weight'),
      'shop.product.edit' => array('price','minimum'),
      'shop.product_status.edit' => array('name','price','minimum','quantity','product_unit','length','width','height','weight'),
      'shop.product_specification.edit' => array('name','minimum','price','product_unit'),
      'shop.product_category.edit' => array('name','price','minimum','quantity','product_unit','length','width','height','weight'),
      'shop.product_minimum.edit' => array('name','price','quantity','product_unit','length','width','height','weight'),
      'shop.product_stock.edit' => array('name','price','minimum','product_unit','length','width','height','weight'),
      'shop.product_price.edit' => array('name','quantity','minimum','product_unit','length','width','height','weight'),
      'shop.product_notification.edit' => array('name','price','minimum','quantity','product_unit','length','width','height','weight'),
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

  public function weightUnit() {
    return $this->hasOne('App\Models\WeightUnit','id','weight_unit_id');
  }

  public function lengthUnit() {
    return $this->hasOne('App\Models\LengthUnit','id','length_unit_id');
  }

  public static function boot() {

    parent::boot();

    Product::saving(function($product){

      if(!$product->exists){
        if(empty($product->active)) {
          $product->active = 0;
        }
        // 1 = seller (person)
        // 2 = system
        $product->shipping_calculate_from = 1;
        $product->flag_message_from = 2;
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

      if(isset($attributes['quantity']) && ($attributes['quantity'] === '')) {
        unset($attributes['quantity']);
      }
      
      if(!empty($attributes['message_out_of_order']) && ($attributes['message_out_of_order'] == '')) {
        $attributes['message_out_of_order'] = null;
      }

    }

    return parent::fill($attributes);

  }

  public function getOriginalPrice($format = false) {

    $currency = new Currency;

    if($format) {
      return $currency->format($this->price);
    }

    return $this->price;

  }

  public function getPrice($format = false) {

    $currency = new Currency;

    $promotion = $this->getPromotion();

    $price = $this->price;

    if(!empty($promotion)) {
      $price = $promotion['reduced_price'];
    }

    if($format) {
      $price = $currency->format($price);
    }

    return $price;

  }

  public function getCategory() {

    $productToCategory = ProductToCategory::where('product_id','=',$this->id)
    ->select('category_id')
    ->first();

    $categoryName = '';
    if(!empty($productToCategory)) {
      $categoryName = $productToCategory->category->name;
    }

    return $categoryName;

  }

  public function getCategoryPaths() {

    $productToCategory = ProductToCategory::where('product_id','=',$this->id)
    ->select('category_id')
    ->first();

    $categoryPaths = array();
    if(!empty($productToCategory)) {

      $paths = CategoryPath::where('category_id','=',$productToCategory->category_id)->get();

      foreach ($paths as $path) {

        $subCat = $path->path->where('parent_id','=',$path->path->id)->first();

        $hasChild = false;
        if(!empty($subCat)) {
          $hasChild = true;
        }

        $categoryPaths[] = array(
          'id' => $path->path->id,
          'name' => $path->path->name,
          'hasChild' => $hasChild
        );
      }

    }

    return $categoryPaths;

  }

  public function getCategoryPathName() {

    $paths = $this->getCategoryPaths();

    $pathName = '';
    if(!empty($paths)) {

      $_path = array();
      foreach ($paths as $path) {
        $_path[] = $path['name'];
      }

      $pathName = implode(' / ', $_path);

    }

    return $pathName;

  }

  // public function weightToGram($weight = null) {

  //   if(empty($weight)) {
  //     $weight = $this->weight;
  //   }

  //   $measurement = new Measurement;
  //   return $measurement->convertToGram($this->weightUnit->unit,$this->weight);
  // }

  public function buildModelData() {

    $currency = new Currency;

    $categoryName = $this->getCategory();
    $categoryPathName = $this->getCategoryPathName();

    $specifications = array();

    if(!empty($this->product_model)) {
      $specifications[] = array(
        'title' => 'โมเดล',
        'value' => $this->product_model
      );
    }

    if(!empty($this->sku)) {
      $specifications[] = array(
        'title' => 'SKU',
        'value' => $this->sku
      );
    }

    $_specifications = json_decode($this->specifications,true);

    if(!empty($_specifications)) {
      foreach ($_specifications as $value) {
        $specifications[] = array(
          'title' => $value['title'],
          'value' => $value['value']
        );
      }
    }

    if(!empty($this->width) && !empty($this->length) && !empty($this->height)) {
      $specifications[] = array(
        'title' => 'ขนาดสินค้า (กว้าง x ยาว x สูง)',
        'value' => round($this->width, 2).' x '.round($this->length, 2).' x '.round($this->height, 2).' '.$this->lengthUnit->name,
      );
    }

    if(!empty($this->weight)) {
      $specifications[] = array(
        'title' => 'น้ำหนัก',
        'value' => round($this->weight, 2).' '.$this->weightUnit->name,
      );
    }

    $shippingCalculateFrom = 'คำนวนค่าส่งสินค้าด้วยระบบ';
    if($this->shipping_calculate_from == 1) {
      $shippingCalculateFrom = 'คำนวนค่าส่งสินค้าจากผู้ขาย';
    }

    $shipping = $this->getRelatedData('ProductShipping',array(
      'first' => true
    ));

    $shippingCost = '';
    $shippingAmountCondition = '';
    $freeShippingMessage = '';
    if(!empty($shipping)) {
      $shippingCost = $shipping->getShippingCostText(true);

      if(!empty($shipping->product_shipping_amount_type_id)) {
        $shippingAmountCondition = $shipping->shippingAmountCondition->name;
      }

      if(!empty($shipping->free_shipping_with_condition)) {
        $freeShippingMessage = $shipping->getFreeShippingWithConditionText($this);
      }

    }

    return array(
      'id' => $this->id,
      'name' => $this->name,
      'description' => !empty($this->description) ? $this->description : '-',
      'sku' => $this->sku,
      '_price' => $currency->format($this->price),
      'quantity' => $this->quantity,
      'message_out_of_order' => $this->message_out_of_order ? $this->message_out_of_order : 'สินค้าหมด',
      'minimum' => $this->minimum,
      'product_unit' => $this->product_unit,
      'specifications' => $specifications,
      'shipping_calculate_from' => $this->shipping_calculate_from,
      '_shipping_calculate_from' => $shippingCalculateFrom,
      'shippingCost' => $shippingCost,
      'shippingCostAppendText' => $shippingAmountCondition,
      'freeShippingMessage' => $freeShippingMessage,
      'active' => $this->active,
      '_active' => $this->active ? 'เปิดการขายสินค้า' : 'ปิดการขาย',
      '_categoryName' => !empty($categoryName) ? $categoryName : '-',
      '_categoryPathName' => !empty($categoryPathName) ? $categoryPathName : '-',
      '_categoryPaths' => $this->getCategoryPaths(),
      'promotion' => $this->getPromotion(),
      'flag' => $this->getFlag(),
    );

  }

  public function buildPaginationData() {

    $string = new String;
    $currency = new Currency;

    return array(
      'id' => $this->id,
      'name' => $this->name,
      '_short_name' => $string->subString($this->name,45),
      'sku' => !empty($this->sku) ? $this->sku : '-',
      '_price' => $currency->format($this->price),
      'quantity' => $this->quantity,
      '_active' => $this->active ? 'เปิดการขายสินค้า' : 'ปิดการขายสินค้า',
      'promotion' => $this->getPromotion(),
      'flag' => $this->getFlag(),
    );
    
  }

  public function getFlag() {

    $flagMessage = '';
    switch ($this->flag_message_from) {
      case 1:
        
        $flagMessage = $this->flag_message;

        break;
      
      case 2:

          if($this->isNewItem()) {
            $flagMessage = 'สินค้ามาใหม่';
          }elseif($this->hasPromotion()){
            $flagMessage = 'สินค้าโปรโมชั่น';
          }

        break;
    }

    return $flagMessage;

  }

  public function isNewItem() {
    $date = new Date;
    $diff = $date->now(true,true) - strtotime($this->created_at->format('Y-m-d H:i:s'));

    if($diff > 604800) {
      return false;
    }

    return true;
  }

  public function hasPromotion() {

    $now = date('Y-m-d H:i:s');

    $promotion = $this->getRelatedData('ProductSalePromotion',array(
      'conditions' => array(
        array('sale_promotion_type_id','=',1),
        array('date_start','<=',$now),
        array('date_end','>=',$now)
      ),
      'fields' => array('id')
    ));

    if(empty($promotion)) {
      return false;
    }

    return true;

  }

  public function getPromotion($salePromotionTypeAlias = null) {

    $now = date('Y-m-d H:i:s');

    $promotion = $this->getRelatedData('ProductSalePromotion',array(
      'conditions' => array(
        array('sale_promotion_type_id','=',1),
        array('date_start','<=',$now),
        array('date_end','>=',$now)
      ),
      'fields' => array('model','model_id','date_start','date_end'),
      'order' => array('date_start','ASC'),
      'first' => true
    ));

    if(empty($promotion)) {
      return null;
    }

    return array_merge($promotion->buildModelData(),$promotion->{lcfirst($promotion->model)}->buildModelData());

  }

}
