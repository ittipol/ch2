<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\imageTool;
use App\library\handleImageFile;
use App\library\handleAttachedFile;
use Input;
// use Session;
use Schema;
use Cookie;

class ApiController extends Controller
{ 

  public function GetDistrict($provinceId = null) {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      // exit('Error!!!');  //trygetRealPath detect AJAX request, simply exist if no Ajax
      $this->error = array(
        'message' => 'ขออภัย ไม่อนุญาตให้เข้าถึงหน้านี้ได้'
      );
      return $this->error();
    }

    $records = Service::loadModel('District')->where('province_id', '=', $provinceId)->get(); 

    $districts = array();
    foreach ($records as $record) {
      $districts[$record->id] = $record->name;
    }

    return response()->json($districts);
  }

  public function GetSubDistrict($districtId = null) {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      $this->error = array(
        'message' => 'ขออภัย ไม่อนุญาตให้เข้าถึงหน้านี้ได้'
      );
      return $this->error();
    }

    $records = Service::loadModel('SubDistrict')->where('district_id', '=', $districtId)->get(); 

    $subDistricts = array();
    foreach ($records as $record) {
      $subDistricts[$record->id] = $record->name;
    }

    return response()->json($subDistricts);
  }

  public function GetCategory($parentId = null) {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      $this->error = array(
        'message' => 'ขออภัย ไม่อนุญาตให้เข้าถึงหน้านี้ได้'
      );
      return $this->error();
    }

    $category = Service::loadModel('Category');

    $records = $category
    ->where('parent_id','=',$parentId)
    ->get();
    
    $categories = array();
    foreach ($records as $record) {

      // check has sub category
      $subCat = $category->where('parent_id','=',$record->id)->first();

      $hasChild = false;
      if(!empty($subCat)) {
        $hasChild = true;
      }

      $categories[] = array(
        'id' => $record->id,
        'name' => $record->name,
        'hasChild' => $hasChild
      );
    }

    $result = array(
      'success' => true,
      'categories' => $categories
    );

    return response()->json($result);
  }

  public function GetShippingMethodId($shippingMethodId) {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      $this->error = array(
        'message' => 'ขออภัย ไม่อนุญาตให้เข้าถึงหน้านี้ได้'
      );
      return $this->error();
    }

    $shippingMethod = Service::loadModel('ShippingMethod')
    ->select('name','description')
    ->where('id','=',$shippingMethodId);

    $result = array(
      'success' => false
    );

    if($shippingMethod->exists()) {

      $shippingMethod = $shippingMethod->first();

      $result = array(
        'success' => true,
        'description' => !empty($shippingMethod->description) ? $shippingMethod->description : 'ไม่ระบุรายระเอียดวิธีการจัดส่งสินค้า <strong>'.$shippingMethod->name.'</strong>'
      );

    }

    return response()->json($result);

  }

  public function uploadAttachedFile() {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      $this->error = array(
        'message' => 'ขออภัย ไม่อนุญาตให้เข้าถึงหน้านี้ได้'
      );
      return $this->error();
    }

    if(empty(Input::file('file'))) {
      return $this->getGeneralError();
    }

    $result = array('success' => false);

    $file = new handleAttachedFile(Input::file('file'));

    if($file->checkFileSize() && $file->checkFileType()) {

      $tempFile = Service::loadModel('TemporaryFile');

      $tempFile->fill(array(
        'model' => Input::get('model'),
        'token' => Input::get('token'),
        'filename' => $file->getFileName(),
        'alias' => $file->getAlias()
      ))->save();

      $temporaryPath = $tempFile->createTemporyFolder(Input::get('model').'_'.Input::get('token').'_attached_file');

      $moved = $tempFile->moveTemporaryFile($file->getRealPath(),$file->getAlias(),array(
        'directoryName' => Input::get('model').'_'.Input::get('token').'_attached_file'
      ));
      
      if($moved) {
        $result = array(
          'success' => true,
          'filename' => $file->getAlias()
        );
      }

    }

    return response()->json($result);

  }

  public function uploadImage() {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      $this->error = array(
        'message' => 'ขออภัย ไม่อนุญาตให้เข้าถึงหน้านี้ได้'
      );
      return $this->error();
    }

    if(empty(Input::file('image'))) {
      return $this->getGeneralError();
    }

    $result = array('success' => false);

    $image = new HandleImageFile(Input::file('image'));

    if($image->checkFileSize() && $image->checkFileType()) {
    
      $tempFile = Service::loadModel('TemporaryFile');

      if(!$tempFile->checkExistSpecifiedTemporaryRecord(Input::get('model'),Input::get('imageToken'))) {
        $tempFile->fill(array(
          'model' => Input::get('model'),
          'token' => Input::get('imageToken')
        ))->save();
      }

      $filename = $image->getFileName();
  
      list($width,$height) = $image->generateImageSize(Input::get('imageType'));

      $temporaryPath = $tempFile->createTemporyFolder(Input::get('model').'_'.Input::get('imageToken').'_'.Input::get('imageType'));

      $imageTool = new ImageTool($image->getRealPath());
      $imageTool->png2jpg($width,$height);
      $imageTool->resize($width,$height);
      $moved = $imageTool->save($temporaryPath.$filename);

      // $moved = $tempFile->moveTemporaryFile($image->getRealPath(),$filename,array(
      //   'directoryName' => Input::get('model').'_'.Input::get('imageToken').'_'.Input::get('imageType')
      // ));

      if($moved) {
        $result = array(
          'success' => true,
          'filename' => $filename
        );
      }

    }

    return response()->json($result);
  }

  public function uploadProfileImage() {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      $this->error = array(
        'message' => 'ขออภัย ไม่อนุญาตให้เข้าถึงหน้านี้ได้'
      );
      return $this->error();
    }

    $result = array(
      'success' => false,
    );

    $acceptedModels = array(
      'Shop',
      'Person'
    );

    $acceptedType = array(
      'cover',
      'profile-image'
    );

    if(!in_array(Input::get('model'), $acceptedModels) || !in_array(Input::get('imageType'), $acceptedType)) {
      return $this->getGeneralError();
    }

    $model = Service::loadModel(Input::get('model'))->find(Input::get('model_id'));

    // Check permission
    $permission = true;
    switch (Input::get('model')) {
      case 'Shop':
        $permission = $model->checkPersonHasShopPermission();
        break;
    }

    if(!$permission) {
      return $this->getGeneralError();
    }

    switch (Input::get('imageType')) {
      case 'profile-image':
        $field = 'profile_image_id';
        break;
      
      case 'cover':
        $field = 'cover_image_id';
        break;
    }

    if(!Schema::hasColumn($model->getTable(), $field)) {
      return $this->getGeneralError();
    }

    $imageModel = Service::loadModel('Image')->find($model->{$field});

    if(empty($imageModel)) {
      $imageModel = Service::loadModel('Image');
    }

    $imageId = $imageModel->saveImage($model,Input::file('image'),array(
      'type' => Input::get('imageType')
    ));

    $model->{$field} = $imageId;

    if($model->save()) {
      $result = array(
        'success' => true
      );
    };

    return response()->json($result);

  }

  public function cartAdd() {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      $this->error = array(
        'message' => 'ขออภัย ไม่อนุญาตให้เข้าถึงหน้านี้ได้'
      );
      return $this->error();
    }

    $cartModel = Service::loadModel('Cart');

    $productId = Input::get('productId');
    $quantity = Input::get('quantity');

    $saved = $cartModel->addProduct($productId,$quantity);

    if($saved['hasError']) {
      $result = $saved;
    }else{
      $result = array(
        'success' => $saved
      );
    }

    return response()->json($result);

  }

  public function cartUpdate() {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      $this->error = array(
        'message' => 'ขออภัย ไม่อนุญาตให้เข้าถึงหน้านี้ได้'
      );
      return $this->error();
    }

    $result = array(
      'html' => view('layouts.blackbox.components.global-cart-product-list',array(
        '_products' => Service::loadModel('Cart')->getProducts()
      ))->render()
    );
    return response()->json($result);
  }

  public function productCount() {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      $this->error = array(
        'message' => 'ขออภัย ไม่อนุญาตให้เข้าถึงหน้านี้ได้'
      );
      return $this->error();
    }

    $result = array(
      'total' => Service::loadModel('Cart')->productCount()
    );
    return response()->json($result);
  }

  public function notificationUpdate() {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      $this->error = array(
        'message' => 'ขออภัย ไม่อนุญาตให้เข้าถึงหน้านี้ได้'
      );
      return $this->error();
    }

    $notificationModel = Service::loadModel('Notification');

    $notifications = $notificationModel
    ->where(function($query){
      $query->where([
        ['receiver','like','Person'],
        ['receiver_id','=',session()->get('Person.id')]
      ]);
    })
    ->where('notify','=','2')
    ->orderBy('created_at','asc')
    ->take(1);

    if(!$notifications->exists()) {
      return response()->json(array(
        'updated' => false
      )); 
    }
      
    $notification = $notifications->first();
    $notification->notify = 0;
    $notification->save();

    $result = array(
      'updated' => true,
      'title' => $notification->title,
      'count' => $notificationModel->countUnreadNotification(),
      'html' => view('layouts.blackbox.components.global-notification-item',array(
        '_notification' => $notification->buildModelData()
      ))->render()
    );

    return response()->json($result);

  }

  public function notificationRead() {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      $this->error = array(
        'message' => 'ขออภัย ไม่อนุญาตให้เข้าถึงหน้านี้ได้'
      );
      return $this->error();
    }

    $notifications = Service::loadModel('Notification')
    ->where(function($query){
      $query->where([
        ['receiver','like','Person'],
        ['receiver_id','=',session()->get('Person.id')]
      ]);
    })
    ->where('unread','=','1')
    ->where('notify','=','0')
    ->orderBy('created_at','desc');

    if($notifications->exists()) {

      foreach ($notifications->get() as $notification) {
        $notification->unread = 0;
        $notification->save();
      }

    }

    return response()->json(true); 

  }

  private function getGeneralError() {

    $result = array(
      'success' => false,
      'message' => array(
        'type' => 'error',
        'title' => 'เกิดข้อผิดพลาด กรุณารีเฟรช แล้วลองอีกครั้ง'
      )
    );

    return response()->json($result);

  }

}
