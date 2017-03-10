<?php

namespace App\Models;

use App\library\url;
use App\library\cache;
use App\library\currency;
use Auth;

class Cart extends Model
{
  protected $table = 'carts';
  protected $fillable = ['person_id','shop_id','product_id','quantity'];

  public function addProduct($productId, $quantity) {

    $product = $this->getProduct($productId);

    $error = $this->checkProductError($product,$quantity);

    if($error['hasError']) {
      return $error;
    }

    $cart = Cart::where([
      ['person_id','=',session()->get('Person.id')],
      ['product_id','=',$productId]
    ])->first();

    // find shop id
    $shop = $product->getModelRelationData('ShopRelateTo',array(
      'first' => true,
      'fields' => array('shop_id')
    ))->shop;

    if(!empty($cart)) {
      // update quantity
      $saved = $cart->increment('quantity', $quantity);
    }else{

      $value = array(
        'person_id' => session()->get('Person.id'),
        'shop_id' => $shop->id,
        'product_id' => $productId,
        'quantity' => $quantity
      );

      $saved = Cart::fill($value)->save();
    }

    return $saved;

  }

  public function updateQuantity($id, $quantity) {

    $product = $this->getProduct($id);

    $error = $this->checkProductError($product,$quantity);

    if($error['hasError']) {
      return $error;
    }

    $cart = Cart::where([
      ['person_id','=',session()->get('Person.id')],
      ['product_id','=',$id]
    ])->first();

    $saved = false;
    if(!empty($cart)) {
      $cart->quantity = $quantity;
      $saved = $cart->save();
    }

    return $saved;

  }


  public function deleteProduct($id) {

    $cart = Cart::where([
      ['person_id','=',session()->get('Person.id')],
      ['product_id','=',$id]
    ])
    ->select('id')
    ->first();

    $success = false;
    if(!empty($cart)) {
      $success = $cart->delete();
    }

    return $success;

  }

  public function checkProductError($product,$quantity) {

    $error = array(
      'hasError' => false,
      'errorType' => false,
      'errorMessage' => ''
    );

    if(empty($product)) {
      $error = array(
        'hasError' => true,
        'errorType' => 1,
        'errorMessage' => 'ไม่พบสินค้า'
      );
    }elseif(!$product->active){
      $error = array(
        'hasError' => true,
        'errorType' => 2,
        'errorMessage' => 'สินค้าถูกปิดการสั่งซื้อชั่วคราว'
      );
    }elseif($product->quantity == 0) {
      $error = array(
        'hasError' => true,
        'errorType' => 3,
        'errorMessage' => 'สินค้าหมด'
      );
    }elseif($quantity > $product->quantity) {
      $error = array(
        'hasError' => true,
        'errorType' => 4,
        'errorMessage' => 'ไม่สามารถสั่งซื้อสินค้านี้ได้ สินค้ามีจำนวนน้อยกว่าจำนวนที่คุณสั่งซื้อ'
      );
    }elseif($product->minimum > $quantity) {
      $error = array(
        'hasError' => true,
        'errorType' => 5,
        'errorMessage' => 'ไม่สามารถสั่งซื้อสินค้านี้ได้ จำนวนสินค้าที่คุณสั่งซื้อน้อยกว่าจำนวนสั่งซื้อขั้นต่ำ โปรดแก้ไขจำนวนการสั่งซื้อสินค้านี้'
      );
    }

    return $error;

  }

  public function getProduct($productId) {

    $product = Product::where([
      ['id','=',$productId],
    ])
    ->select('id','name','price','minimum','product_unit','shipping_calculate_from','quantity','weight','active')
    ->first();

    if(empty($product)) {
      return null;
    }

    $product->attributes['promotion'] = $product->getPromotion();

    return $product;

  }

