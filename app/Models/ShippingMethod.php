<?php

namespace App\Models;

use App\library\currency;

class ShippingMethod extends Model
{
  protected $table = 'shipping_methods';
  protected $fillable = ['name','shipping_service_id','description','shipping_service_cost_type_id','free_service','service_cost','shipping_time','person_id'];
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
    )
  );

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
    ->select($this->getTable().'.*')
    ->get();

    $shop = Shop::select('customer_can_pickup_item')->find($shopId);

    $_shippingMethods = array();

    if($shop->customer_can_pickup_item) {
      $_shippingMethods[] = array(
        'id' => 0,
        'name' => 'รับสินค้าเอง',
        'shippingService' => '-',
        'shipping_time' => '-',
        'shippingServiceCostType' => '-',
        'serviceCostText' => '-',
        'select' => false
      );
    }

    $select = true;
    foreach ($shippingMethods as $shippingMethod) {
      $_shippingMethods[] = array_merge($shippingMethod->buildPaginationData(),array(
        'select' => $select
      ));
      $select = false;
    }

    return $_shippingMethods;
  }

  public function hasShippingMethodChoice($shopId) {

    $shippingMethods = $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $this->getTable().'.id')
    ->where([
      ['shop_relate_to.model','like',$this->modelName],
      ['shop_relate_to.shop_id','=',$shopId]
    ])
    ->select($this->getTable().'.*')
    ->exists();

    $shop = Shop::select('customer_can_pickup_item')->find($shopId);

    if($shop->customer_can_pickup_item || $shippingMethods) {
      return true;
    }

    return false;
    
  }

  public function getShippingMethod($shopId,$build = false) {
    $shippingMethods = $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $this->getTable().'.id')
    ->where([
      ['shop_relate_to.model','like',$this->modelName],
      ['shop_relate_to.shop_id','=',$shopId]
    ])
    ->select($this->getTable().'.*')
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

  public function buildModelData() {
    return array(
      'id' => $this->id,
      'name' => $this->name,
      'shippingService' => $this->shippingService->name
    );
  }

  public function buildPaginationData() {

    $currency = new Currency;

    $serviceCostText = '';
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

    return array(
      'id' => $this->id,
      'name' => $this->name,
      'shippingService' => $this->shippingService->name,
      'shippingServiceCostType' => $this->shippingServiceCostType->name,
      'serviceCostText' => $serviceCostText,
      'shipping_time' => !empty($this->shipping_time) ? $this->shipping_time : 'ไม่ระบุ'
    );

  }
  
}
