<?php

namespace App\Models;

class ItemCategory extends Model
{
  protected $table = 'item_categories';

  public function getCategoryName($id) {

    if(empty($id)) {
      return null;
    }

    $category = $this->select('name')->where('id','=',$id);

    if(!$category->exists()) {
      return null;
    }

    return $category->first()->name;
  }
  
}