  public function getProductInfo($productId,$quantity) {

    $url = new Url;
    $cache = new Cache;

    $product = $this->getProduct($productId);

    if(empty($product)) {
      return null;
    }

    $image = $product->getModelRelationData('Image',array(
      'fields' => array('id','model','model_id','filename','image_type_id'),
      'first' => true
    ));

    $imageUrl = '/images/common/no-img.png';
    if(!empty($image)) {
      $imageUrl = $cache->getCacheImageUrl($image,'sm');
    }

    return array_merge(array(
      'id' => $product->id,
      'name' => $product->name,
      'minimum' => $product->minimum,
      'quantity' => $quantity,
      'product_unit' => $product->product_unit,
      'shipping_calculate_from' => $product->shipping_calculate_from,
      'price' => $this->getPrice($product,true),
      'savingPrice' => $this->getSavingPrice($product,$quantity,true),
      'subTotal' => $this->getProductSubTotal($product,$quantity,true),
      'shippingCost' => $this->getProductShippingCost($product,$quantity,true),
      'total' => $this->getProductTotal($product,$quantity,true),
      'imageUrl' => $imageUrl,
      'productDetailUrl' => $url->setAndParseUrl('product/detail/{id}',array('id' => $product->id)),
    ),$this->checkProductError($product,$quantity));

  }

  public function getPrice($product,$format = false) {
    $currency = new Currency;

    $price = $product->price;

    if(!empty($product->promotion)) {
      $price = $product->promotion['reduced_price'];
    }

    if($format) {
      $price = $currency->format($price);
    }

    return $price;
  }

  public function getSavingPrice($product,$quantity,$format = false) {

    $error = $this->checkProductError($product,$quantity);

    if($error['hasError']) {
      return false;
    }

    $currency = new Currency;

    $savingPrice = 0;

    if(!empty($product->promotion)) {
      $savingPrice = ($product->price - $product->promotion['reduced_price']) * $quantity;
    }

    if($format) {
      $savingPrice = $currency->format($savingPrice);
    }

    return $savingPrice;
  }

  public function getProductSubTotal($product,$quantity,$format = false) {

    $error = $this->checkProductError($product,$quantity);

    if($error['hasError']) {
      return false;
    }

    $currency = new Currency;

    $price = $product->price;

    if(!empty($product->promotion)) {
      $price = $product->promotion['reduced_price'];
    }

    $subTotal = $price * $quantity;

    if($format) {
      $subTotal = $currency->format($subTotal);
    }

    return $subTotal;

  }

  public function getProductTotal($product,$quantity,$format = false) {

    $error = $this->checkProductError($product,$quantity);

    if($error['hasError']) {
      return false;
    }

    $currency = new Currency;

    $price = $product->price;

    if(!empty($product->promotion)) {
      $price = $product->promotion['reduced_price'];
    }

    $total = $price * $quantity;
    // $total = $subTotal;

    if($product->shipping_calculate_from == 2) {

      $shipping = $product->getRalatedData('ProductShipping',array(
        'first' => true
      ));

      $shippingCost = $shipping->getShippingCost($product,$quantity);
      $total = $total + $shippingCost;

    }

    if($format) {
      $total = $currency->format($total);
    }

    return $total;

  }

  public function getProductShippingCost($product,$quantity,$format = false) {

    $currency = new Currency;

    $error = $this->checkProductError($product,$quantity);

    $shippingCost = 0;

    if(!$error['hasError']) {

      if($product->shipping_calculate_from == 2) {

        $shipping = $product->getRalatedData('ProductShipping',array(
          'first' => true
        ));

        $shippingCost = $shipping->getShippingCost($product,$quantity);

      }

    }

    if($format) {
      $shippingCost = $currency->format($shippingCost);
    }

    return $shippingCost;

  }

  public function getProductSummary() {

    $carts = $this->getCarts();

    $shopIds = array();
    if(!empty($carts)) {
      foreach ($carts as $cart) {
        if(!in_array($cart->shop_id, $shopIds)) {
          $shopIds[] = $cart->shop_id;
        }
      }
    }

    $data = array();
    if(!empty($shopIds)) {
      foreach ($shopIds as $shopId) {
        $data[] = array(
          'shop' => array(
            'id' => $shopId,
            'name' => Shop::select('name')->find($shopId)->name
          ),
          'products' => $this->getProducts($shopId),
          'summaries' => $this->getSummary($shopId)
        );
      }
    }

    return $data;

  }

