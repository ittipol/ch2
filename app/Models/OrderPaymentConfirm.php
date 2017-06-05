<?php

namespace App\Models;

use App\library\currency;
use App\library\date;

class OrderPaymentConfirm extends Model
{
  protected $table = 'order_payment_confirms';
  protected $fillable = ['order_id','payment_method_id','payment_method_name','payment_amount','payment_date','description','confirmed'];
  protected $modelRelations = array('Image');

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  public $imageTypes = array(
    'photo' => array(
      'limit' => 5
    )
  );

  protected $validation = array(
    'rules' => array(
      'payment_method_id' => 'required',
      'payment_amount' => 'required|regex:/^[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$/',
      'payment_date' => 'required|date_format:Y-m-d H:i:s',
    ),
    'messages' => array(
      'payment_method_id.required' => 'วิธีที่คุณชำระเงินห้ามว่าง',
      'payment_amount.required' => 'จำนวนเงินห้ามว่าง',
      'payment_amount.regex' => 'จำนวนเงินไม่ถูกต้อง',
      'payment_date.date_format' => 'วันที่ชำระเงินไม่ถูกต้อง',
    )
  );

  public function paymentMethod() {
    return $this->hasOne('App\Models\PaymentMethod','id','payment_method_id');
  }

  public static function boot() {

    parent::boot();

    OrderPaymentConfirm::saving(function($orderPaymentConfirm){
      if($orderPaymentConfirm->state == 'create') {
        $orderPaymentConfirm->confirmed = 0;
      }
    });

  }

  public function buildModelData() {

    $currency = new Currency;
    $date = new Date;

    return array(
      'paymentMethodName' => $this->payment_method_name, 
      'paymentAmount' => $currency->format($this->payment_amount),
      'paymentDate' => $date->covertDateToSting($this->payment_date),
      'paymentTime' => $date->covertTimeToSting($this->payment_date),
      'description' => !empty($this->description) ? $this->description : '-'
    );
  }

}
