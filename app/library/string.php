<?php

namespace App\library;

class String
{
  public function subString($string,$len,$stripTag = true){

    $string = iconv(mb_detect_encoding($string, mb_detect_order(), true), "UTF-8", $string);
    mb_internal_encoding('UTF-8');

    if(empty($string)) {
      return '-';
    }

    if($stripTag){
      $string = strip_tags($string);
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
}