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
      'description' => 'required'
    ),
    'messages' => array(
      'payment_method_id.required' => 'วิธีที่คุณชำระเงินห้ามว่าง',
      'description.required' => 'รายละเอียดการชำระเงินห้ามว่าง',
    )
  ); 

  public function setUpdatedAt($value) {}
}
