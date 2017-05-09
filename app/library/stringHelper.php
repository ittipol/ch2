<?php

namespace App\library;

class stringHelper
{
  public function truncString($string,$len,$stripTag = true,$cleanText = false){

    $string = iconv(mb_detect_encoding($string, mb_detect_order(), true), "UTF-8", $string);
    mb_internal_encoding('UTF-8');

    if(empty($string)) {
      return '';
    }

    if($stripTag){
      $string = strip_tags($string);
    }

    if($cleanText) {
      $string = preg_replace('/\s+/', ' ', $string);
    }

    $_string = $string;

    if(mb_strlen($string) <= $len) {
      return $string;
    }

    $string = mb_substr($string, 0, $len);
    $lastChar = mb_substr($string, $len-1, 1);

    if(ord($lastChar) != 32) {
      $pos = mb_strpos($_string,' ',$len);
      if(!empty($pos)) {
        $string = mb_substr($_string, 0, $pos).'...';
      }
    }

    return $string;

  }

  public function generateModelNameCamelCase($modelName) {

    $parts = explode('_', $modelName);

    $modelName = array();
    foreach ($parts as $part) {
      $modelName[] = ucfirst($part);
    };

    return implode('', $modelName);

  }

  public static function generateUnderscoreName($modelName) {

    $alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $len = strlen($modelName);

    $parts = array();
    $loop = true;
    $index = 0;
    $len = strlen($modelName);
    $modelName = lcfirst($modelName);

    while($loop) {

      if(strpos($alpha, $modelName[$index]) > -1) {
        $parts[] = substr($modelName, 0, $index);
        $modelName = lcfirst(substr($modelName, $index));
        $len = strlen($modelName);
        $index = 0;
      }

      $index++;

      if(($index+1) > $len) {
        $parts[] = $modelName;
        $loop = false;
      }

    }

    return implode('_', $parts);

  }
  
}