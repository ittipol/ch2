<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use App\library\service;
use App\library\currency;
use App\library\stringHelper;
use App\library\cache;
use App\library\formHelper;
use App\library\modelData;
use App\library\paginator;
use Session;
use Schema;
use Route;

class Model extends BaseModel
{
  public $modelName;
  public $modelAlias;
  protected $storagePath = 'app/public/';
  protected $state = 'create';
  protected $modelRelations = array();
  protected $modelRelationData = array();
  protected $modelRelationDataOption = array();
  protected $filterOptions = array();
  protected $sortingFields = array();
  protected $behavior;
  protected $validation;
  // protected $directory = false;
  protected $directoryPath;
  protected $imageCache = array();

  public $formHelper = false;
  public $modelData = false;
  public $paginator = false;
  
  public function __construct(array $attributes = []) {

    $string = new stringHelper;
    
    $this->modelName = class_basename(get_class($this));
    $this->modelAlias = $string->generateUnderscoreName($this->modelName);
    $this->directoryPath = $this->storagePath.$this->modelAlias.'/';

    if($this->formHelper) {
      $this->formHelper = new FormHelper($this);
    }
    
    if($this->modelData) {
      $this->modelData = new ModelData($this);
    }
    
    if($this->paginator) {
      $this->paginator = new Paginator($this);
    }

    parent::__construct($attributes);
    
  }

  public static function boot() {

    parent::boot();

    // before saving
    parent::saving(function($model){

      if(!$model->exists){

        $model->state = 'create';

        // if((Schema::hasColumn($model->getTable(), 'ip_address')) && (empty($model->ip_address))) {
        //   $model->ip_address = Service::ipAddress();
        // }

        if(Schema::hasColumn($model->getTable(), 'created_by') && empty($model->created_by)) {
          $model->created_by = Session::get('Person.id');
        }

      }else{
        $model->state = 'update';
      }

    });

    // after saving
    parent::saved(function($model){

      if(($model->state == 'create') && $model->exists) {

        if(!empty($model->behavior['Slug'])) {
          $slug = new Slug;
          $slug->__saveRelatedData($model);
        }

        // if(!empty(request()->get('shop'))) {
        //   $shopRelateTo = new ShopRelateTo;
        //   dd(request()->get('shop'));
        // }

      }

      $model->modelRelationsSave();

      if(!empty($model->behavior['Lookup'])) {
        $lookup = new Lookup;
        $lookup->__saveRelatedData($model);
      }

      if(!empty($model->behavior['DataAccessPermission'])) {
        $dataAccessPermission = new DataAccessPermission;
        $dataAccessPermission->__saveRelatedData($model,$model->getModelRelationData('DataAccessPermission'));
      }
      
    });

    // before delete() method call this
    // static::deleting(function($user) {
    //    // do the rest of the cleanup...
    // });

    // before delete() method call this
    parent::deleted(function($model) {
       // delete all related data
       $model->deleteAllRelatedData();
    });

  }

  protected function modelRelationsSave() {

    if(!empty($this->modelRelationData)) {

      foreach ($this->modelRelationData as $modelName => $value) {

        if(empty($value)) {
          continue;
        }

        $data = array(
          'value' => $value,
          'options' => $this->getModelRelationDataOption($modelName)
        );

        $model = Service::loadModel($modelName);

        if(!method_exists($model,'__saveRelatedData')) {
          continue;
        }

        $model->__saveRelatedData($this,$data);

        unset($this->modelRelationData[$modelName]);

      }

    }
    
  }

  public function fill(array $attributes) {

    if(!empty($attributes) && !empty($this->modelRelations)){
      foreach ($this->modelRelations as $key => $modelName) {

        if(empty($attributes[$modelName])) {
          continue;
        }

        $this->modelRelationData[$modelName] = $attributes[$modelName];
        unset($attributes[$modelName]);
      }
    }

    if(!empty($this->behavior['DataAccessPermission']) && !empty($attributes['DataAccessPermission'])) {
      $this->modelRelationData['DataAccessPermission']['value'] = $attributes['DataAccessPermission'];
      unset($attributes['DataAccessPermission']);
    }

    if(!empty($attributes)) {
      foreach ($this->fillable as $field) {

        if(empty($attributes[$field]) || is_array($attributes[$field])) {
          continue;
        }

        $attributes[$field] = trim($attributes[$field]);
      }
    }
    
    return parent::fill($attributes);
  }

