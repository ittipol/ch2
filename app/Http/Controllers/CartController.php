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

    $this->setData('data',$cart->getProductSummary());

    return $this->view('pages.cart.cart');

  }

  public function cartUpdateQuantity() {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      exit();
    }

    $cartModel = Service::loadModel('Cart');

    $productId = Input::get('productId');
    $quantity = Input::get('quantity');

    $success = $cartModel->updateQuantity($productId,$quantity);

    $result = array(
      'success' => $success,
      'productTotal' => $cartModel->getProductTotal($productId,$quantity),
      'summaries' => $cartModel->getSummary($cartModel->getShopId($productId))
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

    $cartModel = Service::loadModel('Cart');

    $productId = Input::get('productId');
    $shopId = $cartModel->getShopId($productId);

    // Delete
    $success = $cartModel->deleteProduct($productId);

    if(!$success) {
      $result = array(
        'success' => false,
      );
      return response()->json($result);
    }

    // after deleting check cart empty
    $total = $cartModel->hasProducts();

    $dataMerge = array();
    if($total) { // not empty

      $dataMerge = array(
        'totalShopProductEmpty' => !$cartModel::where([
          ['person_id','=',session()->get('Person.id')],
          ['shop_id','=',$shopId]
        ])->exists()
      );

    }else{ // is empty

      $dataMerge = array(
        'html' => view('pages.cart.cart_empty')->render()
      );

    }

    $result = array_merge(array(
      'success' => true,
      'empty' => !$total,
    ),$dataMerge);

    return response()->json($result);

  }

}
