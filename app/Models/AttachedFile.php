<?php

namespace App\Models;

use App\library\string;
use File;

class AttachedFile extends Model
{
  protected $table = 'attached_files';
  protected $fillable = ['model','model_id','path','filename','person_id'];

  protected $storagePath = 'app/public/attached_files/';

  public function __saveRelatedData($model,$options = array()) {
    $this->handleFiles($model,$options['value']['files'],$options['value']);
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

      // return $attechedFileInstance->id;

    }

    // remove temp dir
    $temporaryFile->deleteTemporaryDirectory($model->modelName.'_'.$options['token'].'_attached_file');
    // remove temp file record
    $temporaryFile->deleteTemporaryRecords($model->modelName,$options['token']);

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

}
