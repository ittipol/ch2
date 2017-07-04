<?php

namespace App\library;

use App\Models\UserLog;
use Request;
use Auth;

class Service
{
  public static function loadModel($modelName) {
    $class = 'App\Models\\'.$modelName;

    if(!class_exists($class)) {
      return false;
    }

    return new $class;
  }

  public static function ipAddress() {
    // $ipaddress = null;
    // if (getenv('HTTP_CLIENT_IP'))
    //     $ipaddress = getenv('HTTP_CLIENT_IP');
    // else if(getenv('HTTP_X_FORWARDED_FOR'))
    //     $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    // else if(getenv('HTTP_X_FORWARDED'))
    //     $ipaddress = getenv('HTTP_X_FORWARDED');
    // else if(getenv('HTTP_FORWARDED_FOR'))
    //     $ipaddress = getenv('HTTP_FORWARDED_FOR');
    // else if(getenv('HTTP_FORWARDED'))
    //    $ipaddress = getenv('HTTP_FORWARDED');
    // else if(getenv('REMOTE_ADDR'))
    //     $ipaddress = getenv('REMOTE_ADDR');
    // else
    //     $ipaddress = 'UNKNOWN';
    // return $ipaddress;

    return Request::ip();

  }

  public static function addUserLog($modelName,$id,$action) {

    if(!Auth::check()) {
      return false;
    }

    $userLogModel = new UserLog;
    $userLogModel->model = $modelName;
    $userLogModel->model_id = $id;
    $userLogModel->action = $action;
    $userLogModel->ip_address = Request::ip();
    $userLogModel->user_id = Auth::user()->id;

    return $userLogModel->save();

  }

  public static function getList($records,$field) {

    $lists = array();
    foreach ($records as $record) {
      $lists[] = $record->{$field};
    }

    return $lists;

  }

}
