<?php

namespace App\library;

use File;

class Cache
{
  private $cachePath = 'cache/';
  private $imageCache = array(
    'xs' => array(
      'width' => 50,
      'height' => 50,
      'fx' => '',
    ),
    'sm' => array(
      'width' => 100,
      'height' => 100,
      'fx' => '',
    ),
    'xsm' => array(
      'width' => 150,
      'height' => 150,
      'fx' => '',
    ),
    // 'md' => array(
    //   'width' => 250,
    //   'height' => 250,
    //   'fx' => '',
    // ),
    'productOption' => array(
      'width' => 50,
      'height' => 50,
      'fx' => 'getImageSizeByRatio',
    ),
    'list' => array(
      'width' => 380,
      'height' => 380,
      'fx' => 'getImageSizeByRatio',
    ),
    // 'banner' => array(
    //   'width' => 1200,
    //   'height' => 300,
    //   'fx' => 'getImageSizeByRatio',
    // ),
  );

  public function __construct($image = null) {
    $url = new Url;
    $this->cachePath = $url->addSlash(storage_path($this->cachePath));
  }

  public function getCacheImagePath($filename) {
    
    $url = new Url;

    $parts = explode('_', $filename);
    $cacheFile = $url->addSlash($this->cachePath.$parts[0]).$filename;

    if(!file_exists($cacheFile)) {
      return false;
    }

    return $cacheFile;

  }

  public function getCacheImageUrl($model,$alias) {

    $url = new Url;

    $path = $model->getImagePath();

    if(!file_exists($path) || empty($this->imageCache[$alias])){
      return false;
    }

    $ext = pathinfo($model->filename, PATHINFO_EXTENSION);
    $filename = pathinfo($model->filename, PATHINFO_FILENAME);
    list($originalWidth,$originalHeight) = getimagesize($path);

    $value = $this->imageCache[$alias];

    $width = $value['width'];
    $height = $value['height'];

    if(!empty($value['fx'])) {
      list($width,$height) = $this->getImageSizeByRatio($originalWidth,$originalHeight,$width,$height);
    }

    $newFilename = $filename.'_'.$width.'x'.$height.'.'.$ext;
    $cachePath = $url->addSlash($this->cachePath.$filename);
    $cacheFile = $cachePath.$newFilename;

    if(!file_exists($cacheFile) && !$this->_cacheImage($path,$width,$height,$cachePath,$cacheFile)) {
      return false;
    }

    return '/safe_image/'.$newFilename;
  }

  private function _cacheImage($path,$width,$height,$cachePath,$cacheFile) {

    if(!is_dir($cachePath)){
      mkdir($cachePath,0777,true);
    }

    $imageLib = new ImageTool($path);
    $imageLib->resize($width,$height);
    return $imageLib->save($cacheFile);

  }

  public function cacheImage($model,$alias) {

    $url = new Url;

    $path = $model->getImagePath();

    if(!file_exists($path) || empty($this->imageCache[$alias])){
      return false;
    }

    $ext = pathinfo($model->filename, PATHINFO_EXTENSION);
    $filename = pathinfo($model->filename, PATHINFO_FILENAME);
    list($originalWidth,$originalHeight) = getimagesize($path);

    $value = $this->imageCache[$alias];

    $width = $value['width'];
    $height = $value['height'];

    if(!empty($value['fx']) && method_exists($this,$value['fx'])) {
      list($width,$height) = $this->getImageSizeByRatio($originalWidth,$originalHeight,$width,$height);
    }

    $cachePath = $url->addSlash($this->cachePath.$filename);
    if(!is_dir($cachePath)){
      mkdir($cachePath,0777,true);
    }

    $filename = $filename.'_'.$width.'x'.$height.'.'.$ext;

    $imageLib = new ImageTool($path);
    $imageLib->resize($width,$height);
    return $imageLib->save($cachePath.$filename);

  }

  public function getImageSizeByRatio($originalWidth,$originalHeight,$width,$height) {

    // if($height > $width) {
    //   $width = (int)ceil($originalWidth * ($height / $originalHeight));
    // }else{
    //   $height = (int)ceil($originalHeight * ($width / $originalWidth));
    // }

    if(($originalHeight > $originalWidth) && (($originalHeight - $originalWidth) > 250)) {
      if($originalHeight > $height) {
        $width = (int)ceil($originalWidth * ($height / $originalHeight));
      }else{
        $height = (int)ceil($originalHeight * ($width / $originalWidth));
      }
    }elseif(($originalWidth > $originalHeight) && (($originalWidth - $originalHeight) > 250)) {
      if($originalWidth > $width) {
        $width = (int)ceil($originalWidth * ($height / $originalHeight));
      }else{
        $height = (int)ceil($originalHeight * ($width / $originalWidth));
      }
    }

    return array($width,$height);
  }

  public function cacheDirectoryExist($directoryName) {
    return is_dir($this->cachePath.$directoryName);
  }

  public function deleteCacheDirectory($directoryName) {

    if(!$this->cacheDirectoryExist($directoryName)) {
      return false;
    }

    return File::deleteDirectory($this->cachePath.$directoryName);
  }

}