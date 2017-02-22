<?php

namespace App\library;

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
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
  }

  // public static function generateUnderscoreName($modelName) {

  //   $alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  //   $len = strlen($modelName);

  //   $parts = array();
  //   $loop = true;
  //   $index = 0;
  //   $len = strlen($modelName);
  //   $modelName = lcfirst($modelName);

  //   while($loop) {

  //     if(strpos($alpha, $modelName[$index])) {
  //       $parts[] = substr($modelName, 0, $index);
  //       $modelName = lcfirst(substr($modelName, $index));
  //       $len = strlen($modelName);
  //       $index = 0;
  //     }

  //     $index++;

  //     if(($index+1) > $len) {
  //       $parts[] = $modelName;
  //       $loop = false;
  //     }

  //   }

  //   return implode('_', $parts);

  // }

  public static function getList($records,$field) {

    $lists = array();
    foreach ($records as $record) {
      $lists[] = $record->{$field};
    }

    return $lists;

  }

}
