<?php

namespace App\Http\Controllers;

class ManualController extends Controller
{
  public function index() {
    
    if(empty($this->param['pageSlug'])) {
      $this->setPageTitle('วิธีการใช้งาน');
      return $this->view('pages.manual.index');
    }

    switch ($this->param['pageSlug']) {
      case 'ui-and-nav':
          $goto = 'pages.manual.ui-and-nav';
          $title = 'เมนู & ตัวนำทางหลัก';
        break;

      case 'search':
          $goto = 'pages.manual.search';
          $title = 'การค้นหา';
        break;

      case 'notification':
          $goto = 'pages.manual.notification';
          $title = 'การแจ้งเตือน';
        break;

      case 'profile-edit':
          $goto = 'pages.manual.profile-edit';
          $title = 'แก้ไขโปรไฟล์';
        break;

      case 'my-shop':
          $goto = 'pages.manual.my-shop';
          $title = 'ร้านค้าของคุณ';
        break;

      case 'creating-shop':
          $goto = 'pages.manual.creating-shop';
          $title = 'สร้างร่านค้า';
        break;

      case 'adding-shipping-method':
          $goto = 'pages.manual.adding-shipping-method';
          $title = 'เพิ่มตัวเลือกวิธีการจัดส่ง';
        break;

      case 'shop-setting':
          $goto = 'pages.manual.shop-setting';
          $title = 'ข้อมูลร้านค้า';
        break;

      case 'adding-payment-method':
          $goto = 'pages.manual.adding-payment-method';
          $title = 'เพิ่มตัวเลือกการชำระเงิน';
        break;

      case 'shop-profile-image':
          $goto = 'pages.manual.shop-profile-image';
          $title = 'รูปถาพโปรไฟล์ร้านค้า & รูปหน้าปก';
        break;

      case 'adding-product':
          $goto = 'pages.manual.adding-product';
          $title = 'เพิ่มสินค้า';
        break;
      
      case 'product-detail-edit':
          $goto = 'pages.manual.product-detail-edit';
          $title = 'แก้ไขข้อมูลสินค้า & ข้อมูลจำเพาะ';
        break;

      case 'adding-product-attribute':
          $goto = 'pages.manual.adding-product-attribute';
          $title = 'เพิ่มตัวเลือกคุณลักษณะสินค้า';
        break;

      case 'product-category-edit':
          $goto = 'pages.manual.product-category-edit';
          $title = 'แก้ไขหมวดหมู่สินค้า';
        break;

      case 'product-minimum-edit':
          $goto = 'pages.manual.product-minimum-edit';
          $title = 'การสั่งซื้อขั้นต่ำ';
        break;

      case 'product-quantity-edit':
          $goto = 'pages.manual.product-quantity-edit';
          $title = 'จำนวนสินค้า';
        break;

      case 'product-price-edit':
          $goto = 'pages.manual.product-price-edit';
          $title = 'ราคาสินค้า';
        break;

      case 'product-promotion-edit':
          $goto = 'pages.manual.product-promotion-edit';
          $title = 'โปรโมชั่นการขาย';
        break;

      case 'product-shipping-edit':
          $goto = 'pages.manual.product-shipping-edit';
          $title = 'การคำนวณขนส่งสินค้า';
        break;

      case 'product-notification-edit':
          $goto = 'pages.manual.product-notification-edit';
          $title = 'ข้อความและการแจ้งเตือน';
        break;

      default:
          abort(404);
        break;
    }
    
    $this->setPageTitle($title);
    return $this->view($goto);

  }
}
