<?php

namespace App\Models;

use App\library\date;

class ProductSalePromotion extends Model
{
  protected $table = 'product_sale_promotions';
  protected $fillable = ['product_id','sale_promotion_target_group_id','sale_promotion_type_id','model','model_id','date_start','date_end'];

  public function productDiscount() {
    return $this->hasOne('App\Models\ProductDiscount','id','model_id');
  }

  public function __saveRelatedData($model,$options = array()) {

    $productSalePromotion = $model->getModelRelationData('ProductSalePromotion',
      array(
        'first' => true
      )
    );

    if(!empty($productSalePromotion)){
      return $productSalePromotion
      ->fill($options['value'])
      ->save();
    }else{

      $options['value']['product_id'] = $model->product_id;

      return $this->fill($model->includeModelAndModelId($options['value']))->save();
    }

  }

  public function calRemainingDays() {
  
    $secs = strtotime($this->date_start) - time();
    $mins = (int)floor($secs / 60);
    $hours = (int)floor($mins / 60);
    $days = (int)floor($hours / 24);

    if($days == 0) {
      $remainingSecs = $secs % 60;
      $remainingMins = $mins % 60;
      $remainingHours = $hours % 24;

      $remaining = array();
      if($remainingHours != 0) {
        $remaining[] = $remainingHours.' ชั่วโมง';
      }

      if($remainingMins != 0) {
        $remaining[] = $remainingMins.' นาที';
      }

      // if($remainingSecs != 0) {
      //   $remaining[] = $remainingSecs.' วินาที';
      // }

      $remaining = implode(' ', $remaining);

    }else{
      $remaining = $days.' วัน';
    }

    return $remaining;

  }

  public function buildModelData() {

    $date = new Date;

    return array_merge(array(
      // 'sale_promotion_type_id' => $this->sale_promotion_type_id,
      '_date_start' => $date->covertDateToSting($this->date_start),
      '_date_end' => $date->covertDateToSting($this->date_end),
    ),$this->{lcfirst($this->model)}->buildModelData());

  }

}
