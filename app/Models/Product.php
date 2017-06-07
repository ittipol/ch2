<?php

namespace App\Models;

use App\library\currency;
use App\library\stringHelper;
use App\library\date;
use App\library\cache;
use App\library\url;

class Product extends Model
{
  protected $table = 'products';
  protected $fillable = ['name','description','product_model','sku','quantity','quantity_available','minimum','product_unit','price','weight','weight_unit_id','length','length_unit_id','width','height','specifications','message_out_of_order','shipping_calculate_from','flag_message_from','flag_message','active','product_selling','created_by'];
  protected $modelRelations = array('Image','Tagging','ProductToCategory','RelateToBranch','ShopRelateTo','ProductOption','ProductSalePromotion','ProductShipping','Review');

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
      'price' => 'required|regex:/^[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$/',
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
      'shop.product_branch.edit' => array('name','price','minimum','quantity','product_unit','length','width','height','weight'),
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

  protected $filterOptions = array(
    'product' => array(
      'input' => 'checkbox',
      'title' => 'แสดงสินค้า',
      'options' => array(
        array(
          'name' => 'สินค้าที่เปิดขาย',
          'value' => 'active:1',
        ),
        array(
          'name' => 'สินค้ามาใหม่',
          'value' => 'f:new-arrival',
        )
      )
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
        'name' => 'ราคา ต่ำไปสูง',
        'value' => 'price:asc'
      ),
      array(
        'name' => 'ราคา สูงไปต่ำ',
        'value' => 'price:desc'
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

  // product_selling
  // 1 = retail
  // 2 = wholesale

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

      if($product->state == 'create') {
        $product->active = 0;
        $product->product_selling = 1;

        if(empty($product->quantity)) {
          $product->quantity = 0;
        }

      }

      if(!$product->exists){
        // 1 = seller (person)
        // 2 = system
        $product->shipping_calculate_from = 1;
        $product->flag_message_from = 2;
      }

    });

