<?php

namespace App\Models;

class PaymentMethod extends Model
{
  public $table = 'payment_methods';
  protected $fillable = ['payment_method_type_id','name','description','additional_data','created_by'];
  protected $modelRelations = array('PaymentMethodToOrder','ShopRelateTo');

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  public $imageTypes = array(
    'photo' => array(
      'limit' => 10
    )
  );

  // protected $validation = array(
  //   'rules' => array(
  //     'name' => 'required|max:255',
  //     'description' => 'required',
  //   ),
  //   'messages' => array(
  //     'name.required' => 'ชื่อวิธีการชำระเงินห้ามว่าง',
  //     'description.required' => 'รายละเอียดวิธีการชำระเงินห้ามว่าง',
  //   )
  // );

  public function getPaymentMethod($shopId) {
    return $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $this->getTable().'.id')
    ->where([
      ['shop_relate_to.model','like',$this->modelName],
      ['shop_relate_to.shop_id','=',$shopId]
    ])
    ->select($this->getTable().'.*')
    ->orderBy($this->getTable().'.name','ASC')
    ->get();
  }

  public function checkPaymentMethodExistById($id,$shopId) {
    return $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $this->getTable().'.id')
    ->where([
      [$this->getTable().'.id','=',$id],
      ['shop_relate_to.model','like',$this->modelName],
      ['shop_relate_to.shop_id','=',$shopId]
    ])
    ->exists();
  }

  public function hasPaymentMethod($shopId) {
    return $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $this->getTable().'.id')
    ->where([
      ['shop_relate_to.model','like',$this->modelName],
      ['shop_relate_to.shop_id','=',$shopId]
    ])
    ->exists();
  }

  public function buildModelData() {

    $additionalData = json_decode($this->additional_data,true);
dd($additionalData);
    dd('hesx');
  }

  public function buildPaginationData() {
    return array(
      'id' => $this->id,
      'name' => $this->name,
      'description' => nl2br($this->description),
    );
  }

}
