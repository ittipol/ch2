<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\message;
use Redirect;

class CheckoutController extends Controller
{
  public function checkout() {
    
    $address = Service::loadModel('Address')->where([
      ['model','like','Person'],
      ['model_id','=',session()->get('Person.id')]
    ])
    ->first()
    ->getAddress();

    $this->setData('data',Service::loadModel('Cart')->getProductSummary());
    $this->setData('shippingAddress',$address);

    return $this->view('pages.checkout.checkout');

  }

  public function checkoutSubmit() {
    
    $productModel = Service::loadModel('Product');
    $cartModel = Service::loadModel('Cart');

    // form data
    $shops = request()->input('shop');
    // Get cart
    $cartProducts = $cartModel->getCart();

    $error = false;
    $checkoutProducts = array();
    if(!empty($cartProducts)) {
      foreach ($cartProducts as $cartProduct) {

        if(empty($shops[$cartProduct['shopId']]['checkout'])) {
          continue;
        }

        if(empty($shops[$cartProduct['shopId']]['shipping_address'])) {
          $this->rollback($checkoutProducts);
          return Redirect::back()->withErrors(['ที่อยู่สำหรับการจัดส่งไม่ได้ถูกกรอก กรุณาตรวจสอบและกรอกที่อยู่สำหรับการจัดส่งให้ครบถ้วน']);
        }

        $_product = $productModel
        ->select('id','name','quantity','minimum','active')
        ->find($cartProduct['productId']);

        $error = $cartModel->checkProductError($_product,$cartProduct['quantity']);

        if($error['hasError']) {
          $this->rollback($checkoutProducts);
          return Redirect::back()->withErrors($this->errorMessage($error['errorType']));
        }

        // allocate product quantity
        $_product->decrement('quantity',$cartProduct['quantity']);

        $checkoutProducts[$cartProduct['shopId']][] = array(
          'productId' => $cartProduct['productId'],
          'quantity' => $cartProduct['quantity'],
          // 'name' => $_product->name,
          // 'price' => $cartModel->getPrice($_product),
          // 'savingPrice' => $cartModel->getSavingPrice($_product,$cartProduct['quantity']),
          // 'subTotal' => $cartModel->getProductSubTotal($_product,$cartProduct['quantity']),
          // 'shippingCost' => $cartModel->getProductShippingCost($_product,$cartProduct['quantity']),
          // 'total' => $cartModel->getProductTotal($_product,$cartProduct['quantity']),
        );

      }
    }

    if(empty($checkoutProducts)) {
      Message::display('ไม่พบร้านค้าที่ต้องการสั่งซื้อสินค้า กรุณาเลือกร้านค้าที่ต้องการสั่งซื้อสินค้าแล้วสั่งซื้ออีกครั้ง','error');
      return Redirect::back();
    }

    $personId = session()->get('Person.id');
    $personName = session()->get('Person.name');

    foreach ($checkoutProducts as $shopId => $products) {

      $order = Service::loadModel('Order');

      $saved = $order
      ->fill(array(
        'invoice_prefix' => $order->getInvoicePrefix(),
        'invoice_number' => $order->getInvoiceNumber($shopId),
        'shop_id' => $shopId,
        'person_id' => $personId,
        'person_name' => $personName,
        'shipping_address' => $shops[$shopId]['shipping_address'],
        'message_to_seller' => $shops[$shopId]['message'],
      ))
      ->save();

      // order products
      $orderProduct = Service::loadModel('OrderProduct');
// newInstance()
      foreach ($products as $product) {

        $_product = $productModel
          // ->select('id','name','quantity','minimum','active')
          ->select('id','name','price','minimum','product_unit','shipping_calculate_from','quantity','weight','active')
          ->find($cartProduct['productId']);

        if($_product->shipping_calculate_from == 2) {
          
        }

        // $value = array(
        //   'order_id' => $order->id,
        //   'product_id' => $product['productId'],
        //   'product_name',
        //   'price',
        //   'quantity',
        //   'shippng_cost'
        // );

        dd($product);

      }

    }

    dd('done');
    dd('passed');

  }

  private function rollback($checkoutProducts) {

    $productModel = Service::loadModel('Product');

    foreach ($checkoutProducts as $shopId => $checkoutProduct) {
      foreach ($checkoutProduct as $product) {
        $productModel
        ->find($product['productId'])
        ->increment('quantity',$product['quantity']);
      }
    }
  }

  private function errorMessage($error) {

    switch ($error) {
      case 4:
        return 'พบสินค้าที่มีการสั่งซื้อน้อยกว่าการสั่งซื้อขั่นต่ำ กรุณาตรวจสอบสินค้าในตระกร้าและส้่งซื้ออีกครั้ง';
        break;
      
      case 5:
        return 'พบสินค้าที่มีจำนวนสินค้าไม่เพียงพอต่อการสั่งซื้อ กรุณาตรวจสอบสินค้าในตระกร้าและส้่งซื้ออีกครั้ง';
        break;
    
      default:
        return 'พบสินค้าบางส่วนในตระกร้าไม่สามารถสั่งซื้อได้ กรุณาตรวจสอบสินค้าในตระกร้าและส้่งซื้ออีกครั้ง';
    }

  }

}
