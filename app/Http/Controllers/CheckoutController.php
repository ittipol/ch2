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

    $productSummaries = Service::loadModel('Cart')->getProductSummary();

    $shippingMethods = array();
    foreach ($productSummaries as $productSummary) {
      $shippingMethods[$productSummary['shop']['id']] = Service::loadModel('ShippingMethod')->getShippingMethodChoice($productSummary['shop']['id']);
    }

    $this->setData('data',Service::loadModel('Cart')->getProductSummary());
    $this->setData('shippingAddress',$address);
    $this->setData('shippingMethods',$shippingMethods);

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
        );

      }
    }

    if(empty($checkoutProducts)) {
      Message::display('ไม่ได้เลือกร้านค้าที่ต้องการสั่งซื้อสินค้า กรุณาเลือกร้านค้าที่ต้องการสั่งซื้อสินค้าแล้วสั่งซื้ออีกครั้ง','error');
      return Redirect::back();
    }

    $cartModel->disableCheckingError();

    $personId = session()->get('Person.id');
    $personName = session()->get('Person.name');
    $orderStatusId = Service::loadModel('OrderStatus')->getIdByalias('pending-seller-confirmation');

    foreach ($checkoutProducts as $shopId => $products) {

      $order = Service::loadModel('Order');

      $order
      ->fill(array(
        'invoice_prefix' => $order->getInvoicePrefix(),
        'invoice_number' => $order->getInvoiceNumber($shopId),
        'shop_id' => $shopId,
        'person_id' => $personId,
        'person_name' => $personName,
        'shipping_address' => $shops[$shopId]['shipping_address'],
        'message_to_seller' => $shops[$shopId]['message'],
        'order_status_id' => $orderStatusId
      ))
      ->save();

      $orderProductModel = Service::loadModel('OrderProduct');
      $orderTotalModel = Service::loadModel('OrderTotal');

      foreach ($products as $product) {

        $_product = $productModel
        ->select('id','name','price','shipping_calculate_from','quantity','weight')
        ->find($product['productId']);

        $shipping = array();
        if($_product->shipping_calculate_from == 2) {

          $productShipping = $_product->getRelatedData('ProductShipping',array(
            'first' => true
          ));

          if($productShipping->free_shipping || $productShipping->checkFreeShippingCondition($_product,$product['quantity'])) {
            $shipping = array(
              'free_shipping' => 1
            );
          }else{
            $shipping = array(
              'shipping_cost' => $productShipping->calShippingCost($_product,$product['quantity'])
            );
          }

          // $shipping = array(
          //   // 'free_shipping' => $productShipping->free_shipping,
          //   // 'shipping_cost' => $productShipping->calShippingCost($_product,$product['quantity'])
          //   // 'shipping_cost' => $productShipping->shipping_cost,
          //   // 'product_shipping_amount_type_id' => $productShipping->product_shipping_amount_type_id
          // );

        }

        $value = array_merge(array(
          'order_id' => $order->id,
          'product_id' => $product['productId'],
          'product_name' => $_product->name,
          'price' => $_product->getPrice(),
          'quantity' => $product['quantity'],
          'total' => $cartModel->getProductTotal($_product,$product['quantity']),
        ),$shipping);

        $orderProductModel
        ->newInstance()
        ->fill($value)
        ->save();

      }

      $totals = $cartModel->getSummary($shopId);

      foreach ($totals as $alias => $value) {

        $orderTotalModel
        ->newInstance()
        ->fill(array(
          'order_id' => $order->id,
          'alias' => $alias,
          'value' => $value['value'],
        ))
        ->save();

      }

      // delete products in cart
      $cartModel->where([
        ['shop_id','=',$shopId],
        ['person_id','=',$personId]
      ])->delete();

    }

    session()->flash('checkout-success',true);

    // Message::display('ลงประกาศเรียบร้อยแล้ว','success');
    return Redirect::to('checkout/success');

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

  public function success() {

    if(!session()->has('checkout-success')) {
      return Redirect::to('account/order');
    }

    return $this->view('pages.checkout.success');

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
