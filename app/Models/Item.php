<?php

namespace App\Models;

use App\library\currency;
use App\library\string;

class Item extends Model
{
  protected $table = 'items';
  protected $fillable = ['name','announcement_detail','description','price','announcement_type_id','used','person_id'];
  protected $modelRelations = array('Image','Address','Tagging','Contact','ItemToCategory');
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
      // 'price' => 'required|regex:/^[\d,]*(\.\d{1,2})?$/|max:255',
      'price' => 'required|regex:/^[0-9,]*(\.[0-9]{1,2})?$/|max:255',
      'Contact.phone_number' => 'required|max:255',
      // 'Contact.email' => 'email|unique:contacts,email|max:255',
      'ItemToCategory.item_category_id' => 'required' 
    ),
    'messages' => array(
      'name.required' => 'ชื่อสินค้าที่ต้องการประกาศห้ามว่าง',
      'price.required' => 'จำนวนราคาห้ามว่าง',
      'price.regex' => 'รูปแบบจำนวนราคาไม่ถูกต้อง',
      'Contact.phone_number.required' => 'หมายเลขโทรศัพท์ห้ามว่าง',
      'ItemToCategory.item_category_id.required' => 'หมวดหมู่หลักสินค้าห้ามว่าง',
    )
  ); 

  public function __construct() {  
    parent::__construct();
  }

  public function announcementType() {
    return $this->hasOne('App\Models\AnnouncementType','id','announcement_type_id');
  }

  public function itemToCategory() {
    return $this->hasOne('App\Models\ItemToCategory','item_id','id');
  }

  public function itemToCategories() {
    return $this->hasMany('App\Models\ItemToCategory','item_id','id');
  }

  public function fill(array $attributes) {

    if(!empty($attributes)) {
      $attributes['price'] = str_replace(',', '', $attributes['price']);
    }

    return parent::fill($attributes);

  }

  public function buildModelData() {

    $currency = new Currency;

    $categoryName = '';
    if(!empty($this->itemToCategory)) {
      $categoryName = $this->itemToCategory->category->name;
    }

    return array(
      'id' => $this->id,
      'announcement_type_id' => $this->announcement_type_id,
      'name' => $this->name,
      'description' => !empty($this->description) ? $this->description : '-',
      '_price' => $currency->format($this->price),
      '_used' => $this->used ? 'สินค้าใหม่' : 'สินค้ามือสอง',
      '_announcementTypeName' => $this->announcementType->name,
      '_categoryName' => $categoryName
    );

  }

  public function buildPaginationData() {

    $currency = new Currency;
    $string = new String;

    return array(
      'id' => $this->id,
      'name' => $this->name,
      '_name_short' => $string->subString($this->name,45),
      '_price' => $currency->format($this->price)
    );
    
  }

}
