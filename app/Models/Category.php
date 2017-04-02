<?php

namespace App\Models;

use App\library\url;

class Category extends Model
{
  protected $table = 'categories';
  protected $fillable = ['parent_id','name'];
  public $timestamps  = false;

  public function getCategoryName($id) {

    if(empty($id)) {
      return null;
    }

    return $this->find($id)->name;
  }

  public function getPrimaryCategories() {
    return $this->select('id','name')->where('parent_id','=',null)->get();
  }

  public function getCategories($parentId = null) {

    $url = new Url;

    $categories = $this->where('parent_id','=',$parentId)->get();

    $_categories = array();
    foreach ($categories as $category) {
      $_categories[] = array(
        'name' => $category->name,
        'url' => $url->setAndParseUrl('product/category:{id}',array('id' => $category->id)),
      );
    }

    return $_categories;

  }

}
