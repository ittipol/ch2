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

      case 'adding-payment-method':
          $goto = 'pages.manual.adding-payment-method';
          $title = 'เพิ่มตัวเลือกการชำระเงิน';
        break;

      case 'shop-edit':
          $goto = 'pages.manual.shop-edit';
          $title = 'แก้ไขข้อมูลร้านค้า';
        break;

      case 'adding-product':
          $goto = 'pages.manual.adding-product';
          $title = 'เพิ่มสินค้า';
        break;
      
      default:
          abort(404);
        break;
    }
    
    $this->setPageTitle($title);
    return $this->view($goto);

  }
}
