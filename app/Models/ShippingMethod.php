<?php

namespace App\Models;

use App\library\currency;

class ShippingMethod extends Model
{
  protected $table = 'shipping_methods';
  protected $fillable = ['name','shipping_service_id','description','shipping_service_cost_type_id','free_service','service_cost','shipping_time','special','special_alias','sort','person_id'];
  protected $modelRelations = array('ShopRelateTo');

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
      'shipping_service_id' => 'required',
      'shipping_service_cost_type_id' => 'required',
      'service_cost' => 'required|numeric',
    ),
    'messages' => array(
      'name.required' => 'ชื่อวิธีการจัดส่งสินค้าห้ามว่าง',
      'shipping_service_id.required' => 'ผู้ให้บริการการจัดส่งห้ามว่าง',
      'shipping_service_cost_type_id.required' => 'รูปแบบการคิดค่าจัดส่งห้ามว่าง',
      'service_cost.required' => 'ค่าจัดส่งห้ามว่าง',
      'service_cost.numeric' => 'รูปแบบค่าจัดส่งไม่ถูกต้อง'
    ),
    'conditions' => array(
      'service_cost' => array(
        'field' => 'shipping_service_cost_type_id',
        'value' => 2,
      )
    ),
    'modelData' => array(
      'shipping_service_id' => array(
        'field' => 'special',
        'value' => 0
      ),
      'shipping_service_cost_type_id' => array(
        'field' => 'special',
        'value' => 0
      )
    )
  );

  // public function fill(array $attributes) {

  //   if(!empty($attributes)) {
      
  //     if(!$this->special || ($this->special_alias != 'picking-up-item')) {
  //       unset($attributes['RelateToBranch']);
  //     }

  //   }

  //   return parent::fill($attributes);

  // }

  public function shippingService() {
    return $this->hasOne('App\Models\ShippingService','id','shipping_service_id');
  }

  public function shippingServiceCostType() {
    return $this->hasOne('App\Models\ShippingServiceCostType','id','shipping_service_cost_type_id');
  }

  public static function boot() {

    parent::boot();

    // before saving
    ShippingMethod::saving(function($model){

      if($model->state == 'create') {

        if(empty($model->sort)) {
          $model->sort = 9;
        }

        if(empty($model->special)) {
          $model->special = 0;
        }

      }

      switch ($model->shipping_service_cost_type_id) {
        case 2:
          $model->free_service = null;
          break;

        case 3:
          $model->free_service = 1;
          $model->service_cost = null;
          break;

        default:
            $model->free_service = null;
            $model->service_cost = null;
          break;
      }

    });

  }

  public function getShippingMethodChoice($shopId) {
    $shippingMethods = $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $this->getTable().'.id')
    ->where([
      ['shop_relate_to.model','like',$this->modelName],
      ['shop_relate_to.shop_id','=',$shopId]
    ])
    ->orderBy($this->getTable().'.sort','ASC')
    ->select($this->getTable().'.*')
    ->get();

    $select = true;
    $_shippingMethods = array();
    foreach ($shippingMethods as $shippingMethod) {
      $_shippingMethods[] = array_merge($shippingMethod->buildPaginationData(),array(
        'select' => $select
      ));
      $select = false;
    }

    return $_shippingMethods;
  }

  public function hasShippingMethod($shopId) {

    $shippingMethods = $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $this->getTable().'.id')
    ->where([
      ['shop_relate_to.model','like',$this->modelName],
      ['shop_relate_to.shop_id','=',$shopId]
    ])
    ->select($this->getTable().'.*')
    ->exists();

    if($shippingMethods) {
      return true;
    }

    return false;
    
  }

  public function checkShippingMethodExistById($id,$shopId) {
    return $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $this->getTable().'.id')
    ->where([
      [$this->getTable().'.id','=',$id],
      ['shop_relate_to.model','like',$this->modelName],
      ['shop_relate_to.shop_id','=',$shopId]
    ])
    ->exists();
  }

  public function getShippingMethods($shopId,$build = false) {
    $shippingMethods = $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $this->getTable().'.id')
    ->where([
      ['shop_relate_to.model','like',$this->modelName],
      ['shop_relate_to.shop_id','=',$shopId]
    ])
    ->select($this->getTable().'.*')
    ->orderBy($this->getTable().'.sort','ASC')
    ->get();

    if(!$build) {
      return $shippingMethods;
    }

    $_shippingMethods = array();
    foreach ($shippingMethods as $shippingMethod) {
      $_shippingMethods[] = $shippingMethod->buildModelData();
    }

    return $_shippingMethods;
  }

  public function getSpecialShippingMethods($shopId,$build = false) {
    $shippingMethods = $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $this->getTable().'.id')
    ->where([
      ['shop_relate_to.model','like',$this->modelName],
      ['shop_relate_to.shop_id','=',$shopId],
      [$this->getTable().'.special','=',1]
    ])
    ->select($this->getTable().'.*')
    ->orderBy($this->getTable().'.sort','ASC')
    ->get();

    if(!$build) {
      return $shippingMethods;
    }

    $_shippingMethods = array();
    foreach ($shippingMethods as $shippingMethod) {
      $_shippingMethods[] = $shippingMethod->buildModelData();
    }

    return $_shippingMethods;
  }

  public function getSpecificSpecialShippingMethods($alias,$shopId,$build = false) {
    $shippingMethod = $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $this->getTable().'.id')
    ->where([
      ['shop_relate_to.model','like',$this->modelName],
      ['shop_relate_to.shop_id','=',$shopId],
      [$this->getTable().'.special','=',1],
      [$this->getTable().'.special_alias','=',$alias]
    ])
    ->select($this->getTable().'.*')
    ->first();

    if(!$build || empty($shippingMethod)) {
      return $shippingMethod;
    }

    return $shippingMethod->buildModelData();
  }

  public function hasSpecialShippingMethod($alias,$shopId) {
    return $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $this->getTable().'.id')
    ->where([
      ['shop_relate_to.model','like',$this->modelName],
      ['shop_relate_to.shop_id','=',$shopId],
      [$this->getTable().'.special','=',1],
      [$this->getTable().'.special_alias','=',$alias]
    ])
    ->exists();
  }

  public function buildModelData() {

    $currency = new Currency;

    $serviceCostText = '-';
    $shippingService = '-';
    $shippingServiceCostType = '-';
    
    switch ($this->shipping_service_cost_type_id) {
      case 1:
          $serviceCostText = '-';
        break;
      
      case 2:
          $serviceCostText = $currency->format($this->service_cost);
        break;

      case 3:
          $serviceCostText = $currency->format(0);
        break;
    }

    if(!empty($this->shippingService)) {
      $shippingService = $this->shippingService->name;
    }

    if(!empty($this->shippingServiceCostType)) {
      $shippingServiceCostType = $this->shippingServiceCostType->name;
    }

    return array(
      'id' => $this->id,
      'name' => $this->name,
      'shippingService' => $shippingService,
      'shippingServiceCostType' => $shippingServiceCostType,
      'serviceCostText' => $serviceCostText,
      'shipping_time' => !empty($this->shipping_time) ? $this->shipping_time : 'ไม่ระบุ'
    );
  }

  public function buildPaginationData() {

    $currency = new Currency;

    $serviceCostText = '-';
    $shippingService = '-';
    $shippingServiceCostType = '-';

    switch ($this->shipping_service_cost_type_id) {
      case 1:
          $serviceCostText = '-';
        break;
      
      case 2:
          $serviceCostText = $currency->format($this->service_cost);
        break;

      case 3:
          $serviceCostText = $currency->format(0);
        break;
    }

    if(!empty($this->shippingService)) {
      $shippingService = $this->shippingService->name;
    }

    if(!empty($this->shippingServiceCostType)) {
      $shippingServiceCostType = $this->shippingServiceCostType->name;
    }

    return array(
      'id' => $this->id,
      'name' => $this->name,
      'free_service' => $this->free_service,
      'service_cost' => $this->service_cost,
      'shippingService' => $shippingService,
      'shippingServiceCostType' => $shippingServiceCostType,
      'serviceCostText' => $serviceCostText,
      'shipping_time' => !empty($this->shipping_time) ? $this->shipping_time : 'ไม่ระบุ'
    );

  }
  
}
