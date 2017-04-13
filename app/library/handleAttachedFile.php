<?php

namespace App\library;

class HandleAttachedFile
{

  private $file;
  private $filesize;
  private $mimeType;
  private $alias;

  public function __construct($file = null) {

    if(!empty($file)) {
      $this->file = $file;
      $this->filesize = $file->getSize();
      $this->mimeType = $file->getMimeType();

      $this->alias = Token::generate(6);

    }

  }

  public function getFileName() {
    return $this->file->getClientOriginalName();
  }

  public function getRealPath() {
    return $this->file->getRealPath();
  }

  public function getAlias() {
    return $this->alias;
  }

  public function getFilesize() {
    return $this->filesize;
  }

  public function checkFileType() {
    // $acceptedFileTypes = array(
    //   'image/jpg',
    //   'image/jpeg',
    //   'image/png', 
    //   'image/pjpeg',
    //   'application/pdf',
    //   'text/plain',
    //   // Microsoft Office MIME types for HTTP Content Streaming
    //   'application/msword',
    //   'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    //   // 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
    //   // 'application/vnd.ms-word.document.macroEnabled.12',
    //   // 'application/vnd.ms-word.template.macroEnabled.12',
    //   'application/vnd.ms-excel',
    //   'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    //   // 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
    //   // 'application/vnd.ms-excel.sheet.macroEnabled.12',
    //   // 'application/vnd.ms-excel.template.macroEnabled.12',
    //   // 'application/vnd.ms-excel.addin.macroEnabled.12',
    //   // 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
    //   'application/vnd.ms-powerpoint',
    //   'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    //   // 'application/vnd.openxmlformats-officedocument.presentationml.template',
    //   // 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
    //   // 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
    //   // 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
    //   // 'application/vnd.ms-powerpoint.template.macroEnabled.12',
    //   // 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
    //   // 'application/vnd.ms-access',
    // );

    $ignore = array(
      'application/x-msdownload'
    );
  
    return !in_array($this->mimeType, $ignore);

  }

  public function checkFileSize() {
    $maxFileSizes = 2097152;

    if($this->filesize <= $maxFileSizes){
      return true;
    }
    return false;
  }

  public function getIcon() {

    $images = array(
      'image/jpg',
      'image/jpeg',
      'image/png', 
      'image/pjpeg',
    );

    $documents = array(
      'application/pdf',
      'text/plain',
      'application/msword',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      'application/vnd.ms-excel',
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      'application/vnd.ms-powerpoint',
      'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    );

    $icon = '';
    if(in_array($this->mimeType, $images)) {
      $icon = '/images/icons/image-white.png';;
    }elseif(in_array($this->mimeType, $documents)) {
      $icon = '/images/icons/document-white.png';
    }

    return $icon;

  }

}

?>