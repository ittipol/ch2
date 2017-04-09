<?php

namespace App\Models;

use App\library\string;
use App\library\url;
use File;

class AttachedFile extends Model
{
  protected $table = 'attached_files';
  protected $fillable = ['model','model_id','path','filename','filesize','person_id'];

  protected $storagePath = 'app/public/attached_files/';

  public function __saveRelatedData($model,$options = array()) {

    if(!empty($options['value']['files'])) {
      $this->handleFiles($model,$options['value']['files'],$options['value']);
    }
    
  }

  public function handleFiles($model,$files,$options = array()) {

    $temporaryFile = new TemporaryFile;

    foreach ($files as $file) {

      $attechedFileInstance = $this->newInstance();

      $tempFile = $temporaryFile->where([
        ['model','like',$model->modelName],
        ['token','=',$options['token']],
        ['alias','=',$file['filename']]
      ]);

      if(!$tempFile->exists()) {
        continue;
      }

      $tempFile = $tempFile->first();

      $attechedFileInstance->model = $model->modelName;
      $attechedFileInstance->model_id = $model->id;
      $attechedFileInstance->path = $tempFile->alias;
      $attechedFileInstance->filename = $tempFile->filename;
      $attechedFileInstance->filesize = $tempFile->filesize;

      if(!$attechedFileInstance->save()) {
        continue;
      }

      $path = $temporaryFile->getFilePath($tempFile->alias,array(
        'directoryName' => $model->modelName.'_'.$options['token'].'_attached_file'
      ));

      $toPath = $attechedFileInstance->getFullDirPath();
      if(!is_dir($toPath)){
        mkdir($toPath,0777,true);
      }

      $this->moveFile($path,$attechedFileInstance->getImagePath());

      $this->fileAccessPermission($model,$attechedFileInstance);

    }

    // remove temp dir
    $temporaryFile->deleteTemporaryDirectory($model->modelName.'_'.$options['token'].'_attached_file');
    // remove temp file record
    $temporaryFile->deleteTemporaryRecords($model->modelName,$options['token']);

  }

  public function fileAccessPermission($model,$file) {

    $attachedFileAccessPermission = new AttachedFileAccessPermission;

    $attachedFileAccessPermission->newInstance()
    ->fill(array(
      'model' => $model->sender,
      'model_id' => $model->sender_id,
      'attached_file_id' => $file->id
    ))
    ->save();

    $attachedFileAccessPermission->newInstance()
    ->fill(array(
      'model' => $model->receiver,
      'model_id' => $model->receiver_id,
      'attached_file_id' => $file->id
    ))
    ->save();

  }

  public function getFullDirPath() {
    
    $string = new String;

    return storage_path($this->storagePath.$string->generateUnderscoreName($this->model)).'/'.$this->model_id.'/'.$this->path.'/';
  }

  public function getImagePath($filename = '') {

    if(empty($filename)) {
      $filename = $this->filename;
    }

    return $this->getFullDirPath().$filename;
  }

  public function moveFile($oldPath,$to) {
    return File::move($oldPath, $to);
  }

  public function getFilesize() {
    return $this->bytesToSize($this->filesize);
  }

  public function buildUrl() {
    $url = new Url;
    return $url->url('/get_file_attachment/'.$this->id);
  }

  public function bytesToSize($bytes) {
    $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if ($bytes == 0) return '0 Byte';
    $i = (int)(floor(log($bytes) / log(1024)));
    return round($bytes / pow(1024, $i), 2).' '.$sizes[$i];
  }

  public function buildModelData() {

    return array(
      'filename' => $this->filename,
      'filesize' => $this->getFilesize(),
      'downloadUrl' => $this->buildUrl()
    );

  }

}
