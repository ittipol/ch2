<?php

namespace App\Http\Controllers;

use App\library\service;

class CheckoutController extends Controller
{
  public function checkout() {
    
    $address = Service::loadModel('Address')->where([
      ['model','like','Person'],
      ['model_id','=',session()->get('Person.id')]
    ])
    ->first()
    ->getAddress();

    $this->setData('data',Service::loadModel('Cart')->getProductSummary());
    $this->setData('address',$address);

    return $this->view('pages.checkout.checkout');

  }

  public function checkoutSubmit() {
    
    $checkouts = request()->all();

    // Get cart
    $products = Service::loadModel('Cart')->getCart();

    $shopIds = array();
    if(!empty($products)) {
      foreach ($products as $cart) {
        if(!in_array($cart['shopId'], $shopIds)) {
          $shopIds[] = $cart['shopId'];
        }
      }
    }
dd($products);
    // check address not empty
    dd($checkouts);
  }

}