  public function getSummary($shopId = null){

    $summaries = array(
      'subTotal' => 'getSummarySubTotal',
      'shippingCost' => 'getSummaryShippingCost',
      // 'savingPrice' => 'getSummarySavingPrice',
      'total' => 'getSummaryTotal'
    );

    $_summaries = array();
    foreach ($summaries as $alias => $fx) {
      $_summaries[$alias] = array(
        'value' => $this->{$fx}($shopId)
      );
    }

    return $_summaries;

  }

  public function getSummarySavingPrice($shopId = null) {

    $currency = new Currency;

    $carts = $this->getCarts($shopId);

    $savingPrice = 0;
    if(!empty($carts)) {

      foreach ($carts as $cart) {
        $product = $product = $this->getProduct($cart->product_id);

        $result = $this->getSavingPrice($product,$cart->quantity);

        if(empty($result)) {
          continue;
        }

        $savingPrice += $result;
      }

    }

    return $currency->format($savingPrice);

  }

  public function getSummarySubTotal($shopId = null) {

    $currency = new Currency;

    $carts = $this->getCarts($shopId);

    $subTotal = 0;
    if(!empty($carts)) {

      foreach ($carts as $cart) {
        $product = $product = $this->getProduct($cart->product_id);

        $result = $this->getProductSubTotal($product,$cart->quantity);

        if(empty($result)) {
          continue;
        }

        $subTotal += $result;
      }

    }

    return $currency->format($subTotal);

  }

  public function getSummaryShippingCost($shopId = null) {

    $currency = new Currency;

    $carts = $this->getCarts($shopId);

    $shippingCost = 0;
    if(!empty($carts)) {

      foreach ($carts as $cart) {
        $product = $product = $this->getProduct($cart->product_id);

        $result = $this->getProductShippingCost($product,$cart->quantity);

        if(empty($result)) {
          continue;
        }

        $shippingCost += $result;
      }

    }

    return $currency->format($shippingCost);

  }

  public function getSummaryTotal($shopId = null) {

    $currency = new Currency;

    $carts = $this->getCarts($shopId);

    $total = 0;
    if(!empty($carts)) {

      foreach ($carts as $cart) {
        $product = $product = $this->getProduct($cart->product_id);

        $result = $this->getProductTotal($product,$cart->quantity);

        if(empty($result)) {
          continue;
        }

        $total += $result;
      }

    }

    return $currency->format($total);

  }

  public function getProducts($shopId = null) {

    $carts = $this->getCarts($shopId);

    $products = array();
    if(!empty($carts)) {

      foreach ($carts as $cart) {

        $product = $this->getProductInfo($cart->product_id,$cart->quantity);

        if(!empty($product)) {
          $products[] = $product;
        }
  
      }

    }

    return $products;

  }

  public function productCount() {

    $carts = $this->getCarts();

    $count = 0;
    if(!empty($carts)) {
      foreach ($carts as $cart) {

        $product = $this->getProduct($cart->product_id);

        $error = $this->checkProductError($product,$cart->quantity);

        if($error['hasError']) {
          continue;
        }

        $count += $cart['quantity'];
        
      }
    }

    return $count;

  }

  public function hasProducts() {
    if(Auth::check()) {
      return $this->where('person_id','=',session()->get('Person.id'))->exists();
    }else {
      // Check from SESSION
    }
  }

  public function getCarts($shopId = null) {

    $carts = null;

    if(Auth::check()) {

      if(empty($shopId)) {
        $carts = $this->where('person_id','=',session()->get('Person.id'))->get();
      }else{
        $carts = $this->where([
          ['person_id','=',session()->get('Person.id')],
          ['shop_id','=',$shopId]
        ])->get();
      }
      
    }else{
      // Form SESSION
    }

    return $carts;

  }

  public function getShopId($productId) {
    return $this->where([
      ['product_id','=',$productId],
      ['person_id','=',session()->get('Person.id')]
    ])
    ->select('shop_id')
    ->first()
    ->shop_id;
  }

  public function setUpdatedAt($value) {}

}
