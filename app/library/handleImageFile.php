<?php

namespace App\library;

class HandleImageFile
{
  private $image;
  private $filename;
  private $width;
  private $height;
  private $maxFileSizes;
  private $acceptedFileTypes;

  public function __construct($image = null) {

    if(!empty($image)) {

      $this->image = $image;
      $this->generateFileName();

      list($this->width,$this->height) = getimagesize($this->image->getRealPath());

      $model = Service::loadModel('Image');
      $this->maxFileSizes = $model->getMaxFileSizes();
      $this->acceptedFileTypes = $model->getAcceptedFileTypes();

    }

  }

  private function generateFileName() {
    $this->filename = time().Token::generateNumber(15).$this->image->getSize().'.'.$this->image->getClientOriginalExtension();
  }

  public function getFileName() {
    return $this->filename;
  }

  public function getOriginalFileName() {
    return $this->image->getClientOriginalName();
  }

  public function getRealPath() {
    return $this->image->getRealPath();
  }

  public function checkFileType() {
    return in_array($this->image->getMimeType(), $this->acceptedFileTypes);
  }

  public function checkFileSize() {
    if($this->image->getSize() <= $this->maxFileSizes){
      return true;
    }
    return false;
  }

  public function generateImageSize($imageType,$originalWidth = null,$originalHeight = null){

    $accepteType = array('photo','profile-image');

    if(empty($originalWidth)) {
      $originalWidth = $this->width; 
    }

    if(empty($originalHeight)) {
      $originalHeight = $this->height; 
    }

    if(!in_array($imageType, $accepteType)) {
      return array($originalWidth,$originalHeight);
    }

    $ratio = abs($originalWidth/$originalHeight);

    $width = $originalWidth;
    $height = $originalHeight;

    if($imageType == 'photo') {

      if(($originalWidth > 960) || ($originalHeight > 960)) {

        if($originalWidth > $originalHeight) {

          $width = 960;

          if(($ratio > 1) && ($ratio < 1.6)) {
            $width = $originalWidth/2;
          } 

          $height = round($originalHeight*($width/$originalWidth));

        }elseif($originalWidth < $originalHeight) {

          $height = 960;

          if(($ratio > 1) && ($ratio < 1.6)) {
            $height = $originalHeight/2;
          } 

          $width = round($originalWidth*($height/$originalHeight));

        }else {
          // ratio = 1
          $width = 960;
          $height = 960;
        }  

      }

    }elseif($imageType == 'profile-image') {
      
      if($height > 400) {
        // or automatic crop
        // top_x (imageWidth - cropsizewidth) / 2
        // top_y (imageHeight - cropsizeheight) / 2
        // bottom_x = top_x + 400;
        // bottom_y = top_y + 400

        $height = 400;
        $width = round($originalWidth*($height/$originalHeight));
      }
    }

    return array($width,$height);

  }

}