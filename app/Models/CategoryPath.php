<?php

namespace App\Models;

class CategoryPath extends Model
{
  protected $table = 'category_paths';
  protected $fillable = ['category_id','path_id','level'];
  public $timestamps  = false;

  public function category() {
    return $this->hasOne('App\Models\Category','id','category_id');
  }

  public function path() {
    return $this->hasOne('App\Models\Category','id','path_id');
  }

  public function getPathIdByCategoryIdAndLevel($categoryId,$level = null) {

    if(empty($level)) {
      return null;
    }

    // Get Category level
    $categoryLevel = CategoryPath::where('path_id','=',$categoryId)->select('level')->first()->level;

    if($level > $categoryLevel) {
      $level = $categoryLevel;
    }

    $categoryPath = CategoryPath::where([
      ['category_id','=',$categoryId],
      ['level','=',$level],
    ]);

    if($categoryPath->exists()) {
      return $categoryPath->select('path_id')->first()->path_id;
    }

    return null;

  }

  public function getPathsByCategoryIdAndLevel($categoryId,$level) {

    $pathId = $this->getPathIdByCategoryIdAndLevel($categoryId,$level);

    if(empty($pathId)) {
      return null;
    }

    $categoryPaths = CategoryPath::select('category_id')->where('path_id','=',$pathId);

    $pathIds = array();
    if($categoryPaths->exists()) {
      foreach ($categoryPaths->get() as $path) {
        $pathIds[] = $path->category_id;
      }
    }

    return $pathIds;

  }

}
