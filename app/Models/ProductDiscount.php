<?php

namespace App\Models;

use App\library\currency;

class ProductDiscount extends Model
{
  protected $table = 'product_discounts';
  protected $fillable = ['product_id','reduced_price'];
  protected $modelRelations = array('ProductSalePromotion');

  public $formHelper = true;
  public $modelData = true;

  protected $validation = array(
    'rules' => array(
      'ProductSalePromotion.date_start' => 'date_format:Y-m-d',
      'ProductSalePromotion.date_end' => 'date_format:Y-m-d|after:ProductSalePromotion.date_start',
    ),
    'messages' => array(
      'ProductSalePromotion.date_end.after' => 'วันที่สิ้นสุดโปรโมชั่นต้องเป็นวันที่ถัดไปของวันที่เริ่มต้นโปรโมชั่น',
    )
  );

  public function fill(array $attributes) {

    if(!empty($attributes)) {
     
      $attributes['ProductSalePromotion']['date_start'] = $attributes['date_start'];
      $attributes['ProductSalePromotion']['date_end'] = $attributes['date_end'];

      $salePromotionType = new SalePromotionType;
      $attributes['ProductSalePromotion']['sale_promotion_type_id'] = $salePromotionType->getIdByalias('discount');

      $salePromotionTargetGroup = new SalePromotionTargetGroup;
      $attributes['ProductSalePromotion']['sale_promotion_target_group_id'] = $salePromotionTargetGroup->getIdByalias('consumer');

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
      '_reduced_price' => $currency->format($this->reduced_price),
      'percentDiscount' => $percentDiscount.'%',
      'salePromotionType' => $salePromotionType
    );

  }

}
