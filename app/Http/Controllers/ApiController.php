<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\imageTool;
use App\library\handleImageFile;
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

    $image = new HandleImageFile(Input::file('image'));

    $result = array(
      'success' => false,
    );

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

    $acceptModels = array(
      'Shop',
      'Person'
    );

    $acceptType = array(
      'cover',
      'profile-image'
    );

    if(!in_array(Input::get('model'), $acceptModels) || !in_array(Input::get('imageType'), $acceptType)) {
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

    $success = Service::loadModel('Cart')->addProduct(Input::get('productId'),Input::get('quantity'));

    // $value = array(
    //   'session_id' => session()->getId(),
    //   'productId' => $productId,
    //   'quantity' => $quantity
    // );

    // if(Auth::check()) {
    //   $value['person_id'] = session()->get('Person.id');
    // }

    // if(session()->has('carts.'.$productId)) {
    //   $data = session()->get('carts.'.$productId);
    //   $data['quantity'] += $quantity;
    // }else{
    //   $data = array(
    //     'productId' => $productId,
    //     'quantity' => $quantity
    //   );
    // }

    // session()->put('carts.'.$productId, $data);

    // $data = array(
    //   'productId' => $productId,
    //   'quantity' => $quantity
    // );

    // session()->put('carts', serialize($data));

    // Cookie::queue('carts'.$productId, $data, 1440);

    $result = array(
      'success' => $success
    );

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
