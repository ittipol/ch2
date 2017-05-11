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

    $category = $this->select('name')->where('id','=',$id);

    if(!$category->exists()) {
      return null;
    }

    return $category->first()->name;
  }

  public function getSubCategories($parentId = null,$build = true) {

    $url = new Url;

    $categories = $this->select('id','name')->where('parent_id','=',$parentId);

    if(!$categories->exists()) {
      return null;
    }

    if(!$build) {
      return $categories->get();
    }

    $_categories = array();
    foreach ($categories->get() as $category) {
      $_categories[] = array(
        'name' => $category->name,
        'url' => $url->setAndParseUrl('product/{id}',array('id' => $category->id)),
      );
    }

    return $_categories;

  }

  public function getCategoriesWithSubCategories($id = null) {

    $url = new Url;
    
    $category = $this->find($id);

    if(empty($category->parent_id)) {
      $categories = $this->getSubCategories($category->id,false);

      $_categories = array();
      foreach ($categories as $_category) {

        $_categories[] = array(
          'name' => $_category->name,
          'url' => $url->setAndParseUrl('product/{id}',array('id' => $_category->id)),
          'total' => $this->countProduct($_category->id),
          'subCategories' => array()
        );

      }

    }else{
      $categories = $this->getSubCategories($category->parent_id,false);

      $_categories = array();
      foreach ($categories as $_category) {

        $__subCategories = array();
        if($id == $_category->id) {
      
          $subCategories = $this->getSubCategories($_category->id,false);

          if(!empty($subCategories)) {
            foreach ($subCategories as $_subCategories) {

              $__subCategories[] = array(
                'name' => $_subCategories->name,
                'url' => $url->setAndParseUrl('product/{id}',array('id' => $_subCategories->id)),
                'total' => $this->countProduct($_subCategories->id)
              );
            }
          }
          
        }

        $_categories[] = array(
          'name' => $_category->name,
          'url' => $url->setAndParseUrl('product/{id}',array('id' => $_category->id)),
          'total' => $this->countProduct($_category->id),
          'subCategories' => $__subCategories
        );

      }

    }

    return $_categories;

  }

  public function getCategoryPaths($id) {

    $url = new Url;

    $paths = CategoryPath::where('category_id','=',$id)->get();

    foreach ($paths as $path) {

      $subCat = $path->path->where('parent_id','=',$path->path->id)->first();

      $hasChild = false;
      if(!empty($subCat)) {
        $hasChild = true;
      }

      $categoryPaths[] = array(
        // 'id' => $path->path->id,
        'name' => $path->path->name,
        'url' => $url->setAndParseUrl('product/{category_id}',array('category_id'=>$path->path->id)),
        'hasChild' => $hasChild
      );
    }

    return $categoryPaths;

  }

  public function getParentCategoryId($id) {

    $category = $this->select('parent_id')->where('id','=',$id);

    if(!$category->exists()) {
      return null;
    }

    return $category->first()->parent_id;

  }

  public function getParentCategory($id) {

    $category = $this->select('parent_id')->where('id','=',$id);

    if(!$category->exists()) {
      return null;
    }

    return $this->select('id','parent_id','name')->where('id','=',$category->first()->parent_id)->first();

  }  

  public function countProduct($id) {

    $categoryPaths = CategoryPath::select('category_id')->where('path_id','=',$id)->get();

    $ids = array();
    foreach ($categoryPaths as $categoryPath) {
      $ids[] = $categoryPath->category_id;
    }

    return Product::join('product_to_categories', 'product_to_categories.product_id', '=', 'products.id')
    ->whereIn('product_to_categories.category_id',$ids)
    ->count();

  }

  public function hadCatagory($categoryId = null) {

    if(empty($categoryId)) {
      return false;
    }

    return $this->where('id','=',$categoryId)->exists();
  }

}
