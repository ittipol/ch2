<?php

namespace App\Models;

use App\library\url;
use App\library\cache;
use App\library\currency;
use Auth;

class Cart extends Model
{
  protected $table = 'carts';
  protected $fillable = ['shop_id','product_id','product_option_value_id','quantity','created_by'];

  private $checkError = true;

  protected $totalTypes = array(
    'subTotal' => array(
      'title' => 'มูลค่าสินค้า',
      'class' => 'sub-total'
    ),
    'shippingCost' => array(
      'title' => 'ค่าจัดส่งสินค้า',
      'class' => 'shipping-cost'
    ),
    'savingPrice' => array(
      'title' => 'ประหยัด',
      'class' => 'saving-price'
    ),
    'total' => array(
      'title' => 'ยอดสุทธิ',
      'class' => 'total-amount'
    )
  );

  public function addProduct($productId,$quantity,$productOptionValueId = null) {

    $product = $this->getProduct($productId,$productOptionValueId);

    if($product->hasProductOption()) {
      if(empty($productOptionValueId)) {
        return array(
          'hasError' => true,
          'errorType' => 5,
          'errorMessage' => 'โปรดเลือกคุณลักษณะสินค้า'
        );
      }

      if(!$product->checkProductOptionValue($productOptionValueId)) {
        return array(
          'hasError' => true,
          'errorType' => 6,
          'errorMessage' => 'ตัวเลือกไม่ถูกต้อง'
        );
      }
    }

    $error = $this->checkProductError($product,$quantity);

    if($error['hasError']) {
      return $error;
    }

    // find shop id
    $shop = $product->getRelatedData('ShopRelateTo',array(
      'first' => true,
      'fields' => array('shop_id')
    ))->shop;

    if(Auth::check()) {

      $cart = $this->where([
        ['product_id','=',$productId],
        ['product_option_value_id','=',$productOptionValueId],
        ['created_by','=',session()->get('Person.id')],
      ])
      ->select('id');

      if($cart->exists()) {
        // update quantity
        $saved = $cart->first()->increment('quantity', $quantity);
      }else{

        $value = array(
          'shop_id' => $shop->id,
          'product_id' => $productId,
          'quantity' => $quantity,
          'created_by' => session()->get('Person.id'),
        );

        if(!empty($productOptionValueId)) {
          $value['product_option_value_id'] = $productOptionValueId; 
        }

        $saved = $this->fill($value)->save();

      }

    }else{

      if(empty($productOptionValueId)) {
        $productOptionValueId = 0;
      }

      if(session()->has('cart.'.$productId.'.items.'.$productOptionValueId)) {
        $product = session()->get('cart.'.$productId.'.items.'.$productOptionValueId);
        $product['quantity'] += $quantity;
        session()->put('cart.'.$productId.'.items.'.$productOptionValueId,$product);
      }else{

        if(!session()->has('cart.'.$productId)) {
          session()->put('cart.'.$productId,array(
            'shopId' => $shop->id,
            'productId' => $productId
          ));
        }

        session()->put('cart.'.$productId.'.items.'.$productOptionValueId,array(
          // 'shopId' => $shop->id,
          // 'productId' => $productId,
          'quantity' => $quantity,
          'productOptionValueId' => ($productOptionValueId == 0) ? null : $productOptionValueId,
        ));


      }
      
      $saved = true;
    }

    return $saved;

  }

  public function updateQuantity($id,$quantity,$productOptionValueId = null) {

    // $product = $this->getProduct($id,$productOptionValueId);

    // $error = $this->checkProductError($product,$quantity);

    // if($error['hasError']) {
    //   return $error;
    // }

    if(Auth::check()) {

      $cart = Cart::where([
        ['product_id','=',$id],
        ['product_option_value_id','=',$productOptionValueId],
        ['created_by','=',session()->get('Person.id')]
      ])
      ->select('id');

      $updated = false;
      if($cart->exists()) {
        $cart = $cart->first();
        $cart->quantity = $quantity;
        $updated = $cart->save();
      }

    }else{

      if(empty($productOptionValueId)) {
        $productOptionValueId = 0;
      }

      if(session()->has('cart.'.$id.'.items.'.$productOptionValueId)) {
        $ewewx = session()->get('cart.'.$id.'.items.'.$productOptionValueId);
        $product['quantity'] = $quantity;
        session()->put('cart.'.$id.'.items.'.$productOptionValueId,$product);
      }

      $updated = true;

    }

    return $updated;

  }


