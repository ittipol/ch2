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

    $productOptionValueId = null;
    if(!empty(Input::get('productOptionValueId'))) {
      $productOptionValueId = Input::get('productOptionValueId');
    }

    $product = $cartModel->getProduct($productId,$productOptionValueId);

    $error = $cartModel->checkProductError($product,$quantity);

    if($error['hasError']) {
      return $error;
    }

    $updated = $cartModel->updateQuantity($productId,$quantity,$productOptionValueId);

    $result = array(
      'success' => $updated,
      'productTotal' => $cartModel->getProductTotal($product,$quantity,true),
      'shippingCostTotal' => $cartModel->getProductShippingCost($product,$quantity,true),
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

    $productOptionValueId = null;
    if(!empty(Input::get('productOptionValueId'))) {
      $productOptionValueId = Input::get('productOptionValueId');
    }

    $shopId = $cartModel->getShopId($productId);

    // Delete
    $success = $cartModel->deleteProduct($productId,$productOptionValueId);

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

      $totalShopProductEmpty = $cartModel::where([
        ['created_by','=',session()->get('Person.id')],
        ['shop_id','=',$shopId]
      ])->exists();

      $summaries = array();
      if($totalShopProductEmpty) {
        $summaries = $cartModel->getSummary($shopId);
      }

      $dataMerge = array(
        'totalShopProductEmpty' => !$totalShopProductEmpty,
        'summaries' => $summaries
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
