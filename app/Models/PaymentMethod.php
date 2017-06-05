<?php

namespace App\Models;

class PaymentMethod extends Model
{
  public $table = 'payment_methods';
  protected $fillable = ['payment_method_type_id','payment_service_provider_id','name','description','additional_data','created_by'];
  protected $modelRelations = array('ShopRelateTo');

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  public $imageTypes = array(
    'photo' => array(
      'limit' => 10
    )
  );

  public $promptpayTransferNumberType = array(
    'tel-no' => 'หมายเลขโทรศัพท์',
    'id-card-no' => 'เลขบัตรประชาชน',
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

  public function paymentMethodType() {
    return $this->hasOne('App\Models\PaymentMethodType','id','payment_method_type_id');
  }

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

  public function getPaymentMethodByPaymentMethodTypeId($shopId,$paymentMethodTypeId) {
    return $this
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', $this->getTable().'.id')
    ->where([
      ['shop_relate_to.model','like',$this->modelName],
      ['shop_relate_to.shop_id','=',$shopId],
      ['payment_method_type_id','=',$paymentMethodType->id]
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

    $providerName = null;
    if(!empty($this->payment_service_provider_id)) {
      $providerName = PaymentServiceProvider::select('name')->find($this->payment_service_provider_id)->name;
    }

    $data = array(
      'id' => $this->id,
      'name' => $this->name,
      'description' => nl2br($this->description),
      'providerName' => $providerName
    );

    $additionalData = json_decode($this->additional_data,true);

    if(empty($additionalData)) {
      return $data;
    }

    return array_merge($data,$additionalData);

  }

  public function buildPaginationData() {
    return array(
      'id' => $this->id,
      'name' => $this->name,
      'description' => nl2br($this->description),
    );
  }

  public function buildFormData() {

    $additionalData = json_decode($this->additional_data,true);

    if(empty($additionalData)) {
      return $this->getAttributes();
    }

    return array_merge(
      $this->getAttributes(),
      $additionalData
    );
  }

  public function buildPaymentMethodName($paymentMethodType,$value) {

    switch ($paymentMethodType) {
      case 'bank-transfer':

          $this->name = PaymentServiceProvider::select('name')->find($value['payment_service_provider_id'])->name.' สาขา '.$value['branch_name'].' เลขที่บัญชี '.$value['account_number'];
          
          $this->additional_data = json_encode(array(
            'branch_name' => $value['branch_name'],
            'account_number' => $value['account_number'],
          ));

        break;
      
      case 'promptpay':

          $this->name = 'โอนเงินด้วย'.$this->promptpayTransferNumberType[$value['promptpay_transfer_number_type']].' '.$value['promptpay_transfer_number'];

          $this->additional_data = json_encode(array(
            'promptpay_transfer_number_type' => $value['promptpay_transfer_number_type'],
            'promptpay_transfer_number' => $value['promptpay_transfer_number'],
          ));

        break;

      case 'paypal':

          $this->name = 'ชำระเงินไปยังบัญชี '.$value['paypal_account'];

          $this->additional_data = json_encode(array(
            'paypal_account' => $value['paypal_account'],
          ));

        break;
    }

    if(!$this->exists) {
      $this->payment_method_type_id = PaymentMethodType::select('id')->where('alias','like',$paymentMethodType)->first()->id;      
    }

    $this->payment_service_provider_id = !empty($value['payment_service_provider_id']) ? $value['payment_service_provider_id'] : null;

  }

}
