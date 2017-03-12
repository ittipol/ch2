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

  private $checkError = true;

  public function addProduct($productId, $quantity) {

    $product = $this->getProduct($productId);

    $error = $this->checkProductError($product,$quantity);

    if($error['hasError']) {
      return $error;
    }

    // find shop id
    $shop = $product->getModelRelationData('ShopRelateTo',array(
      'first' => true,
      'fields' => array('shop_id')
    ))->shop;

    if(Auth::check()) {

      $cart = Cart::where([
        ['person_id','=',session()->get('Person.id')],
        ['product_id','=',$productId]
      ])
      ->select('id')
      ->first();

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

    }else{

      if(session()->has('cart.'.$productId)) {
        $product = session()->get('cart.'.$productId);
        $product['quantity'] += $quantity;
        session()->put('cart.'.$productId,$product);
      }else{
        session()->put('cart.'.$productId,array(
          'shopId' => $shop->id,
          'productId' => $productId,
          'quantity' => $quantity
        ));
      }
      
      $saved = true;
    }

    return $saved;

  }

  public function updateQuantity($id, $quantity) {

    $product = $this->getProduct($id);

    $error = $this->checkProductError($product,$quantity);

    if($error['hasError']) {
      return $error;
    }

    if(Auth::check()) {

      $cart = Cart::where([
        ['person_id','=',session()->get('Person.id')],
        ['product_id','=',$id]
      ])->first();

      $updated = false;
      if(!empty($cart)) {
        $cart['quantity'] = $quantity;
        $updated = $cart->save();
      }

    }else{

      $product = session()->get('cart.'.$id);
      $product['quantity'] = $quantity;
      session()->put('cart.'.$id,$product);

      $updated = true;

    }

    return $updated;

  }


  public function deleteProduct($id) {

    if(Auth::check()) {
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
    }else{
      session()->forget('cart.'.$id);
      $success = true;
    }

    return $success;

  }

  public function checkProductError($product,$quantity) {

    $error = array(
      'hasError' => false,
      'errorType' => false,
      'errorMessage' => ''
    );

    if(!$this->checkError) {
      return $error;
    }

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
        'errorMessage' => 'ไม่สามารถสั่งซื้อสินค้านี้ได้ จำนวนสินค้าที่คุณสั่งซื้อน้อยกว่าจำนวนสั่งซื้อขั้นต่ำ'
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
    }else{
      $promotion = $product->getPromotion();

      if(!empty($promotion)) {
        $price = $promotion['reduced_price'];
      }
    }

    if($format) {
      $price = $currency->format($price);
    }

    return $price;
  }

  public function getSavingPrice($product,$quantity,$format = false) {

    $currency = new Currency;

    $error = $this->checkProductError($product,$quantity);

    $savingPrice = 0;

    if(!$error['hasError']) {

      $savingPrice = ($product->price - $this->getPrice($product)) * $quantity;

    }

    if($format) {
      $savingPrice = $currency->format($savingPrice);
    }

    return $savingPrice;
  }

  public function getProductSubTotal($product,$quantity,$format = false) {

    $currency = new Currency;

    $error = $this->checkProductError($product,$quantity);

    $subTotal = 0;

    if(!$error['hasError']) {
      $subTotal = $this->getPrice($product) * $quantity;
    }

    if($format) {
      $subTotal = $currency->format($subTotal);
    }

    return $subTotal;

  }

  public function getProductTotal($product,$quantity,$format = false) {

    $currency = new Currency;

    $error = $this->checkProductError($product,$quantity);

    $total = 0;

    if(!$error['hasError']) {

      $total = $this->getPrice($product) * $quantity;

      if($product->shipping_calculate_from == 2) {

        $shipping = $product->getRalatedData('ProductShipping',array(
          'first' => true
        ));

        $shippingCost = $shipping->getShippingCost($product,$quantity);
        $total = $total + $shippingCost;

      }

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

    $products = $this->getCart();

    $shopIds = array();
    if(!empty($products)) {
      foreach ($products as $cart) {
        if(!in_array($cart['shopId'], $shopIds)) {
          $shopIds[] = $cart['shopId'];
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
          'summaries' => $this->getSummary($shopId,true)
        );
      }
    }

    return $data;

  }

  public function getSummary($shopId = null,$format = false){

    $summaries = array(
      'subTotal' => 'getSummarySubTotal',
      'shippingCost' => 'getSummaryShippingCost',
      // 'savingPrice' => 'getSummarySavingPrice',
      'total' => 'getSummaryTotal'
    );

    $_summaries = array();
    foreach ($summaries as $alias => $fx) {
      $_summaries[$alias] = array(
        'value' => $this->{$fx}($shopId,$format)
      );
    }

    return $_summaries;

  }

  public function getSummarySavingPrice($shopId = null,$format = false) {

    $currency = new Currency;

    $carts = $this->getCart($shopId);

    $savingPrice = 0;
    if(!empty($carts)) {

      foreach ($carts as $cart) {
        $product = $product = $this->getProduct($cart['productId']);

        $result = $this->getSavingPrice($product,$cart['quantity']);

        if(empty($result)) {
          continue;
        }

        $savingPrice += $result;
      }

    }

    if($format) {
      return $currency->format($savingPrice);
    }

    return $savingPrice;

  }

  public function getSummarySubTotal($shopId = null,$format = false) {

    $currency = new Currency;

    $carts = $this->getCart($shopId);

    $subTotal = 0;
    if(!empty($carts)) {

      foreach ($carts as $cart) {
        $product = $product = $this->getProduct($cart['productId']);

        $result = $this->getProductSubTotal($product,$cart['quantity']);

        if(empty($result)) {
          continue;
        }

        $subTotal += $result;
      }

    }

    if($format) {
      return $currency->format($subTotal);
    }

    return $subTotal;

  }

  public function getSummaryShippingCost($shopId = null,$format = false) {

    $currency = new Currency;

    $carts = $this->getCart($shopId);

    $shippingCost = 0;
    if(!empty($carts)) {

      foreach ($carts as $cart) {
        $product = $product = $this->getProduct($cart['productId']);

        $result = $this->getProductShippingCost($product,$cart['quantity']);

        if(empty($result)) {
          continue;
        }

        $shippingCost += $result;
      }

    }

    if($format) {
      return $currency->format($shippingCost);
    }

    return $shippingCost;

  }

  public function getSummaryTotal($shopId = null,$format = false) {

    $currency = new Currency;

    $carts = $this->getCart($shopId);

    $total = 0;
    if(!empty($carts)) {

      foreach ($carts as $cart) {
        $product = $product = $this->getProduct($cart['productId']);

        $result = $this->getProductTotal($product,$cart['quantity']);

        if(empty($result)) {
          continue;
        }

        $total += $result;
      }

    }

    if($format) {
      return $currency->format($total);
    }

    return $total;

  }

  public function getProducts($shopId = null) {

    $carts = $this->getCart($shopId);

    $products = array();
    if(!empty($carts)) {

      foreach ($carts as $cart) {

        $product = $this->getProductInfo($cart['productId'],$cart['quantity']);

        if(!empty($product)) {
          $products[] = $product;
        }
  
      }

    }

    return $products;

  }

  public function productCount() {

    $carts = $this->getCart();

    $count = 0;
    if(!empty($carts)) {
      foreach ($carts as $cart) {

        $product = $this->getProduct($cart['productId']);

        $error = $this->checkProductError($product,$cart['quantity']);

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
      return count(session()->get('cart')) ? true : false;
    }
  }

  public function getCart($shopId = null) {

    $products = null;

    if(Auth::check()) {

      if(empty($shopId)) {
        $_products = $this
        ->where('person_id','=',session()->get('Person.id'))
        ->select('product_id','quantity','shop_id')
        ->get();
      }else{
        $_products = $this->where([
          ['person_id','=',session()->get('Person.id')],
          ['shop_id','=',$shopId]
        ])
        ->select('product_id','quantity','shop_id')
        ->get();
      }
      
      foreach ($_products as $product) {
        $products[] = array(
          'shopId' => $product->shop_id,
          'productId' => $product->product_id,
          'quantity' => $product->quantity
        );
      }

    }else{

      if(empty($shopId)) {
        $products = session()->get('cart');
      }else{
        $_products = session()->get('cart');

        foreach ($_products as $product) {
          if($product['shopId'] == $shopId) {
            $products[] = $product;
          }
        }
      }

    }

    return $products;

  }

  public function getShopId($productId) {
    // return $this->where([
    //   ['product_id','=',$productId],
    //   ['person_id','=',session()->get('Person.id')]
    // ])
    // ->select('shop_id')
    // ->first()
    // ->shop_id;

    return ShopRelateTo::where([
      ['model','like','Product'],
      ['model_id','=',$productId]
    ])
    ->select('shop_id')
    ->first()
    ->shop_id;

  }

  public function enableCheckingError() {
    $this->checkError = true;
  }

  public function disableCheckingError() {
    $this->checkError = false;
  }

  public function setUpdatedAt($value) {}

}
