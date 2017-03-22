<?php

namespace App\Models;

class ShippingMethod extends Model
{
  protected $table = 'shipping_methods';
  protected $fillable = ['name','shipping_service_id','description','person_id'];
  protected $modelRelations = array('ShopRelateTo');

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
      'shipping_service_id' => 'required',
    ),
    'messages' => array(
      'name.required' => 'ชื่อวิธีการจัดส่งสินค้าห้ามว่าง',
      'shipping_service_id.required' => 'ผู้ให้บริการการจัดส่งห้ามว่าง',
    )
  );

  public function shippingService() {
    return $this->hasOne('App\Models\ShippingService','id','shipping_service_id');
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
        'select' => false
      );
    }

    $select = true;
    foreach ($shippingMethods as $shippingMethod) {
      $_shippingMethods[] = array_merge($shippingMethod->buildModelData(),array(
        'select' => $select
      ));
      $select = false;
    }

    return $_shippingMethods;
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
  
}
