<?php

namespace App\Models;

class Word extends Model
{
  public $table = 'words';
  protected $fillable = ['word','description'];

  public function __construct() {  
    parent::__construct();
  }

  public function saveSpecial($value) {
    $tagIds = array();
    foreach ($value as $word) {
      $this->checkAndSave($word);
      $tagIds[] = $this->getDataByWord($word)->id;
    }
    return $tagIds;
  }

  public function checkAndSave($word) {
    if(!$this->checkRecordExistByTagName($word)) {
      return $this->newInstance()->fill(array('word' => $word))->save();
    }

    return true;
  }

  public function checkRecordExistByTagName($word) {
    return $this->where('word','like',$word)->exists();
  }

  public function getDataByWord($word) {
    return $this->where('word','like',$word)->first();
  }

  public function setUpdatedAt($value) {}

}
