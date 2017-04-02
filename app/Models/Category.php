<?php

namespace App\Models;

use App\library\url;

class Category extends Model
{
  protected $table = 'categories';
  protected $fillable = ['parent_id','name'];
  public $timestamps  = false;

  // public function getCategoryName($id) {

  //   if(empty($id)) {
  //     return null;
  //   }

  //   return $this->find($id)->name;
  // }

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

  public function getCategories($parentId = null,$build = true) {

    $url = new Url;

    $categories = $this->where('parent_id','=',$parentId);

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
        'url' => $url->setAndParseUrl('product/shelf/{id}',array('id' => $category->id)),
      );
    }

    return $_categories;

  }

  public function _getCategories($id = null) {

    $url = new Url;
 
    $category = $this->find($id);

    if(empty($category->parent_id)) {
      $categories = $this->getCategories($category->id,false);

      $_categories = array();
      foreach ($categories as $_category) {

        $_categories[] = array(
          'name' => $_category->name,
          'url' => $url->setAndParseUrl('product/shelf/{id}',array('id' => $_category->id)),
          'subCategories' => array()
        );

      }

    }else{
      $categories = $this->getCategories($category->parent_id,false);

      $_categories = array();
      foreach ($categories as $_category) {

        $__subCategories = array();
        if($id == $_category->id) {
      
          $subCategories = $this->getCategories($_category->id,false);

          if(!empty($subCategories)) {
            foreach ($subCategories as $_subCategories) {
              $__subCategories[] = array(
                'name' => $_subCategories->name,
                'url' => $url->setAndParseUrl('product/shelf/{id}',array('id' => $_subCategories->id)),
              );
            }
          }
          
        }

        $_categories[] = array(
          'name' => $_category->name,
          'url' => $url->setAndParseUrl('product/shelf/{id}',array('id' => $_category->id)),
          'subCategories' => $__subCategories
        );

      }

    }

    return $_categories;

  }

}
