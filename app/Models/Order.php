<?php

namespace App\Models;

class Order extends Model
{
  protected $table = 'orders';
  protected $fillable = ['invoice_prefix','invoice_number','shop_id','person_id','person_name','shipping_address','message_to_seller','order_status_id'];

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  public function getInvoicePrefix() {
    return 'INV';
  }

  public function getInvoiceNumber($shopId) {

    $latestOrder = $this
    ->select('id','invoice_number')
    ->orderBy('id','DESC')
    ->where('shop_id','=',$shopId)
    ->first();

    if(empty($latestOrder)) {
      return 1;
    }

    return $latestOrder->invoice_number + 1;

  }

  public function countProduct() {
    
  }

}
