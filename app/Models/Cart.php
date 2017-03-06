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

    // check product is active
    $product = Product::where([
      ['id','=',$productId],
      ['quantity','!=',0],
      ['active','=',1]
    ])
    ->select('id','minimum')
    ->first();

    if(empty($product) || ($product->minimum > $quantity)) {
      return false;
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

  public function updateQuantity($id, $quantity) {

    // check product is active
    $product = Product::where([
      ['id','=',$id],
      ['quantity','!=',0],
      ['active','=',1]
    ])
    ->select('id','minimum')
    ->first();

    if(empty($product)) {
      return false;
    }

    if(empty($quantity) || ($product->minimum > $quantity)) {
      $quantity = $product->minimum;
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

  public function getProductInfo($productId,$quantity) {

    $url = new Url;
    $cache = new Cache;
    $currency = new Currency;

    $product = Product::where([
      ['id','=',$productId],
      ['quantity','!=',0],
      ['active','=',1]
    ])
    ->select('id','name','price','minimum','product_unit')
    ->first();

    if(empty($product)) {
      continue;
    }
    
    $image = $product->getModelRelationData('Image',array(
      'fields' => array('id','model','model_id','filename','image_type_id'),
      'first' => true
    ));

    $imageUrl = '/images/common/no-img.png';
    if(!empty($image)) {
      $imageUrl = $cache->getCacheImageUrl($image,'sm');
    }

    $_products = array(
      'id' => $product->id,
      'name' => $product->name,
      'price' => $currency->format($product->price),
      'quantity' => $quantity,
      'total' => $currency->format($product->price * $quantity),
      'minimum' => $product->minimum,
      'product_unit' => $product->product_unit,
      'imageUrl' => $imageUrl,
      'productDetailUrl' => $url->setAndParseUrl('product/detail/{id}',array('id' => $product->id))
    );

    return $_products;

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

  public function getProducts($shopId = null) {

    $carts = $this->getCarts($shopId);

    $products = array();
    if(!empty($carts)) {

      foreach ($carts as $cart) {
        $products[] = $this->getProductInfo($cart->product_id,$cart->quantity);
      }

    }

    return $products;

  }

  public function getSummary($shopId = null){

    $summaries = array(
      'subTotal' => 'getSubTotal',
      'total' => 'getTotal'
    );

    $_summaries = array();
    foreach ($summaries as $alias => $fx) {
      $_summaries[$alias] = array(
        'value' => $this->{$fx}($shopId)
      );
    }

    return $_summaries;

  }

  public function getSubTotal($shopId = null) {

    $currency = new Currency;

    if(!empty($shopId)) {
      $carts = $this->where([
        ['person_id','=',session()->get('Person.id')],
        ['shop_id','=',$shopId]
      ])->get();
    }else{
      $carts = $this->where('person_id','=',session()->get('Person.id'))->get();
    }

    $subTotal = 0;
    if(!empty($carts)) {

      foreach ($carts as $cart) {

        $product = Product::where([
          ['id','=',$cart->product_id],
          ['active','=',1]
        ])
        ->select('price')
        ->first();

        $subTotal += ($product->price * $cart->quantity);

      }

    }

    return $currency->format($subTotal);

  }

  public function getTotal($shopId = null) {

    $currency = new Currency;

    if(!empty($shopId)) {
      $carts = $this->where([
        ['person_id','=',session()->get('Person.id')],
        ['shop_id','=',$shopId]
      ])->get();
    }else{
      $carts = $this->where('person_id','=',session()->get('Person.id'))->get();
    }

    $total = 0;
    if(!empty($carts)) {

      foreach ($carts as $cart) {

        $product = Product::where([
          ['id','=',$cart->product_id],
          ['active','=',1]
        ])
        ->select('price')
        ->first();

        // Plus Tax
        // 

        $total += ($product->price * $cart->quantity);

      }

    }

    return $currency->format($total);

  }

  public function productCount() {

    $carts = $this->getCarts();

    $count = 0;
    if(!empty($carts)) {
      foreach ($carts as $cart) {

        $product = Product::where([
          ['id','=',$cart->product_id],
          ['active','=',1]
        ])
        ->select('id')
        ->first();

        if(empty($product)) {
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

  public function getProductTotal($productId,$quantity) {

    $currency = new Currency;

    $price = Product::select('price')
    ->find($productId)->price;

    return $currency->format($price * $quantity);
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
