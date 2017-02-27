<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\imageTool;
use App\library\handleImageFile;
use Input;
use Session;
use Schema;

class ApiController extends Controller
{ 

  public function GetDistrict($provinceId = null) {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      exit('Error!!!');  //trygetRealPath detect AJAX request, simply exist if no Ajax
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
      exit('Error!!!');  //trygetRealPath detect AJAX request, simply exist if no Ajax
    }

    $records = Service::loadModel('SubDistrict')->where('district_id', '=', $districtId)->get(); 

    $subDistricts = array();
    foreach ($records as $record) {
      $subDistricts[$record->id] = $record->name;
    }

    return response()->json($subDistricts);
  }

  public function GetCategory($parentId = null) {

    // if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    //   $this->error = array(
    //     'message' => 'ขออภัย ไม่อนุญาตให้เข้าถึงหน้านี้ได้'
    //   );
    //   return $this->error();
    // }

    $category = Service::loadModel('Category');

    // if(empty($parentId)) {
    //   // $records = $category->whereNull('parent_id')->get();
    //   $records = $category->where('parent_id','=',null)->get();
    // }else{
    //   $records = $category->where('parent_id','=',$parentId)->get();
    // }

    $records = $category
    ->where('parent_id','=',$parentId)
    ->get();
    
    $categories = array();
    foreach ($records as $record) {

      // check has sub category
      $subCat = $category->where('parent_id','=',$record->id)->first();

      $next = false;
      if(!empty($subCat)) {
        $next = true;
      }

      $categories[] = array(
        'id' => $record->id,
        'name' => $record->name,
        's' => $next
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
      exit('Error!!!');  //trygetRealPath detect AJAX request, simply exist if no Ajax
    }

    if(empty(Input::file('image'))) {
      return $this->generalError();
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
      exit('Error!!!');  //trygetRealPath detect AJAX request, simply exist if no Ajax
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
      return $this->generalError();
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
      return $this->generalError();
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
      return $this->generalError();
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

  private function generalError() {

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
