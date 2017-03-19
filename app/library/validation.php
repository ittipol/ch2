<?php

namespace App\library;

class validation 
{
  public function isCurrency($number) {
    if(preg_match('/^[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$/', $number)) {
      return true;
    }
    return false;
  }

  public function isNumber($number) {
    if(preg_match('/^[0-9]+$/', $number)) {
      return true;
    }
    return false;
  }

  public function isDecimal($number) {
    if(preg_match('/^[0-9]+(?:\.?[0-9]+)?$/', $number)) {
      return true;
    }
    return false;
  }

}

?>