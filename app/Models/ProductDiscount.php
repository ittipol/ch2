<?php

namespace App\Models;

use App\library\currency;
use App\library\date;

class ProductDiscount extends Model
{
  protected $table = 'product_discounts';
  protected $fillable = ['product_id','reduced_price'];
  protected $modelRelations = array('ProductSalePromotion');

  public $formHelper = true;
  public $modelData = true;

  protected $validation = array(
    'rules' => array(
      'reduced_price_input' => 'required|numeric',
      'reduced_percent_input' => 'required|numeric',
      'date_start' => 'date_format:Y-m-d H:i:s',
      'date_end' => 'date_format:Y-m-d H:i:s|after_or_equal:date_start',
    ),
    'messages' => array(
      'reduced_price_input.required' => 'จำนวนราคาที่ต้องการลดห้ามว่าง',
      'reduced_percent_input.required' => 'จำนวน % ห้ามว่าง',
      'date_start.date_format' => 'วันที่เริ่มต้นโปรโมชั่นไม่ถูกต้อง',
      'date_end.date_format' => 'วันสุดท้ายของโปรโมชั่นไม่ถูกต้อง',
      'date_end.after_or_equal' => 'วันสุดท้ายของโปรโมชั่นจะต้องเป็นวันเดียวกันหรือหลังจากวันที่เริ่มต้นโปรโมชั่นเท่านั้น',
    ),
    'excepts' => array(
      'shop.product_discount.edit' => array('reduced_price_input','reduced_percent_input'),
    ),
    'conditions' => array(
      'reduced_price_input' => array(
        'field' => 'input_type',
        'value' => 1,
      ),
      'reduced_percent_input' => array(
        'field' => 'input_type',
        'value' => 2,
      )
    )
  );

  public function fill(array $attributes) {

    if(!empty($attributes)) {

      // $date = new Date;
      // $attributes['ProductSalePromotion'] = $date->appendTimeForDateStartAndDateEnd($attributes['date_start'],$attributes['date_end']);

      $attributes['ProductSalePromotion']['date_start'] = $attributes['date_start'];
      $attributes['ProductSalePromotion']['date_end'] = $attributes['date_end'];

      if(!$this->exists) {
        $salePromotionType = new SalePromotionType;
        $attributes['ProductSalePromotion']['sale_promotion_type_id'] = $salePromotionType->getIdByalias('discount');

        $salePromotionTargetGroup = new SalePromotionTargetGroup;
        $attributes['ProductSalePromotion']['sale_promotion_target_group_id'] = $salePromotionTargetGroup->getIdByalias('consumer');
      }      

      unset($attributes['date_start']);
      unset($attributes['date_end']);

    }

    return parent::fill($attributes);

  }

  public function buildModelData() {

    $currency = new Currency;

    $salePromotionType = SalePromotionType::select('name')
    ->where('alias','like','discount')
    ->first()
    ->name;

    $price = Product::select('price')->find($this->product_id)->price;

    $percentDiscount = (int)round((($price - $this->reduced_price) * 100) / $price, 0);

    return array(
      'reduced_price' => $this->reduced_price,
      '_reduced_price' => $currency->format($this->reduced_price),
      'percentDiscount' => $percentDiscount.'%',
      'salePromotionType' => $salePromotionType
    );

  }

  public function buildFormData() {

    return array(
      'id' => $this->id,
      'reduced_price' => $this->reduced_price,
    );

  } 

}
