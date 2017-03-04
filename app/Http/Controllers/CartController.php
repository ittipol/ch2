<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\url;
use App\library\cache;
use App\library\currency;
use Input;

class CartController extends Controller
{
  public function index() {

    $cart = Service::loadModel('Cart');

    $this->setData('summaries',$cart->getProductSummary());

    return $this->view('pages.cart.cart');

  }

  public function cartUpdateQuantity() {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      exit();
    }

    $success = Service::loadModel('Cart')->updateQuantity(Input::get('productId'),Input::get('quantity'));

    // Get Summary after update 

    $result = array(
      'success' => $success
    );

    return response()->json($result);

  }

  public function cartDelete() {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      $this->error = array(
        'message' => 'ขออภัย ไม่อนุญาตให้เข้าถึงหน้านี้ได้'
      );
      return $this->error();
    }

    $productId = Input::get('productId');

    $cartModel = Service::loadModel('Cart');

    $shopId = $cartModel::where([
      ['person_id','=',session()->get('Person.id')],
      ['product_id','=',$productId]
    ])
    ->select('shop_id')
    ->first()
    ->shop_id;

    // delete cart
    $success = $cartModel->deleteProduct($productId);

    if(!$success) {
      $result = array(
        'success' => false,
      );
      return response()->json($result);
    }

    // after deleting check cart empty
    $total = $cartModel->hasProducts();

    if($total) { // not empty
      $totalShopProduct = $cartModel::where([
        ['person_id','=',session()->get('Person.id')],
        ['shop_id','=',$shopId]
      ])->exists();
    }

    $result = array(
      'success' => true,
      'empty' => !$total,
      'totalShopProductEmpty' => !$totalShopProduct,
      'shopId' => $shopId,
      // 'total' => 
    );

    return response()->json($result);

  }

}