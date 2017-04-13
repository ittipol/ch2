<?php

namespace App\Models;

use App\library\string;
use App\library\url;
use File;

class AttachedFile extends Model
{
  protected $table = 'attached_files';
  protected $fillable = ['model','model_id','path','filename','filesize','alias','person_id'];
  protected $modelRelations = array('AttachedFileAccessPermission');

  protected $storagePath = 'app/public/attached_files/';

  public static function boot() {

    parent::boot();

    AttachedFile::deleted(function($model) {
      // delete image file after image record is deleted
      $model->deleteFile();
    });

  }

  public function __saveRelatedData($model,$data = array()) {

    if(empty($data['value']['files'])) {
      return false;
    }

    if(!empty($data['options']['deleteBeforeSaving']) && $data['options']['deleteBeforeSaving']) {
      // Delete old record
      $files = $this->where([
        ['model','like','PersonApplyJob'],
        ['model_id','=',$model->id]
      ]);

      if($files->exists()) {
        foreach ($files->get() as $file) {
          $file->delete();
        }

        $this->deleteDirectory('PersonApplyJob',$model->id);
      }
    }

    return $this->handleFiles($model,$data['value']['files'],$data['value']);
    
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
      $attechedFileInstance->filename = $tempFile->filename;
      $attechedFileInstance->filesize = $tempFile->filesize;
      $attechedFileInstance->alias = $tempFile->alias;

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

      $this->moveFile($path,$attechedFileInstance->getFilePath());

      $this->fileAccessPermission($model,$attechedFileInstance);

    }

    // remove temp dir
    $temporaryFile->deleteTemporaryDirectory($model->modelName.'_'.$options['token'].'_attached_file');
    // remove temp file record
    $temporaryFile->deleteTemporaryRecords($model->modelName,$options['token']);

    return true;

  }

  public function getFullDirPath($modelName = '',$modelId = '') {
    
    $string = new String;

    if(empty($modelName)) {
      $modelName = $this->model;
    }

    if(empty($modelId)) {
      $modelId = $this->model_id;
    }

    return storage_path($this->storagePath.$string->generateUnderscoreName($modelName)).'/'.$modelId.'/';

  }

  public function getFilePath($alias = '') {

    if(empty($alias)) {
      $alias = $this->alias;
    }

    return $this->getFullDirPath().$alias;
  }

  public function moveFile($oldPath,$to) {
    return File::move($oldPath, $to);
  }

  public function deleteFile() {

    $path = $this->getFilePath();

    if(!file_exists($path)){
      return false;
    }

    File::Delete($path);

    return true;

  }

  public function deleteDirectory($modelName,$modelId) {

    $path = $this->getFullDirPath($modelName,$modelId);

    if(!$this->checkDirectoryExist($path)) {
      return false;
    }

    return File::deleteDirectory($path);
  }

  public function checkDirectoryExist($directoryName) {
    return is_dir($directoryName);
  }

  public function fileAccessPermission($model,$file) {

    $attachedFileAccessPermission = new AttachedFileAccessPermission;

    switch ($model->modelName) {
      case 'Message':

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

        break;
      
      case 'PersonApplyJob':
     
        $attachedFileAccessPermission->newInstance()
        ->fill(array(
          'model' => 'Person',
          'model_id' => $model->person_id,
          'attached_file_id' => $file->id
        ))
        ->save();

        $attachedFileAccessPermission->newInstance()
        ->fill(array(
          'model' => 'Shop',
          'model_id' => $model->shop_id,
          'attached_file_id' => $file->id
        ))
        ->save();

        break;
    }

  }


  public function hasPermission() {

    $permissions = $this->getRelatedData('AttachedFileAccessPermission',array(
      'fields' => array('model','model_id')
    ));

    $hasPermission = false;
    foreach ($permissions as $permission) {
      
      switch ($permission->model) {
        case 'Shop':
          
          $personToShopModel = new PersonToShop;
          $records = $personToShopModel->getByShopId($permission->model_id);

          $personIds = array();
          foreach ($records as $record) {
            $personIds[] = $record->person_id; 
          }


          $hasPermission = in_array(session()->get('Person.id'), $personIds);

          break;
        
        case 'Person':
          
          if(session()->get('Person.id') == $permission->model_id) {
            $hasPermission = true;
          }

          break;
      }

      if($hasPermission) {
        break;
      }

    }

    return $hasPermission;

  }

  public function getFilesize() {
    return $this->bytesToSize($this->filesize);
  }

  public function fileAttachmentUrl() {
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

    $string = new String;

    return array(
      'filename' => $string->truncString($this->filename,20),
      'filesize' => $this->getFilesize(),
      'downloadUrl' => $this->fileAttachmentUrl()
    );

  }

}
