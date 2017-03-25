<?php

namespace App\Models;

class OrderPaymentConfirm extends Model
{
  protected $table = 'order_payment_confirms';
  protected $fillable = ['order_id','payment_method_id','description'];
  // protected $directory = true;

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
      'payment_method_id' => 'required',
      'payment_date' => 'required|date_format:Y-m-d H:i:s',
      'payment_time' => 'required|date_format:H:i:s'
    ),
    'messages' => array(
      'payment_method_id.required' => 'วิธีที่คุณชำระเงินห้ามว่าง',
      'payment_date.date_format' => 'วันที่ชำระเงินไม่ถูกต้อง',
    )
  ); 

  public function setUpdatedAt($value) {}
}
