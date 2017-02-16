<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\imageTool;
use App\library\handleImageFile;
use Input;
use Session;

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

  public function uploadImage() {

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      exit('Error!!!');  //trygetRealPath detect AJAX request, simply exist if no Ajax
    }

    if(empty(Input::file('image'))) {
      $result = array(
        'success' => false,
        'message' => array(
          'type' => 'error',
          'title' => 'เกิดข้อผิดพลาด กรุณารีเฟรช แล้วลองอีกครั้ง'
        )
      );

      return response()->json($result);
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

      // cover don't resize

      $imageTool = new ImageTool($image->getRealPath());
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

}