    Product::deleted(function($product) {
       // delete product in cart
       Cart::where('product_id','=',$product->id)->delete();
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

    // $promotion = $this->getPromotion();

    $price = $this->price;

    // if(!empty($promotion)) {
    //   $price = $promotion['reduced_price'];
    // }

    if(!empty($this->promotion)) {
      $price = $this->promotion['reduced_price'];
    }else{
      $promotion = $this->getPromotion();

      if(!empty($promotion)) {
        $price = $promotion['reduced_price'];
      }
    }

    if($format) {
      $price = $currency->format($price);
    }

    return $price;

  }

  public function getCategory() {

    $productToCategory = ProductToCategory::where('product_id','=',$this->id)->select('category_id');

    if(!$productToCategory->exists()) {
      return null;
    }

    return $productToCategory->first()->category;
  }

  public function getCategoryName() {

    $productToCategory = ProductToCategory::where('product_id','=',$this->id)->select('category_id');

    if(!$productToCategory->exists()) {
      return '';
    }

    return $productToCategory->first()->category->name;

  }

  public function getCategoryPaths() {

    $url = new Url;

    $productToCategory = ProductToCategory::where('product_id','=',$this->id)->select('category_id');

    if(!$productToCategory->exists()) {
      return null;
    }

    $categoryModel = new Category;

    return $categoryModel->getCategoryPaths($productToCategory->first()->category_id);

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

  public function getProductCatalogs() {

    $productCatalogs = ProductCatalog::join('product_to_product_catalogs', 'product_to_product_catalogs.product_catalog_id', '=', 'product_catalogs.id')
    ->where('product_to_product_catalogs.product_id','=',$this->id)
    ->select('product_catalogs.*')
    ->orderBy('product_catalogs.name','asc');

    if(!$productCatalogs->exists()) {
      return null;
    }

    return $productCatalogs->get();
  }

  public function checkProductOptionValue($optionValueId) {
    return ProductOption::join('product_option_values', 'product_option_values.product_option_id', '=', 'product_options.id')
    ->where('product_option_values.id','=',$optionValueId)
    ->exists();
  }

  public function getTotalProductOption() {
    return ProductOption::where('product_id','=',$this->id)->count();
  }

  public function getProductOptions() {

    $productOptions = ProductOption::where('product_id','=',$this->id);

    if(!$productOptions->exists()) {
      return null;
    }

    return $productOptions->get();
  }

  public function getProductOptionValues() {

    $productOptions = ProductOption::where('product_id','=',$this->id);

    if(!$productOptions->exists()) {
      return null;
    }

    $currency = new Currency;

    $_optionValues = array();
    foreach ($productOptions->get() as $value) {
    
      $options = $value->getOptionValue();

      if(empty($options)) {
        continue;
      }

      $data['id'] = $value->id;
      $data['name'] = $value->name;

      foreach ($options as $option) {

        $name = null;
        $image = null;
        switch ($option->display_type) {
          case 1:
              $name = $option->name;
            break;

          case 2:
              $image = $option->getImage('productOption');
            break;
          
          case 3:
              $name = $option->name;
              $image = $option->getImage('productOption');
            break;
        }

        if($option->use_quantity) {
          $_quantity = $option->quantity;
        }else{
          $_quantity = $this->quantity;
        }

        $quantityText = null;
        if($_quantity == 0) {
          $quantityText = $this->message_out_of_order ? $this->message_out_of_order : 'สินค้าหมด';
        }elseif($_quantity < 11) {
          $quantityText = 'เหลือเพียง '.$_quantity.' '.$this->product_unit;
        }else{
          $quantityText = 'มีสินค้า';
        }

        $data['options'][] = array(
          'id' => $option->id,
          'name' => $name,
          'imageUrl' => $image,
          'realPrice' => $currency->format($this->price + $option->price),
          'quantityText' => $quantityText,
          'display_type' => $option->display_type
        );
      }

      $_optionValues[] = $data;

    }

    return $_optionValues;

  }

  public function hasProductOption() {
    return $productOptions = ProductOption::where('product_id','=',$this->id)->exists();
  }

  public function buildModelData() {

    $currency = new Currency;

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

    $quantityText = null;
    if($this->quantity == 0) {
      $quantityText = $this->message_out_of_order ? $this->message_out_of_order : 'สินค้าหมด';
    }elseif($this->quantity < 11) {
      $quantityText = 'เหลือเพียง '.$this->quantity.' '.$this->product_unit;
    }else{
      $quantityText = 'มีสินค้า';
    }

    return array_merge(array(
      'id' => $this->id,
      'name' => $this->name,
      'description' => !empty($this->description) ? nl2br($this->description) : '-',
      'sku' => $this->sku,
      '_price' => $currency->format($this->price),
      'quantityText' => $quantityText,
      'quantity' => $this->quantity,
      // 'message_out_of_order' => $this->message_out_of_order ? $this->message_out_of_order : 'สินค้าหมด',
      'minimum' => $this->minimum,
      'product_unit' => $this->product_unit,
      'specifications' => $specifications,
      'shipping_calculate_from' => $this->shipping_calculate_from,
      '_shipping_calculate_from' => $shippingCalculateFrom,
      'active' => $this->active,
      '_active' => $this->active ? 'เปิดการขายสินค้า' : 'ปิดการขาย',
      'promotion' => $this->getPromotion(),
      'flag' => $this->getProductFlag(),
    ),$this->getShippingCostText());

  }

  public function buildPaginationData() {

    $string = new stringHelper;
    $currency = new Currency;
    
    return array(
      'id' => $this->id,
      'name' => $this->name,
      '_short_name' => $string->truncString($this->name,25),
      'sku' => !empty($this->sku) ? $this->sku : '-',
      '_price' => $currency->format($this->price),
      'quantity' => $this->quantity,
      '_active' => $this->active ? 'เปิดการขายสินค้า' : 'ปิดการขายสินค้า',
      'promotion' => $this->getPromotion(),
      'flag' => $this->getProductFlag()
    );
    
  }

  public function getShippingCostText() {

    $shipping = $this->getRelatedData('ProductShipping',array(
      'first' => true
    ));

    $shippingCostText = '';
    $productShippingAmountType = '';
    $freeShippingMessage = '';
    if(!empty($shipping)) {
      $shippingCostText = $shipping->getShippingCostText(true);

      if(!empty($shipping->product_shipping_amount_type_id)) {
        $productShippingAmountType = $shipping->productShippingAmountType->name;
      }

      if(!empty($shipping->free_shipping_with_condition)) {
        $freeShippingMessage = $shipping->getFreeShippingWithConditionText($this);
      }

    }

    return array(
      'shippingCostText' => $shippingCostText,
      'shippingCostAppendText' => $productShippingAmountType,
      'freeShippingMessage' => $freeShippingMessage,
    );

  }

  public function getProductFlag() {

    $flagMessage = '';
    // switch ($this->flag_message_from) {
    //   case 1:
        
    //     $flagMessage = $this->flag_message;

    //     break;
      
    //   case 2:

    //       // if($this->isNewItem()) {
    //       //   $flagMessage = 'สินค้ามาใหม่';
    //       // }

    //       if($this->hasPromotion()){
    //         $flagMessage = 'สินค้าโปรโมชั่น';
    //       }

    //     break;
    // }

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
      'order' => array('date_start','asc'),
      'first' => true
    ));

    if(empty($promotion)) {
      return null;
    }

    return array_merge($promotion->buildModelData(),$promotion->{lcfirst($promotion->model)}->buildModelData());

  }

  public function checkProductBought() {

    // Service::loadModel('OrderStatus')->getIdByalias('pending-seller-confirmation');
    return Order::join('order_products', 'order_products.order_id', '=', 'orders.id')
    ->select('orders.id')
    ->where([
      // ['orders.order_status_id','=',],
      ['order_products.product_id','=',$this->id],
      ['created_by','=',session()->get('Person.id')]
    ])->exists();

  }

  public function productAvgScore() {
    $review = new Review;
    return $review->getAvgScore($this);
  }

  public function productScoreList() {
    $review = new Review;
    return $review->getScoreList($this);
  }

  public function buildLookupData() {

    $url = new url;
    $string = new stringHelper;
    $currency = new Currency;

    return array(
      'title' => $string->truncString($this->name,90),
      'description' => $string->truncString($this->description,250),
      'flags' => array(
        $this->getProductFlag()
      ),
      'data' => array(
        'productPrice' => array(
          'value' => array(
            'price' => $currency->format($this->price),
            'promotion' => $this->getPromotion()
          )
        )
      ),
      'detailUrl' => $url->setAndParseUrl('product/detail/{id}',array('id' => $this->id)),
      'image' => $this->getImage('list'),
      'isDataTitle' => 'สินค้าจากบริษัทและร้านค้า'
    );

  }

  public function buildTimelineData() {

    $url = new url;
    $string = new stringHelper;

    return array(
      'title' => $string->truncString($this->name,90),
      'description' => $string->truncString($this->description,250),
      // 'data' => array()
      'detailUrl' => $url->setAndParseUrl('product/detail/{id}',array('id' => $this->id)),
      'image' => $this->getImage('list')
    );

  }

}
