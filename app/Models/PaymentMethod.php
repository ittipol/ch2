<?php

namespace App\Models;

class PaymentMethod extends Model
{
  public $table = 'payment_methods';
  protected $fillable = ['name','description','person_id'];
  protected $modelRelations = array('Image','PaymentMethodToOrder','ShopRelateTo');

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
      'description' => 'required',
    ),
    'messages' => array(
      'name.required' => 'ชื่อวิธีการชำระเงินห้ามว่าง',
      'description.required' => 'รายละเอียดวิธีการชำระเงินห้ามว่าง',
    )
  );

  public function getPaymentMethod($shopId) {
    return $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $this->getTable().'.id')
    ->where([
      ['shop_relate_to.model','like',$this->modelName],
      ['shop_relate_to.shop_id','=',$shopId]
    ])
    ->select($this->getTable().'.id',$this->getTable().'.name')
    ->orderBy($this->getTable().'.name','ASC')
    ->get();
  }

  public function hasPaymentMethod($shopId) {
    return $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $this->getTable().'.id')
    ->where([
      ['shop_relate_to.model','like',$this->modelName],
      ['shop_relate_to.shop_id','=',$shopId]
    ])
    ->select($this->getTable().'.id',$this->getTable().'.name')
    ->orderBy($this->getTable().'.name','ASC')
    ->exists();
  }

}
