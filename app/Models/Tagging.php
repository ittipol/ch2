<?php

namespace App\Models;

class Tagging extends Model
{
  public $table = 'taggings';
  protected $fillable = ['model','model_id','word_id'];

  public function __construct() {  
    parent::__construct();
  }

  public function word() {
    return $this->hasOne('App\Models\Word','id','word_id');
  }

  public function __saveRelatedData($model,$options = array()) {

    $word = new Word;
    $wordIds = $word->saveSpecial($options['value']);

    $currentTaggings = $model->getRelatedData($this->modelName,array(
      'fields' => array('id','word_id')
    ));

    $currentId = array();
    $deletingId = array();
    if(!empty($currentTaggings)){
      foreach ($currentTaggings as $tagging) {

        if(in_array($tagging->word_id, $wordIds)) {
          $currentId[] = $tagging->word_id;
          continue;
        }

        $deletingId[] = $tagging->id;
        // $this->find($tagging->id)->delete();

      }
    }

    if(!empty($deletingId)) {
      $this->whereIn('id',$deletingId)->delete();
    }

    foreach ($wordIds as $wordId) {
      if(!in_array($wordId, $currentId)) {
        $this->newInstance()->fill($model->includeModelAndModelId(array('word_id' => $wordId)))->save();
      }
    }

    return true;
    
  }

  public function buildModelData() {
    
    return array(
      '_word_id' => $this->word->id,
      '_word' => $this->word->word
    );

  }

  public function buildFormData() {
    
    return array(
      // '_word_id' => $this->word->id,
      '_word' => $this->word->word
    );

  }

  public function setUpdatedAt($value) {}

}
