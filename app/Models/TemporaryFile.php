<?php

namespace App\Models;

use App\library\url;
use File;
use Session;

class TemporaryFile extends Model
{
  protected $table = 'temporary_files';
  protected $fillable = ['model','filename','token','file_type','person_id'];
  private $temporaryPath = 'temporary/';

  public function __construct() {

    parent::__construct();
    
    $this->temporaryPath = storage_path($this->temporaryPath);
  }

  public function createTemporyFolder($folderName) {

    $url = new Url;

    $temporaryPath = $this->temporaryPath;

    if(!is_dir($temporaryPath)){
      mkdir($temporaryPath,0777,true);
    }

    $temporaryPath .= $folderName;

    if(!is_dir($temporaryPath)){
      mkdir($temporaryPath,0777,true);
    }

    return $url->addSlash($temporaryPath);

  }

  // public function moveTemporaryFile($oldPath,$filename,$options = array()) {

  //   $temporaryPath = $this->createTemporyFolder($options['directoryName']);

  //   return File::move($oldPath, $temporaryPath.$filename);
  // }

  public function getFilePath($filename,$options = array()) {

    $temporaryPath = $this->temporaryPath;

    if(!empty($options['directoryName'])) {
      $temporaryPath .= $options['directoryName'].'/';
    }

    return $temporaryPath.$filename;
  }

  public function getTemporaryPath() {
    return $this->temporaryPath;
  }

  public function deleteTemporaryFile($directoryName,$filename) {

    if(empty($directoryName) || empty($filename)) {
      return false;
    }

    return File::Delete(storage_path($this->temporaryPath).$directoryName.'/'.$filename);
  }

  public function temporaryDirectoryExist($directoryName) {
    return is_dir($this->temporaryPath.$directoryName);
  }

  public function deleteTemporaryDirectory($directoryName) {

    // if(empty($directoryName)) {
    //   return false;
    // }
    if(!$this->temporaryDirectoryExist($directoryName)) {
      return false;
    }

    return File::deleteDirectory($this->temporaryPath.$directoryName);
  }

  public function checkExistSpecifiedTemporaryRecord($modelName,$token) {
    return $this->where([
      ['model','=',$modelName],
      ['token','=',$token],
      ['person_id','=',Session::get('Person.id')]
    ])->exists();
  }

  public function deleteSpecifiedTemporaryRecord($modelName,$token,$filename) {
    return $this->where([
      ['model','=',$modelName],
      ['token','=',$token],
      ['filename','=',$filename],
      ['person_id','=',Session::get('Person.id')]
    ])->delete();
  }

  public function deleteTemporaryRecords($modelName,$token) {
    return $this->where([
      ['model','=',$modelName],
      ['token','=',$token],
      ['person_id','=',Session::get('Person.id')]
    ])->delete();
  }

  public function setUpdatedAt($value) {}

}
