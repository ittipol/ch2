<?php

namespace App\Models;

use App\library\service;
use App\library\string;
use App\library\url;
use App\library\cache;
use App\library\imageTool;
use App\library\handleImageFile;
use File;
use Session;

class Image extends Model
{
  protected $table = 'images';
  protected $fillable = ['model','model_id','path','filename','description','image_type_id','person_id'];
  private $maxFileSizes = 2097152;
  private $acceptedFileTypes = ['image/jpg','image/jpeg','image/png', 'image/pjpeg'];

  private $imagePatterns = array(
    'profile-image' => array(
      'path' => 'profile',
      'rename' => 'profile-image'
    ),
    'cover' => array(
      'path' => 'profile',
      'rename' => 'cover'
    ),
    'image' => array(
      'path' => 'images'
    )
  );

  protected $storagePath = 'app/public/images/';

  // photos/?tab=album&album_id=270003133398148
  // photos/?tab=album&album_id=1299989693406181

  public function __construct() {  
    parent::__construct();
  }

  public static function boot() {

    parent::boot();

    Image::deleted(function($model) {
      // delete image file after image record is deleted
      $model->deleteImage();
    });

  }

  public function imageType() {
    return $this->hasOne('App\Models\ImageType','id','image_type_id');
  }

  public function __saveRelatedData($model,$options = array()) {
    $this->handleImages($model,$options['value'],$options);
  }

  private function handleImages($model,$images,$options = array()) {

    $imageType = new ImageType;

    foreach ($images as $type => $value) {

      if(!$imageType->checkExistByAlias($type)) {
        continue;
      }

      if(!empty($value['delete'])) {
        $this->deleteImages($model,$value['delete']);
      }

      if(!empty($value['images'])) {
        $this->addImages($model,$value['images'],array(
          'type' => $type,
          'token' => $value['token']
        ));
      }

    }

  }

  private function addImages($model,$images,$options = array()) {

    if(empty($model->imageTypes[$options['type']]) || ($model->imageTypes[$options['type']]['limit'] == 0)) {
      return false;
    }

    $temporaryFile = new TemporaryFile;
    $imageType = new ImageType;

    $count[$options['type']] = 0;

    $imageType = $imageType->where('alias','like',$options['type'])->select('path')->first();

    foreach ($images as $image) {

      if($model->imageTypes[$options['type']]['limit'] < ++$count[$options['type']]) {
        break;
      }

      $this->handleImage($model,$image,$options);

    }

    // remove temp dir
    $temporaryFile->deleteTemporaryDirectory($model->modelName.'_'.$options['token'].'_'.$options['type']);
    // remove temp file record
    $temporaryFile->deleteTemporaryRecords($model->modelName,$options['token']);

  }

  public function deleteImage() {

    $cache = new Cache;

    $path = $this->getImagePath();

    if(!file_exists($path)){
      return false;
    }

    if(File::Delete($path)) {
      $cache->deleteCacheDirectory(pathinfo($this->filename, PATHINFO_FILENAME));
    }

    return true;

  }

  public function deleteImages($model,$imageIds) {

    $images = $this->newInstance()
    ->whereIn('id',$imageIds)
    ->where([
      ['model','=',$model->modelName],
      ['model_id','=',$model->id],
      ['person_id','=',Session::get('Person.id')]
    ])
    ->get();

    if(empty($images)) {
      return false;
    }

    foreach ($images as $image) {
      $image->delete();
    }

    return true;

  }

  public function deleteDirectory($model) {
    return File::deleteDirectory(storage_path($this->storagePath.$model->modelAlias).'/'.$model->id.'/');
  }

  public function addImage($model,$image,$options = array()) {

    $temporaryFile = new TemporaryFile;

    $imageId = $this->handleImage($model,$image,$options);

    $temporaryFile->deleteTemporaryDirectory($model->modelName.'_'.$options['token'].'_profile-image');
    $temporaryFile->deleteTemporaryRecords($model->modelName,$options['token']);

    return $imageId;

  }

