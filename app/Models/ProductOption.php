<?php

namespace App\Models;

class ProductOption extends Model
{
  protected $table = 'product_options';
  protected $fillable = ['product_id','name','created_by'];
  protected $modelRelations = array('ProductOptionValue');

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
    ),
    'messages' => array(
      'name.required' => 'ชื่อหัวข้อคุณลักษณะห้ามว่าง',
    )
  );

  public function getTotalOptionValueInProductOption() {
    return ProductOptionValue::where('product_option_id','=',$this->id)->count();
  }

  public function getOptionValue() {
    $optionValues =  ProductOptionValue::where('product_option_id','=',$this->id);

    if(!$optionValues->exists()) {
      return null;
    }

    return $optionValues->get();    

  }

  public function buildModelData() {

    return array(
      'name' => $this->name
    );

  }

}

