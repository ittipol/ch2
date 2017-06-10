<?php

namespace App\Http\Controllers;

class ManualController extends Controller
{
  public function __construct() { 
    parent::__construct();

    $this->setPageTitle('วิธีการใช้งาน');
    $this->setPageDescription('วิธีการใช้งานเว็บไซต์ การใช้งานทั่วไป การใช้งานสำหรับผู้ขาย การใช้งานสำหรับผู้ซื้อ');
    $this->setMetaKeywords('ผู้ขาย,ผู้ซื้อ,ร้านค้า,ร้านค้าออนไลน์,วิธีการใช้งาน,คู่มือการใช้งาน,การช่วยเหลือ,ตระกร้าสินค้า,การแจ้งเตือน,การค้นหา');

  }

  public function index() {
    
    if(empty($this->param['pageSlug'])) {
      $this->setPageTitle('วิธีการใช้งาน');
      return $this->view('pages.manual.index');
    }

    switch ($this->param['pageSlug']) {
      case 'ui-and-nav':
          $title = 'เมนู & ตัวนำทางหลัก';
        break;

      case 'search':
          $title = 'การค้นหา';
        break;

      case 'notification':
          $title = 'การแจ้งเตือน';
        break;

      case 'profile-edit':
          $title = 'แก้ไขโปรไฟล์';
        break;

      case 'my-shop':          
          $title = 'ร้านค้าของคุณ';
        break;

      case 'creating-shop':
          $title = 'สร้างร่านค้า';
        break;

      case 'adding-shipping-method':
          $title = 'เพิ่มวิธีการจัดส่ง';
        break;

      case 'adding-payment-method':
          $title = 'เพิ่มวิธีการชำระเงิน';
        break;

      case 'shop-setting':
          $title = 'ข้อมูลร้านค้า';
        break;

      case 'shop-profile-image':
          $title = 'รูปถาพโปรไฟล์ร้านค้า & รูปหน้าปก';
        break;

      case 'adding-product':
          $title = 'เพิ่มสินค้า';
        break;
      
      case 'product-detail-edit':
          $title = 'แก้ไขข้อมูลสินค้า & ข้อมูลจำเพาะ';
        break;

      case 'adding-product-attribute':
          $title = 'เพิ่มตัวเลือกคุณลักษณะสินค้า';
        break;

      case 'product-category-edit':
          $title = 'แก้ไขหมวดหมู่สินค้า';
        break;

      case 'product-minimum-edit':
          $title = 'การสั่งซื้อขั้นต่ำ';
        break;

      case 'product-quantity-edit':
          $title = 'จำนวนสินค้า';
        break;

      case 'product-price-edit':
          $title = 'ราคาสินค้า';
        break;

      case 'product-promotion-edit':
          $title = 'โปรโมชั่นการขาย';
        break;

      case 'product-shipping-edit':
          $title = 'การคำนวณขนส่งสินค้า';
        break;

      case 'product-notification-edit':
          $title = 'ข้อความและการแจ้งเตือน';
        break;

      case 'adding-product-catalog':
          $title = 'สร้างแคตตาล็อกสินค้า';
        break;

      case 'product-catalog-edit':
          $title = 'แก้ไขแคตตาล็อกสินค้า';
        break;

      case 'product-catalog-product-edit':
          $title = 'เพิ่ม / ลบสินค้าในแคตตาล็อก';
        break;

      case 'buying-product':
          $title = 'เลือกซื้อสินค้า';
        break;

      case 'checking-out-product':
          $title = 'ดำเนินการสั่งซื้อสินค้า';
        break;

      case 'checking-order':
          $title = 'ตรวจสอบรายการสั้่งซื้อ';
        break;

      case 'payment-inform':
          $title = 'การแจ้งการชำระเงิน';
        break;

      case 'cart-product-edit':
          $title = 'แก้ไข & ลบสินค้าในตระกร้าสินค้า';
        break;

      default:
          abort(404);
        break;
    }
    
    $this->setPageTitle($title);
    $this->setPageDescription('วิธีการใช้งาน '.$title);

    $this->botAllowed();

    $this->setData('title',$title);
    $this->setData('page','pages.manual.manual_page.'.$this->param['pageSlug']);

    return $this->view('pages.manual.panel');

  }
}
