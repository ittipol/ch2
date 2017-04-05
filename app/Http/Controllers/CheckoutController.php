<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\message;
use App\library\notificationHelper;
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

    $shippingMethodModel = Service::loadModel('ShippingMethod');

    $shippingMethods = array();
    foreach ($productSummaries as $productSummary) {
      $shippingMethods[$productSummary['shop']['id']] = $shippingMethodModel->getShippingMethodChoice($productSummary['shop']['id']);
    }

    $this->setData('data',Service::loadModel('Cart')->getProductSummary());
    $this->setData('shippingAddress',$address);
    $this->setData('shippingMethods',$shippingMethods);

    return $this->view('pages.checkout.checkout');

  }

  public function checkoutSubmit() {
    
    $productModel = Service::loadModel('Product');
    $cartModel = Service::loadModel('Cart');
    $shippingMethodModel = Service::loadModel('ShippingMethod');

    $pickUpMethodId = Service::loadModel('SpecialShippingMethod')->getIdByalias('picking-up-product');

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

        if($shippingMethodModel->hasShippingMethod($cartProduct['shopId'])) {

          if(!isset($shops[$cartProduct['shopId']]['shipping_method_id'])) {
            return Redirect::back()->withErrors('ยังไม่ได้เลือกวิธีการจัดส่งสินค้า');
          }elseif(!$shippingMethodModel->checkShippingMethodExistById($shops[$cartProduct['shopId']]['shipping_method_id'],$cartProduct['shopId'])){
            return Redirect::back()->withErrors('เกิดข้อผิดพลาดในการเลือกวิธีการจัดส่งสินค้า');
          }
         
        }

        $specialShippingMethodId = $shippingMethodModel
        ->select('special_shipping_method_id')
        ->find($shops[$cartProduct['shopId']]['shipping_method_id'])
        ->special_shipping_method_id;

        if(($specialShippingMethodId == $pickUpMethodId) && empty($shops[$cartProduct['shopId']]['branch_id'])) {
          return Redirect::back()->withErrors('ยังไม่ได้ระบุสาขาที่คุณต้องการเข้ารับสินค้า');
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

    $notificationHelper = new NotificationHelper;

    $cartModel->disableCheckingError();

    $personId = session()->get('Person.id');
    $personName = session()->get('Person.name');
    $orderStatusId = Service::loadModel('OrderStatus')->getIdByalias('pending-seller-confirmation');

    $orderModel = Service::loadModel('Order');
    $orderShipping = Service::loadModel('OrderShipping');
    $orderProductModel = Service::loadModel('OrderProduct');
    $orderTotalModel = Service::loadModel('OrderTotal');
    $orderPickUpToBranchModel = Service::loadModel('OrderPickUpToBranch');

    // $createAt = date('Y-m-d H:i:s');

    foreach ($checkoutProducts as $shopId => $products) {

      $order = $orderModel->newInstance();

      // order shipping cost
      $shipping = array();
      $pickUpOrder = false;

      if(isset($shops[$shopId]['shipping_method_id'])) {
        $shippingMethod = $shippingMethodModel
        ->select('id','name','service_cost','free_service','shipping_service_id','shipping_service_cost_type_id','shipping_time','special_shipping_method_id')
        ->find($shops[$shopId]['shipping_method_id']);

        if($shippingMethod->special_shipping_method_id == $pickUpMethodId) {
          $pickUpOrder = true;
        }

        $shipping = array(
          'order_free_shipping' => $shippingMethod->free_service,
          'order_shipping_cost' => $shippingMethod->service_cost,
          'pick_up_order' => $pickUpOrder
        );

      }

      $order->fill(array_merge(array(
        'invoice_prefix' => $orderModel->getInvoicePrefix(),
        'invoice_number' => $orderModel->getInvoiceNumber($shopId),
        'shop_id' => $shopId,
        'person_id' => $personId,
        'person_name' => $personName,
        'shipping_address' => $shops[$shopId]['shipping_address'],
        'customer_message' => $shops[$shopId]['message'],
        'order_status_id' => $orderStatusId
      ),$shipping))
      ->save();

      // If has has shipping method then save
      if(isset($shops[$shopId]['shipping_method_id'])) {

        $orderShipping
        ->newInstance()
        ->fill(array(
          'order_id' => $order->id,
          'shipping_method_id' => $shippingMethod->id,
          'shipping_method_name' => $shippingMethod->name,
          'shipping_service_id' => $shippingMethod->shipping_service_id,
          'shipping_service_cost_type_id' => $shippingMethod->shipping_service_cost_type_id,
          'shipping_time' => $shippingMethod->shipping_time
        ))
        ->save();

        // pick up product
        if($pickUpOrder) {
          $orderPickUpToBranchModel
          ->newInstance()
          ->fill(array(
            'order_id' => $order->id,
            'branch_id' => $shops[$shopId]['branch_id']
          ))
          ->save();
        }

      }

      foreach ($products as $product) {

        $_product = $productModel
        ->select('id','name','price','shipping_calculate_from','quantity','weight')
        ->find($product['productId']);

        $shipping = array();
        if(isset($shops[$shopId]['shipping_method_id']) && ($shippingMethod->shipping_service_cost_type_id == 3)) {
          $shipping = array(
            'free_shipping' => 1,
            'shipping_cost' => null
          );
        }elseif($_product->shipping_calculate_from == 2) {

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

        }

        $orderProductModel
        ->newInstance()
        ->fill(array_merge(array(
          'order_id' => $order->id,
          'product_id' => $product['productId'],
          'product_name' => $_product->name,
          'full_price' => $_product->price,
          'price' => $_product->getPrice(),
          'quantity' => $product['quantity'],
          'total' => $cartModel->getProductTotal($_product,$product['quantity']),
        ),$shipping))
        ->save();

      }

      // $totals = $cartModel->getSummary($shopId);
      $totals = $order->getSummary();

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

      // Add Order History
      $OrderHistoryModel = Service::loadModel('OrderHistory');
      $OrderHistoryModel->order_id = $order->id;
      $OrderHistoryModel->order_status_id = 1;
      $OrderHistoryModel->save();

      // Create Notification
      $notificationHelper->setModel($order);
      $notificationHelper->create('order-create');

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
