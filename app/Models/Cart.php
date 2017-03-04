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

  // public function updateProduct($id, $quantity) {}

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

  public function getProductSummary() {

    $carts = $this->where('person_id','=',session()->get('Person.id'))->get();

    $data = array();
    if(!empty($carts)) {

      foreach ($carts as $cart) {

        $data[$cart->shop_id][] = array(
          'productId' => $cart->product_id,
          'quantity' => $cart->quantity
        );

      }

    }

    $_data = array();
    foreach ($data as $shopId => $carts) {

      $_products = array();
      foreach ($carts as $cart) {
        $_products[] = $this->getProductInfo($cart['productId'],$cart['quantity']);
      }

      // find shop info
      $shopName = Shop::select('name')->find($shopId)->name;

      $_data[] = array(
        'shop' => array(
          'id' => $shopId,
          'name' => $shopName
        ),
        'products' => $_products,
        'summary' => $this->getSummary()
      );

    }

    return $_data;

  }

  public function getSummary(){

    $summaries = array(
      'subTotal',
      'total'
    );

    $_summaries = array();
    foreach ($summaries as $summary) {
      // $this->{$subTotal}();

      // $_summaries[] = array(
      //   'name' => $summary;
      // );

    }

  }

  public function getProducts() {

    // $url = new Url;
    // $cache = new Cache;
    // $currency = new Currency;

    // if(Auth::check()) {
    // Get From DB
    // }else{
    // Get From Session
    // }

    $carts = $this->where('person_id','=',session()->get('Person.id'))->get();

    $products = array();
    if(!empty($carts)) {

      foreach ($carts as $cart) {

        $products[] = $this->getProductInfo($cart->product_id,$cart->quantity);
        // $product = Product::where([
        //   ['id','=',$cart->product_id],
        //   ['active','=',1]
        // ])
        // ->select('id','name','price')
        // ->first();

        // if(empty($product)) {
        //   continue;
        // }

        // $image = $product->getModelRelationData('Image',array(
        //   'fields' => array('id','model','model_id','filename','image_type_id'),
        //   'first' => true
        // ));

        // $imageUrl = '/images/common/no-img.png';
        // if(!empty($image)) {
        //   $imageUrl = $cache->getCacheImageUrl($image,'sm');
        // }

        // $products[] = array(
        //   'id' => $product->id,
        //   'name' => $product->name,
        //   'price' => $currency->format($product->price),
        //   'quantity' => $cart->quantity,
        //   'total' => $currency->format($product->price * $cart['quantity']),
        //   'imageUrl' => $imageUrl,
        //   'minimum' => $product->minimum,
        //   'productDetailUrl' => $url->setAndParseUrl('product/detail/{id}',array('id' => $product->id))
        // );

      }

    }

    return $products;

  }

  public function productCount() {

    // if(Auth::check()) {
    // Get From DB
    // }else{
    // Get From Session
    // }

    // test for session
    // $carts = session()->get('carts');

    $carts = $this->where('person_id','=',session()->get('Person.id'))->get();

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
    return $this->where('person_id','=',session()->get('Person.id'))->exists();
  }

  public function setUpdatedAt($value) {}

}
