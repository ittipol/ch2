<?php

namespace App\library;

use Session;

class Message
{
  public static function display($title = '',$type = 'info') {
    Session::flash('message.title', $title);
    Session::flash('message.type', $type); 
  }

  public static function displayWithDesc($title = '',$desc = '',$type = 'info') {
    Session::flash('message.title', $title);
    Session::flash('message.desc', $desc);
    Session::flash('message.type', $type); 
  }

  // public function get($name) {
  //   return $this->message[$name];
  // }

  // public function addingSuccess($text = '') {

  //   if(empty($text)) {
  //     $text = 'ข้อมูล';
  //   }

  //   Session::flash('message.title', $text.'ถูกเพิ่มเรียบร้อยแล้ว');
  //   Session::flash('message.type', 'success');
  // }

  // public function editingSuccess($text = '') {

  //   if(empty($text)) {
  //     $text = 'ข้อมูล';
  //   }

  //   Session::flash('message.title', $text.'ถูกแก้ไขเรียบร้อยแล้ว');
  //   Session::flash('message.type', 'success');
  // }

  public function loginSuccess() {
    Session::flash('message.title', 'เข้าสู่ระบบแล้ว');
    Session::flash('message.desc', '');
    Session::flash('message.type', 'info');
  }

  //   public function loginFail() {
  //     Session::flash('message.title', 'อีเมล  หรือ รหัสผ่านไม่ถูก');
  //     Session::flash('message.desc', '');
  //     Session::flash('message.type', 'error');
  //   }

  //   public function loginRequest() {
  //     Session::flash('message.title', 'กรุณาเข้าสู่ระบบ');
  //     Session::flash('message.desc', 'หน้าที่คุณเรียกนั้น จำเป็นต้องเข้าสู่ระบบก่อนเพื่อการทำงานที่ถูกต้อง');
  //     Session::flash('message.type', 'error');
  //   }

  // public function registerSuccess() {
  //   Session::flash('message.title', 'สมัครสมาชิกเส็จสิ้น');
  //   Session::flash('message.desc', 'บัญชีของคุณพร้อมใช้งานแล้ว');
  //   Session::flash('message.type', 'success');
  // }

  // public function formTokenNotFound($text = 'บันทึก') {
  //   Session::flash('message.title', 'เกิดข้อผิดพลาด ไม่สามารถ'.$text.'ข้อมูลได้ โปรดลองใหม่อีกครั้ง');
  //   Session::flash('message.type', 'error');
  // }

  public function error($text = '') {
    Session::flash('message.title', 'เกิดข้อผิดพลาด '.$text);
    Session::flash('message.type', 'error'); 
  }

}
