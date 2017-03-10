<?php

namespace App\Http\Controllers;

use App\library\service;

class CheckoutController extends Controller
{
  public function checkout() {
    
    $cart = Service::loadModel('Cart');

    $this->setData('data',$cart->getProductSummary());

    return $this->view('pages.checkout.checkout');

  }
}
