<?php

namespace App\Models;

class Product extends Model
{
  protected $table = 'products';
  protected $fillable = ['name','description','sku','quantity','stock_status_id','price','weight','weight_id','length','length_id','width','height','person_id'];
  protected $modelRelations = array('Image','Address','Tagging');
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
    ),
    'messages' => array(
      'name.required' => 'ชื่อห้ามว่าง',
      'price.required' => 'จำนวนราคาห้ามว่าง',
      'price.regex' => 'จำนวนราคาไม่ถูกต้อง',
    )
  );

  protected $behavior = array(
    // 'Slug' => array(
    //   'field' => 'name'
    // ),
    // 'Lookup' => array(
    //   'format' =>  array(
    //     'keyword' => '{{name}}'
    //   )
    // ),
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

    Product::saved(function($model){
      $lookup = new Lookup;
      $lookup->__saveRelatedData($model);
    });

  }

}