  public function handleImage($model,$image,$options = array()) {

    $temporaryFile = new TemporaryFile;
    $imageType = new ImageType;
    $cache = new Cache;

    $imageInstance = $this->newInstance();

    if(!empty($image['id'])) {
      $imageInstance = $imageInstance->where([
        ['id','=',$image['id']],
        ['person_id','=',Session::get('Person.id')]
      ])->first();

      if(empty($imageInstance)) {
        return false;
      }

    }

    $path = '';
    if(!empty($image['filename'])) {

      $path = $temporaryFile->getFilePath($image['filename'],array(
        'directoryName' => $model->modelName.'_'.$options['token'].'_'.$options['type']
      ));

      if(!file_exists($path)) {
        return false;
      }
    }

    if(!$imageInstance->exists) { // new record
      $imageInstance->model = $model->modelName;
      $imageInstance->model_id = $model->id;
      $imageInstance->filename = $image['filename'];
      $imageInstance->image_type_id = $imageType->getIdByalias($options['type']);
    }

    if(!empty($image['description'])) {
      $imageInstance->description = $image['description'];
    }

    if(!$imageInstance->save()) {
      return false;
    }

    $toPath = $imageInstance->getFullDirPath();
    if(!is_dir($toPath)){
      mkdir($toPath,0777,true);
    }

    if(!empty($path) && $this->moveImage($path,$imageInstance->getImagePath())) {
      $cache->deleteCacheDirectory(pathinfo($imageInstance->filename, PATHINFO_FILENAME));
    }

    return $imageInstance->id;

  }

  public function moveImage($oldPath,$to) {
    return File::move($oldPath, $to);
  }

  public function getDirPath() {

    $string = new String;

    return storage_path($this->storagePath.$string->generateUnderscoreName($this->model)).'/'.$this->model_id.'/';
  }

  public function getFullDirPath() {
    
    $string = new String;

    $path = $this->getDirPath();

    if(!empty($this->imageType->path)) {
      $path .= $this->imageType->path.'/';
    }

    return $path;
    // return storage_path($this->storagePath.$string->generateUnderscoreName($this->model)).'/'.$this->model_id.'/'.$this->imageType->path.'/';
  }

  public function getImagePath($filename = '') {

    if(empty($filename)) {
      $filename = $this->filename;
    }

    // if(!empty($this->imageType->path)) {
    //   $url = new Url;
    //   $filename = $url->addSlash($this->imageType->path).$filename;
    // }

    // return $this->getDirPath().$filename;
    return $this->getFullDirPath().$filename;
  }

  public function getImageUrl($filename = '') {

    if(empty($filename)) {
      $filename = $this->filename;
    }

    $path = '';
    if(file_exists($this->getImagePath())){
      $path = '/safe_image/'.$filename;
    }

    return $path;
  }

  public function getFirstImage($model,$style) {

    $imageStyle = new ImageStyle;

    $image = $model->getRelatedData('Image',array(
      'conditions' => array(
        array('image_style_id','=',$imageStyle->getIdByalias($style))
      ),
      'first' => true
    ));

    $_image = array();
    if(!empty($image)) {
      $_image = $image->buildModelData();
    }

    return $_image;

  }

  public function base64Encode() {

    $dirPath = 'image/'.strtolower($this->model).'/';

    $path = '';
    if(File::exists($this->getImagePath())){
      $path = '/safe_image/'.$this->name;
    }

    return base64_encode(File::get($path));
  }

  public function checkMaxSize($size) {
    if($size <= $this->maxFileSizes) {
      return true;
    }
    return false;
  }

  public function checkType($type) {
    if (in_array($type, $this->acceptedFileTypes)) {
      return true;
    }
    return false;
  }

  public function getMaxFileSizes() {
    return $this->maxFileSizes;
  }

  public function getAcceptedFileTypes() {
    return $this->acceptedFileTypes;
  }

  public function buildModelData() {

    return array(
      'filename' => $this->filename,
      'description' => $this->description ? $this->description : '-',
      '_url' => $this->getImageUrl()
    );

  }

  public function buildFormData() {

    return array(
      'id' => $this->id,
      'description' => $this->description ? $this->description : '',
      '_url' => $this->getImageUrl()
    );

  }

  public function saveImage($model,$image,$options = array()) {

    $cache = new cache;
    $imageType = new ImageType;
    $image = new HandleImageFile($image);

    if(!$this->exists) {
      $this->model = $model->modelName;
      $this->model_id = $model->id;
      $this->filename = $filename = $image->getFileName();
      $this->image_type_id = $imageType->getIdByalias($options['type']);
    }

    if(!empty($options['description'])) {
      $this->description = $options['description'];
    }

    if(!$this->save()) {
      return false;
    }

    list($width,$height) = $image->generateImageSize($options['type']);

    $toPath = $this->getFullDirPath();
    if(!is_dir($toPath)){
      mkdir($toPath,0777,true);
    }

    $imageTool = new ImageTool($image->getRealPath());
    $imageTool->png2jpg($width,$height);
    $imageTool->resize($width,$height);
    $moved = $imageTool->save($this->getImagePath());

    if($moved) {
      $cache->deleteCacheDirectory(pathinfo($this->filename, PATHINFO_FILENAME));
    }

    return $this->id;

  }

}
