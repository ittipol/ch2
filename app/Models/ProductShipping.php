<?php

namespace App\Models;

class ProductShipping extends Model
{
  protected $table = 'product_shippings';
  protected $fillable = ['product_id','free_shipping','shipping_amount','shipping_amount_condition_id','free_shipping_with_condition','shipping_calculate_type_id','shipping_condition','free_shipping_amount'];
  public $timestamps  = false;

  public $formHelper = true;
  public $modelData = true;

  protected $validation = array(
    'rules' => array(
      'free_shipping_amount' => 'required|numeric',
      'shipping_amount' => 'required|numeric',
    ),
    'messages' => array(
      'free_shipping_amount.required' => 'จำนวนของเงื่อนไขการส่งสินค้าฟรีห้ามว่าง',
      'free_shipping_amount.numeric' => 'จำนวนของเงื่อนไขการส่งสินค้าฟรีไม่ถูกต้อง',
      'shipping_amount.required' => 'ค่าขนส่งสินค้าต่อสินค้าหนึ่งชิ้นห้ามว่าง',
      'shipping_amount.numeric' => 'ค่าขนส่งสินค้าต่อสินค้าหนึ่งชิ้นไม่ถูกต้อง'
    ),
    'conditions' => array(
      'free_shipping_amount' => array(
        'field' => 'free_shipping_with_condition',
        'value' => 1,
      ),
      'shipping_amount' => array(
        'field' => 'free_shipping',
        'value' => 0,
      )
    )
  );

  public static function boot() {

    parent::boot();

    ProductShipping::saving(function($productShipping){

      Product::find($productShipping->product_id)
      ->fill(array(
        'shipping_calculate_from' => $productShipping->modelRelationData['Product']['shipping_calculate_from']
      ))
      ->save();

      if($productShipping->modelRelationData['Product']['shipping_calculate_from'] == 1) {
        $productShipping->free_shipping = null;
        $productShipping->shipping_amount = null;
        $productShipping->shipping_amount_condition_id = null;
        $productShipping->free_shipping_with_condition = null;
      }

      if($productShipping->free_shipping == 1) {
        $productShipping->shipping_amount = null;
        $productShipping->shipping_amount_condition_id = null;
      }

      if(empty($productShipping->free_shipping_with_condition)) {
        $productShipping->shipping_calculate_type_id = null;
        $productShipping->shipping_condition = NULL;
        $productShipping->free_shipping_amount = NULL;
      }

      unset($productShipping->modelRelationData['Product']['shipping_calculate_from']);

    });

  }

  public function fill(array $attributes) {

    if(!empty($attributes)) {
      $this->modelRelationData['Product']['shipping_calculate_from'] = $attributes['shipping_calculate_from'];
      unset($attributes['shipping_calculate_from']);
    
      if(empty($attributes['free_shipping_with_condition'])) {
        $attributes['free_shipping_with_condition'] = null;
      }

    }

    return parent::fill($attributes);

  }

}
