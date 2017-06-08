<?php

namespace App\library;

class validation 
{
  protected $operators = [
      '=', '<', '>', '<=', '>=', '<>', '!=',
      'like', 'like binary', 'not like', 'between', 'ilike',
      '&', '|', '^', '<<', '>>',
      'rlike', 'regexp', 'not regexp',
      '~', '~*', '!~', '!~*', 'similar to',
      'not similar to',
  ];

  public function isCurrency($number) {
    if(preg_match('/^[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$/', $number)) {
      return true;
    }
    return false;
  }

  public function isPhoneNumber($phoneNumber) {
    if(preg_match('/^[0-9+][0-9\-]{3,}[0-9]$/', $phoneNumber)) {
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

  public function isZipcode($zipcode) {
    if(preg_match('/^[0-9]{5}$/', $zipcode)) {
      return true;
    }
    return false;
  }

  public function isSqlOperators($operators) {
    if(in_array($operators, $this->operators)) {
      return true;
    } 
    return false;
  }

}

?>