  public function getDirectoryPath() {

    if(empty($this->directoryPath)) {
      return false;
    }

    return storage_path($this->directoryPath);
  }

  public function checkExistById($id) {
    return $this->find($id)->exists();
  }

  public function checkExistByAlias($alias) {

    if(!Schema::hasColumn($this->getTable(), 'alias')){
      return false;
    }

    return $this->where('alias','like',$alias)->exists();
  }

  public function getIdByalias($alias) {

    if(!Schema::hasColumn($this->getTable(), 'alias')){
      return false;
    }

    $record = $this->getData(array(
      'conditions' => array(
        ['alias','like',$alias]
      ),
      'fields' => array('id'),
      'first' => true
    ));

    if(empty($record)) {
      return null;
    }

    return $record->id;
  }

  public function getByAlias($alias) {

    if(!Schema::hasColumn($this->getTable(), 'alias')){
      return false;
    }

    return $this->getData(array(
      'conditions' => array(
        ['alias','like',$alias]
      ),
      'first' => true
    ));

  }

  public function getBy($value,$field,$first=true) {

    if(!Schema::hasColumn($this->getTable(), $field)){
      return false;
    }

    return $this->getData(array(
      'conditions' => array(
        [$field,'=',$value]
      ),
      'first' => $first
    ));

  }

  public function getData($options = array()) {

    $model = $this->newInstance();

    if(!empty($options['joins'])) {

      if(is_array(current($options['joins']))) {

        foreach ($options['joins'] as $value) {
          $model = $model->join($value[0], $value[1], $value[2], $value[3]);
        }

      }else{
        $model = $model->join(
          current($options['joins']), 
          next($options['joins']), 
          next($options['joins']), 
          next($options['joins'])
        );
      }

    }

    if(!empty($options['conditions']['in'])) {

      foreach ($options['conditions']['in'] as $condition) {
        $model = $model->whereIn(current($condition),next($condition));
      }

      unset($options['conditions']['in']);

    }

    if(!empty($options['conditions']['or'])) {

      $conditions = $criteria['conditions']['or'];

      $model = $model->where(function($query) use($conditions) {

        foreach ($conditions as $condition) {
          $query->orWhere(
            $condition[0],
            $condition[1],
            $condition[2]
          );
        }

      });

      unset($options['conditions']['or']);

    }

    if(!empty($options['conditions'])){
      $model = $model->where($options['conditions']);
    }

    if(!$model->exists()) {
      return null;
    }

    if(!empty($options['fields'])){
      $model = $model->select($options['fields']);
    }

    if(!empty($options['order'])){

      if(is_array(current($options['order']))) {

        foreach ($options['order'] as $value) {
          $model = $model->orderBy($value[0],$value[1]);
        }

      }else{
        $model = $model->orderBy(current($options['order']),next($options['order']));
      }
      
    }

    if(!empty($options['list'])) {
      return Service::getList($model->get(),$options['list']);
    }

    // if(isset($options['first'])) {
    //   if($options['first']) {
    //     return $model->first();
    //   }
    //   return $model->get();
    // }elseif($model->count() == 1) {
    //   return $model->first();
    // }


    if(empty($options['first'])) {
      return $model->get();
    }

    return $model->first();

  }

  public function getRelatedData($modelName,$options = array()) {

    $model = Service::loadModel($modelName);
    $field = $this->modelAlias.'_id';

    if(Schema::hasColumn($model->getTable(), $field)) {
      $conditions = array(
        [$field,'=',$this->id],
      );
    }elseif($model->checkHasFieldModelAndModelId()) {
      $conditions = array(
        ['model','like',$this->modelName],
        ['model_id','=',$this->id],
      );
    }else{
      return false;
    }

    if(!empty($options['conditions'])){
      $options['conditions'] = array_merge($options['conditions'],$conditions);
    }else{
      $options['conditions'] = $conditions;
    }

    return $model->getData($options);

  }

