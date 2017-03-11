<?php

namespace App\library;

class Measurement 
{
  public function format($number) {
    
    list($decimal,$point) = explode('.', $number);

    if((int)$point == 0) {
      return number_format($number, 0, '.', '');
    }

    return number_format($number, 2, '.', '');

  }

  // public function convertToGram($unit,$weight) {

  //   $gram = null;

  //   switch ($unit) {
  //     case 'kg':
  //       $gram = $this->kgToGram($weight);
  //       break;

  //     case 'g':
  //       $gram = $weight;
  //       break;
      
  //     case 'L':
  //       $gram = $this->LitreToGram($weight);
  //       break;

  //     case 'ml':
  //       $gram = $weight;
  //       break;

  //     // case 'oz':
  //     //   break;
  //   }

  //   return $gram;

  // }

  // public function kgToGram($kg) {
  //   return $kg * 1000;
  // }

  // public function LitreToGram($litre) {
  //   return $litre * 1000;
  // }

  // public function mlToGram($ml) {
  //   return $ml;
  // }

  // public function OzToGram($oz) {
  //   return $oz * 28.3495231;
  // }
}

?>