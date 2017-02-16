<?php

namespace App\Models;

class ImageStyle extends Model
{
  protected $table = 'image_styles';

  public function getImageSizeByRatio($originalWidth,$originalHeight,$width,$height) {

    if (($originalWidth > $originalHeight) && (abs($originalWidth - $originalHeight) > 280)){
      $width = (int)ceil($originalWidth*($height/$originalHeight));
    } else if (($originalWidth < $originalHeight) && (abs($originalWidth - $originalHeight) > 280)){
      $height = (int)ceil($originalHeight*($width/$originalWidth));
    }

    return array($width,$height);
  }

}
