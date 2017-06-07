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

  private $paymentTypeToAdditionalData = array(
    'bank-transfer' => array(
      'account_type'
    ),
    'promptpay' => array(
      'promptpay_transfer_number_type'
    )
  );

  private $additionalData = array(
    'account_type' => array(
      'saving-deposit-account' => 'ออมทรัพย์',
      // 'fixed-deposit-account' => 'ฝากประจำ',
      // 'current-account' => 'กระแสรายวัน',
    ),
    'promptpay_transfer_number_type' => array(
      'tel-no' => 'หมายเลขโทรศัพท์',
      'id-card-no' => 'เลขบัตรประชาชน',
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

  public function getAdditionalDataByPaymentType($type = null) {

    if(empty($type) || empty($this->paymentTypeToAdditionalData[$type])) {
      return null;
    }

    $data = array();
    foreach ($this->paymentTypeToAdditionalData[$type] as $index) {
      $data[$index] = $this->getAdditionalData($index);
    }

    return $data;
  }

  public function getAdditionalData($index = null) {

    if(empty($index) || empty($this->additionalData[$index])) {
      return null;
    }

    return $this->additionalData[$index];

  }

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
    $providerLogo = null;
    if(!empty($this->payment_service_provider_id)) {

      $provider = PaymentServiceProvider::select('name','logo')->find($this->payment_service_provider_id);

      $providerName = $provider->name;

      if(!empty($provider->logo)) {
        $providerLogo = '/images/service_provider_logo/'.$provider->logo;
      }

    }

    $data = array(
      'id' => $this->id,
      'name' => $this->name,
      'description' => nl2br($this->description),
      'providerName' => $providerName,
      'providerLogo' => $providerLogo
    );

    $additionalData = $this->extractAdditionalData();

    if(empty($additionalData)) {
      return $data;
    }

    return array_merge($data,$additionalData);

  }

  private function extractAdditionalData() {

    if(empty($this->additional_data)) {
      return null;
    }

    $additionalData = json_decode($this->additional_data,true);

    foreach ($additionalData as $index => $value) {

      if(!empty($this->additionalData[$index])) {
        $additionalData[$index] = $this->additionalData[$index][$value];
      }

    }

    return $additionalData;

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
      array(
        'additional_data' => $additionalData
      )
    );
  }

  public function buildPaymentMethodName($paymentMethodType,$value) {

    switch ($paymentMethodType) {
      case 'bank-transfer':
          $this->name = PaymentServiceProvider::select('name')->find($value['payment_service_provider_id'])->name.' สาขา '.$value['additional_data']['branch_name'].' เลขที่บัญชี '.$value['additional_data']['account_number'];
        break;
      
      case 'promptpay':
          $additionalData = $this->getAdditionalData($paymentMethodType,'promptpay_transfer_number_type');
          $this->name = 'โอนเงินด้วย'.$additionalData[$value['additional_data']['promptpay_transfer_number_type']].' '.$value['additional_data']['promptpay_transfer_number'];
        break;

      case 'paypal':
          $this->name = 'ชำระเงินไปยังบัญชี '.$value['additional_data']['paypal_account'];
        break;
    }

    if(!$this->exists) {
      $this->payment_method_type_id = PaymentMethodType::select('id')->where('alias','like',$paymentMethodType)->first()->id;      
    }

    $this->payment_service_provider_id = !empty($value['payment_service_provider_id']) ? $value['payment_service_provider_id'] : null;

  }

}
