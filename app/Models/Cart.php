<?php

namespace App\Models;

use App\library\url;
use App\library\cache;
use App\library\currency;
use Auth;

class Cart extends Model
{
  protected $table = 'carts';
  protected $fillable = ['item_id','item_category_id'];

  public function getProducts() {

    $url = new Url;
    $cache = new Cache;
    $currency = new Currency;

    // if(Auth::check()) {
    // Get From DB
    // }else{
    // Get From Session
    // }

    // test for session
    $carts = session()->get('carts');

    $products = array();
    if(!empty($carts)) {

      foreach ($carts as $cart) {
       
        $product = Product::where([
          ['id','=',$cart['productId']],
          ['active','=',1]
        ])
        ->select('id','name','price')
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

        $products[] = array(
          'name' => $product->name,
          'price' => $currency->format($product->price),
          'quantity' => $cart['quantity'],
          'imageUrl' => $imageUrl,
          'productDetailUrl' => $url->setAndParseUrl('product/detail/{id}',array('id' => $cart['productId']))
        );

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
    $carts = session()->get('carts');

    $count = 0;
    if(!empty($carts)) {
      foreach ($carts as $cart) {

        $product = Product::where([
          ['id','=',$cart['productId']],
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
  
  }

}