  public function deleteRelatedData($model) {

    $field = $model->modelAlias.'_id';

    if(Schema::hasColumn($this->getTable(), $field)) {

      $_model = $this->where($field,'=',$model->id);

    }elseif($this->checkHasFieldModelAndModelId()) {

      $_model = $this->where([
        ['model','=',$model->modelName],
        ['model_id','=',$model->id],
      ]);

    }else {
      return false;
    }

    if(!$_model->exists()) {
      return false;
    }

    if(Schema::hasColumn($this->getTable(), 'id')) {

      foreach ($_model->get() as $value) {
        $value->delete();
      }

      if($this->modelName == 'Image') {
        $this->deleteDirectory($model);
      }

    }else{

      if($this->checkHasFieldModelAndModelId()) {
        foreach ($_model->get() as $value) {

          $__model = Service::loadModel($value->model)->select('id')->find($value->model_id);

          if(!empty($__model)) {
            $__model->delete();
          }

        }
      }

      $_model->delete();
    }

    return true;

  }

  public function deleteAllRelatedData($options = array()) {

    $modelRelations = array_merge($this->getModelRelations(),array(
      'Slug',
      'Lookup',
      'DataAccessPermission'
    ));

    foreach ($modelRelations as $modelName) {
      $model = Service::loadModel($modelName);
      $model->deleteRelatedData($this);
    }

    return true;

  }

  public function checkHasFieldModelAndModelId() {
    if(Schema::hasColumn($this->getTable(), 'model') && Schema::hasColumn($this->getTable(), 'model_id')) {
      return true;
    }
    return false;
  }

  public function includeModelAndModelId($value) {

    if(empty($this->modelName) || empty($this->id)){
      return false;
    }

    if(!is_array($value)) {
      $value = array($value);
    }

    return array_merge($value,array(
      'model' => $this->modelName,
      'model_id' => $this->id
    ));

  }

  public function getCacheImageUrl($size = null) {

    if(empty($size)) {
      $size = 'list';
    }

    $cache = new Cache;

    $image = $this->getRelatedData('Image',array(
      'first' => true
    ));

    $imageUrl = null;
    if(!empty($image)) {
      $imageUrl = $cache->getCacheImageUrl($image,$size);
    }

    return $imageUrl;

  }

  public function getModelRelations() {
    return $this->modelRelations;
  }

  public function getModelRelationData($modelName) {

    if(empty($this->modelRelationData[$modelName])) {
      return null;
    }

    return $this->modelRelationData[$modelName];

  }

  public function getModelRelationDataOption($modelName) {

    if(empty($this->modelRelationDataOption[$modelName])) {
      return null;
    }

    return $this->modelRelationDataOption[$modelName];

  }

  public function getBehavior($modelName) {

    if(empty($this->behavior[$modelName])) {
      return null;
    }

    return $this->behavior[$modelName];

  }

  public function getValidation() {
    return $this->validation;
  }

  public function getFillable() {
    return $this->fillable;
  }

  public function getImageCache() {
      return $this->imageCache;
  }

  public function getFilterOptions() {
      return $this->filterOptions;
  }

  public function getSortingFields() {
      return $this->sortingFields;
  }

  public function getRecordForParseUrl() {
      return $this->getAttributes();
  }

  public function buildModelData() {
    return $this->getAttributes();
  }

  public function buildPaginationData() {
    return $this->getAttributes();
  }

  public function buildFormData() {
    return $this->getAttributes();
  }

  public function buildLookupData() {

    $string = new stringHelper;

    return array(
      'name' => $this->name,
      '_short_name' => $string->truncString($this->name,45),
      'description' => !empty($this->description) ? $this->description : '-',
      '_short_description' => $string->truncString($this->description,200),
      '_imageUrl' => null
    );

  }

  // public function createOrUpdate($value) {
  //   if($this->exists){
  //     return $this
  //     ->fill($options['value'])
  //     ->save();
  //   }else{
  //     return $this->fill($value)->save();
  //   }
  // }

  public function getImage($size = null) {

    $cache = new Cache;

    $image = $this->getRelatedData('Image',array(
      'fields' => array('model','model_id','filename','image_type_id'),
      'first' => true
    ));

    if(empty($image)) {
      return null;
    }

    if(empty($size)) {
      return $image->getImageUrl();
    }

    return $cache->getCacheImageUrl($image,$size);

  }

  public function getShortDescription($description = null) {

    if(empty($description) && !empty($this->description)) {
      $description = $this->description;
    }elseif(empty($description)) {
      return null;
    }

    $string = new stringHelper;
    return $string->truncString($description,200,true,true);
  }

}