  public function deleteProduct($id,$productOptionValueId = null) {

    if(Auth::check()) {

      $cart = Cart::where([
        ['product_id','=',$id],
        ['product_option_value_id','=',$productOptionValueId],
        ['created_by','=',session()->get('Person.id')]
      ])
      ->select('id');

      $success = false;
      if($cart->exists()) {
        $success = $cart->first()->delete();
      }
    }else{

      if(empty($productOptionValueId)) {
        $productOptionValueId = 0;
      }

      session()->forget('cart.'.$id.'.items.'.$productOptionValueId);
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
        'errorMessage' => 'ไม่สามารถสั่งซื้อสินค้าได้ สินค้ามีจำนวนน้อยกว่าจำนวนที่สั่งซื้อ'
      );
    }elseif($product->minimum > $quantity) {
      $error = array(
        'hasError' => true,
        'errorType' => 5,
        'errorMessage' => 'ไม่สามารถสั่งซื้อสินค้าได้ จำนวนสินค้าที่คุณสั่งซื้อน้อยกว่าจำนวนสั่งซื้อขั้นต่ำ'
      );
    }

    return $error;

  }

  public function getCart($shopId = null) {

    $products = null;

    if(Auth::check()) {

      if(empty($shopId)) {
        $_products = $this
        ->where('created_by','=',session()->get('Person.id'))
        ->select('id','product_id','quantity','shop_id','product_option_value_id')
        ->get();
      }else{
        $_products = $this->where([
          ['created_by','=',session()->get('Person.id')],
          ['shop_id','=',$shopId]
        ])
        ->select('id','product_id','quantity','shop_id','product_option_value_id')
        ->get();
      }

      foreach ($_products as $product) {

        // $data = Product::select('id')->find($product->product_id);

        // if(empty($data)) {
        //   $product->delete();
        //   continue;
        // }

        $products[] = array(
          'shopId' => $product->shop_id,
          'productId' => $product->product_id,
          'quantity' => $product->quantity,
          'productOptionValueId' => $product->product_option_value_id
        );
      }

    }else{

      if(empty($shopId)) {
        $_products = session()->get('cart');

        if(empty($_products)) {
          return null;
        }

        foreach ($_products as $product) {
          foreach ($product['items'] as $value) {

            // $data = Product::select('id')->find($product['productId']);

            // if(empty($data)) {
              
            //   $productOptionValueId = 0;
            //   if(!empty($value['productOptionValueId'])) {
            //     $productOptionValueId = $value['productOptionValueId'];
            //   }

            //   session()->forget('cart.'.$product['productId'].'.items.'.$productOptionValueId);

            //   continue;
            // }

            $products[] = array(
              'shopId' => $product['shopId'],
              'productId' => $product['productId'],
              'quantity' => $value['quantity'],
              'productOptionValueId' => $value['productOptionValueId']
            );
          }
        }

      }else{
        $_products = session()->get('cart');

        foreach ($_products as $product) {
          if($product['shopId'] == $shopId) {

            foreach ($_products as $product) {
              foreach ($product['items'] as $value) {

                // $data = Product::select('id')->find($product['productId']);

                // if(empty($data)) {
                  
                //   $productOptionValueId = 0;
                //   if(!empty($value['productOptionValueId'])) {
                //     $productOptionValueId = $value['productOptionValueId'];
                //   }

                //   session()->forget('cart.'.$product['productId'].'.items.'.$productOptionValueId);

                //   continue;
                // }
                
                $products[] = array(
                  'shopId' => $product['shopId'],
                  'productId' => $product['productId'],
                  'quantity' => $value['quantity'],
                  'productOptionValueId' => $value['productOptionValueId']
                );
              }
            }

            break;

          }
        }
      }

    }

    return $products;

  }

  public function getProducts($shopId = null) {

    $carts = $this->getCart($shopId);

    $products = array();
    if(!empty($carts)) {

      foreach ($carts as $cart) {

        $product = $this->buildCartProductInfo($cart['productId'],$cart['quantity'],$cart['productOptionValueId']);

        if(!empty($product)) {
          $products[] = $product;
        }
  
      }

    }

    return $products;

  }

  public function getProduct($productId,$productOptionValueId = null) {

    $product = Product::select('id','name','price','minimum','product_unit','shipping_calculate_from','quantity','weight','active')
    ->find($productId);

    if(empty($product)) {
      return null;
    }

    $product->attributes['productOption'] = null;
    $product->attributes['productOptionValueId'] = $productOptionValueId;
    if(!empty($productOptionValueId)) {

      $productOptionValue = ProductOptionValue::select('product_option_id','name','use_quantity','quantity','use_price','price')->find($productOptionValueId);

      if(!empty($productOptionValue)) {

        $productOption = ProductOption::select('name')->find($productOptionValue->product_option_id);

        $product->attributes['productOption'] = array(
          'productOptionName' => $productOption->name,
          'valueName' => $productOptionValue->name
        );
        
        if($productOptionValue->use_price) {
          $product->price = $product->price + $productOptionValue->price;
        }

        if($productOptionValue->use_quantity) {
          $product->quantity = $productOptionValue->quantity;
        }

      }

    }

    $product->attributes['promotion'] = $product->getPromotion();

    return $product;

  }

  public function buildCartProductInfo($productId,$quantity,$productOptionValueId = null) {

    $url = new Url;
    $cache = new Cache;

    $product = $this->getProduct($productId,$productOptionValueId);

    if(empty($product)) {
      return null;
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
      'productOptionValueId' => $product->productOptionValueId,
      'productOption' => $product->productOption,
      'imageUrl' => $product->getImage('sm'),
      'productDetailUrl' => $url->setAndParseUrl('product/detail/{id}',array('id' => $product->id)),
    ),$this->checkProductError($product,$quantity));

  }















  // public function getProduct($productId) {

  //   $product = Product::where([
  //     ['id','=',$productId],
  //   ])
  //   ->select('id','name','price','minimum','product_unit','shipping_calculate_from','quantity','weight','active')
  //   ->first();

  //   if(empty($product)) {
  //     return null;
  //   }

  //   $product->attributes['promotion'] = $product->getPromotion();

  //   return $product;

  // }

  // public function getProductInfo($productId,$quantity) {

  //   $url = new Url;
  //   $cache = new Cache;

  //   $product = $this->getProduct($productId);

  //   if(empty($product)) {
  //     return null;
  //   }

  //   return array_merge(array(
  //     'id' => $product->id,
  //     'name' => $product->name,
  //     'minimum' => $product->minimum,
  //     'quantity' => $quantity,
  //     'product_unit' => $product->product_unit,
  //     'shipping_calculate_from' => $product->shipping_calculate_from,
  //     'price' => $this->getPrice($product,true),
  //     'savingPrice' => $this->getSavingPrice($product,$quantity,true),
  //     'subTotal' => $this->getProductSubTotal($product,$quantity,true),
  //     'shippingCost' => $this->getProductShippingCost($product,$quantity,true),
  //     'total' => $this->getProductTotal($product,$quantity,true),
  //     'imageUrl' => $product->getImage('sm'),
  //     'productDetailUrl' => $url->setAndParseUrl('product/detail/{id}',array('id' => $product->id)),
  //   ),$this->checkProductError($product,$quantity));

  // }

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

        $shipping = $product->getRelatedData('ProductShipping',array(
          'first' => true
        ));

        // $shippingCost = $shipping->calShippingCost($product,$quantity);
        // $total = $total + $shippingCost;

        $total = $total + $shipping->calShippingCost($product,$quantity);

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

    $freeShipping = false;
    $shippingCost = 0;

    if(!$error['hasError']) {

      if($product->shipping_calculate_from == 2) {

        $shipping = $product->getRelatedData('ProductShipping',array(
          'first' => true
        ));

        $shippingCost = 0;
        if($shipping->free_shipping || $shipping->checkFreeShippingCondition($product,$quantity)) {
          $freeShipping = true;
        }else{
          $shippingCost = $shipping->calShippingCost($product,$quantity);
        }

      }

    }

    if($format) {

      if($freeShipping) {
        $shippingCost = 'จัดส่งฟรี ('.$currency->format(0).')';
      }else{
        $shippingCost = $currency->format($shippingCost);
      }

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
      'subTotal' => 'getCartSubTotal',
      'shippingCost' => 'getCartShippingCost',
      'savingPrice' => 'getCartSavingPrice',
      'total' => 'getCartTotal'
    );

    $_summaries = array();
    foreach ($summaries as $alias => $fx) {
      $_summaries[$alias] = array(
        'value' => $this->{$fx}($shopId,$format),
        'title' => $this->getTitle($alias),
        'class' => $this->getClass($alias),
      );
    }

    return $_summaries;

  }

  public function getCartSavingPrice($shopId = null,$format = false) {

    $currency = new Currency;

    $carts = $this->getCart($shopId);

    $savingPrice = 0;
    if(!empty($carts)) {

      foreach ($carts as $cart) {
        $product = $this->getProduct($cart['productId']);

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

  public function getCartSubTotal($shopId = null,$format = false) {

    $currency = new Currency;

    $carts = $this->getCart($shopId);

    $subTotal = 0;
    if(!empty($carts)) {

      foreach ($carts as $cart) {
        $product = $this->getProduct($cart['productId']);

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

  public function getCartShippingCost($shopId = null,$format = false) {

    $currency = new Currency;

    $carts = $this->getCart($shopId);

    $shippingCost = 0;
    if(!empty($carts)) {

      foreach ($carts as $cart) {
        $product = $this->getProduct($cart['productId']);

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

  public function getCartTotal($shopId = null,$format = false) {

    $currency = new Currency;

    $carts = $this->getCart($shopId);

    $total = 0;
    if(!empty($carts)) {

      foreach ($carts as $cart) {
        $product = $this->getProduct($cart['productId']);

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

  public function productCount() {

    $carts = $this->getCart();

    $count = 0;
    if(!empty($carts)) {
      foreach ($carts as $cart) {

        // $product = $this->getProduct($cart['productId']);

        // $error = $this->checkProductError($product,$cart['quantity']);

        // if($error['hasError']) {
        //   continue;
        // }

        $count += $cart['quantity'];
        
      }
    }

    return $count;

  }

  public function hasProducts() {
    if(Auth::check()) {
      return $this->where('created_by','=',session()->get('Person.id'))->exists();
    }else {

      $products = session()->get('cart');

      $hasProduct = false;
      if(!empty($products)) {

        foreach ($products as $product) {
          $hasProduct = !empty($product['items']);
        }

      }

      if(!$hasProduct) {
        session()->forget('cart');
      }

      return $hasProduct;
      // return count(session()->get('cart')) ? true : false;
    }
  }

  public function getShopId($productId) {
    $data = ShopRelateTo::where([
      ['model','like','Product'],
      ['model_id','=',$productId]
    ])
    ->select('shop_id');

    if(!$data->exists()) {
      return null;
    }

    return $data->first()->shop_id;
  }

  public function getTitle($alias) {

    if(empty($this->totalTypes[$alias]['title'])) {
      return null;
    }

    return $this->totalTypes[$alias]['title'];
  }

  public function getClass($alias) {

    if(empty($this->totalTypes[$alias]['class'])) {
      return null;
    }

    return $this->totalTypes[$alias]['class'];
  }

  public function enableCheckingError() {
    $this->checkError = true;
  }

  public function disableCheckingError() {
    $this->checkError = false;
  }

  public function setUpdatedAt($value) {}

}
