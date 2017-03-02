<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\url;
use App\library\cache;
use App\library\currency;

class CartController extends Controller
{
  public function index() {

    $url = new Url;
    $cache = new Cache;
    $currency = new Currency;
    
    // for test
    $carts = array(
      array(
        'productId' => 7,
        'quantity' => 2
      ),
      array(
        'productId' => 10,
        'quantity' => 1
      ),
      array(
        'productId' => 9,
        'quantity' => 10
      )
    );

    $productModel = Service::loadModel('Product');

    $products = array();
    foreach ($carts as $cart) {
      
      $product = $productModel->where([
        ['id','=',$cart['productId']],
        ['active','=',1]
      ])->first();

      if(empty($product)) {
        continue;
      }

      $image = $product->getModelRelationData('Image',array(
        'fields' => array('id','model','model_id','filename','image_type_id'),
        'first' => true
      ));

      $imageUrl = '/images/common/no-img.png';
      if(!empty($image)) {
        $imageUrl = $cache->getCacheImageUrl($image,'xsm');
      }

      $products[] = array(
        'name' => $product->name,
        'price' => $currency->format($product->price),
        'quantity' => $cart['quantity'],
        'imageUrl' => $imageUrl,
        'productDetailUrl' => $url->setAndParseUrl('product/detail/{id}',array('id' => $cart['productId']))
      );

    }

    $this->setData('products',$products);

    return $this->view('pages.cart.cart');

  }

}
