<?php

namespace App\Models;

use App\library\currency;
use App\library\measurement;

class ProductShipping extends Model
{
  protected $table = 'product_shippings';
  protected $fillable = ['product_id','free_shipping','shipping_cost','product_shipping_amount_type_id','free_shipping_with_condition','shipping_calculate_type_id','free_shipping_operator_sign','free_shipping_amount'];
  public $timestamps  = false;

  public $formHelper = true;
  public $modelData = true;

  public $operatorSigns = array(
    '>' => 'มากกว่า',
    '=' => 'เท่ากับ',
    '<' => 'น้อยกว่า'
  );

  protected $validation = array(
    'rules' => array(
      'free_shipping_amount' => 'required|numeric',
      'shipping_cost' => 'required|numeric',
    ),
    'messages' => array(
      'free_shipping_amount.required' => 'จำนวนของเงื่อนไขการส่งสินค้าฟรีห้ามว่าง',
      'free_shipping_amount.numeric' => 'จำนวนของเงื่อนไขการส่งสินค้าฟรีไม่ถูกต้อง',
      'shipping_cost.required' => 'ค่าขนส่งสินค้าห้ามว่าง',
      'shipping_cost.numeric' => 'ค่าขนส่งสินค้าไม่ถูกต้อง'
    ),
    'conditions' => array(
      'free_shipping_amount' => array(
        'field' => 'free_shipping_with_condition',
        'value' => 1,
      ),
      'shipping_cost' => array(
        'field' => 'free_shipping',
        'value' => 0,
      )
    )
  );

  public function shippingAmountCondition() {
    return $this->hasOne('App\Models\ShippingAmountCondition','id','product_shipping_amount_type_id');
  }

  public function shippingCostCalCulateType() {
    return $this->hasOne('App\Models\ShippingCostCalCulateType','id','shipping_calculate_type_id');
  }

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
        $productShipping->shipping_cost = null;
        $productShipping->product_shipping_amount_type_id = null;
        $productShipping->free_shipping_with_condition = null;
      }

      if($productShipping->free_shipping == 1) {
        $productShipping->shipping_cost = null;
        $productShipping->product_shipping_amount_type_id = null;
      }

      if(empty($productShipping->free_shipping_with_condition)) {
        $productShipping->shipping_calculate_type_id = null;
        $productShipping->free_shipping_operator_sign = NULL;
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

  public function parseOperatorSigntoName($operatorSign) {
    return $this->operatorSigns[$operatorSign];
  }

  public function getFreeShippingWithConditionText($product) {

    $currency = new Currency;
    $measurement = new Measurement;

    $text = 'จัดส่งฟรีเมื่อ%s%s%s';

    $amount = '';
    switch ($this->shipping_calculate_type_id) {
      case 1:
        if(!empty($product->weight)) {
          $amount = ' '.$measurement->format($this->free_shipping_amount).' '.$product->weightUnit->name;
        }
        break;
      
      case 2:
        $amount = ' '.(int)$this->free_shipping_amount.' '.$product->product_unit;
        break;

      case 3:
        $amount = ' '.$currency->format($this->free_shipping_amount);
        break;

    }

    if(empty($amount)) {
      return '';
    }

    return sprintf($text,
      $this->shippingCostCalCulateType->name,
      $this->parseOperatorSigntoName($this->free_shipping_operator_sign),
      $amount
    );

  }

  public function getShippingCost($product,$quantity) {

    $total = 0;
    if(!$this->free_shipping) {

      if(!$this->checkFreeShippingCondition($product,$quantity)) {

        switch ($this->product_shipping_amount_type_id) {
          case 1:
            $total = $this->shipping_cost * $quantity;
            break;

          case 2:
            $total = $this->shipping_cost;
            break;

        }

      }

    }

    return $total;

  }

  public function checkFreeShippingCondition($product,$quantity) {

    $productValue = null;

    switch ($this->shipping_calculate_type_id) {
      case 1:
          if(!empty($product->weight)) {
            $productValue = $product->weight * $quantity;
          }
        break;
      
      case 2:
          $productValue = $quantity;
        break;

      case 3: 

          $promotion = $product->getPromotion();

          $price = $product->price;

          if(!empty($product->promotion)) {
            $price = $product->promotion['reduced_price'];
          }

          $productValue = $price * $quantity;
        break;

    }

    if(empty($productValue)) {
      return false;
    }

    return $this->checkHasFreeShippingCondition(
      $this->free_shipping_amount,
      $productValue,
      $this->free_shipping_operator_sign
    );

  }

  public function checkHasFreeShippingCondition($freeShippingAmount,$productValue,$operatorSign) {

    $hasFreeShipping = false;
    switch ($operatorSign) {
      case '>':

        if($productValue > $freeShippingAmount) {
          $hasFreeShipping = true;
        }

        break;
      
      case '=':

        if($productValue == $freeShippingAmount) {
          $hasFreeShipping = true;
        }

        break;

      case '<':
  
        if($productValue < $freeShippingAmount) {
          $hasFreeShipping = true;
        }

        break;
    }

    return $hasFreeShipping;

  }

  public function getShippingCostText($format = false) {

    $currency = new Currency;

    $shippingCost = 'จัดส่งฟรี';
    if(!$this->free_shipping) {

      $shippingCost = $this->shipping_cost;

      if($format) {
        $shippingCost = $currency->format($shippingCost);
      }

    }

    return $shippingCost;

  }

  public function getProductPriceWithShippingCost($price,$quantity) {

    $total = $price * $quantity;

    if(!$this->free_shipping) {

      switch ($this->product_shipping_amount_type_id) {
        case 1:
          $total = ($price + $this->shipping_amount) * $quantity;
          break;
        
        case 2:
          $total = ($price * $quantity) + $this->shipping_cost;
          break;
      }

    }

    return $total;

  }

  public function buildModelData() {

    $shippingCost = '';
    $shippingAmountCondition = '';
    $freeShippingMessage = '';

    $shippingCost = $this->getShippingCostText(true);

    if(!empty($this->product_shipping_amount_type_id)) {
      $shippingAmountCondition = $this->shippingAmountCondition->name;
    }

    if(!empty($this->free_shipping_with_condition)) {
      $freeShippingMessage = $this->getFreeShippingWithConditionText($this);
    }

    return array(
      'free_shipping' => $this->free_shipping,
      'shipping_calculate_from' => $this->shipping_calculate_from,
      '_shipping_calculate_from' => $shippingCalculateFrom,
      'shippingCost' => $shippingCost,
      'shippingCostAppendText' => $shippingAmountCondition,
      'freeShippingMessage' => $freeShippingMessage,
    );

  }

}
