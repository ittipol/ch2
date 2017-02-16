<?php

namespace App\Models;

use App\library\token;

class Slug extends Model
{
  public $table = 'slugs';
  protected $fillable = ['model','model_id','slug'];
  public $timestamps  = false;

  // ** reserved word **
  // company
  // online-shop
  // job
  // ad
  // product

  public function __construct() {  
    parent::__construct();
  }

  public function shop() {
    return $this->hasOne('App\Models\Shop','id','model_id');
  }

  public function __saveRelatedData($model,$options = array()) {

    $behavior = $model->getBehavior('Slug');

    if(empty($behavior['field'])) {
      return false;
    }

    $save = true;

    $slug = $this->generateSlug($model,$behavior['field']);

    if(empty($slug)){
      $slug = $model->id.'-'.Token::generateNumber(10);
    }

    if($this->checkDataExistBySlug($model,$slug)) {
      $slug .= '-'.Token::generateNumber(8);
    }

    return $this->fill($model->includeModelAndModelId(array('slug' => $slug)))->save();

  }

  private function generateSlug($model,$field) {

    if(empty($field) || empty($model->{$field})) {
      return false;
    }

    $slug = str_replace(' ', '-', trim($model->{$field}));
    $slug .= '-'.$model->id;

    return $slug;

  }

  public function checkDataExistBySlug($model,$slug) {
    return $this->where([
      ['model','like',$model->modelName],
      ['model_id','like',$model->id],
      ['slug','like',$slug]
    ]
    )->exists();
  }

  public function setUpdatedAt($value) {}

}
