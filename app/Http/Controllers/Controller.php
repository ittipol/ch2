<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// use App\library\token;
// use App\library\service;
// use App\library\entity;
use App\library\stringHelper;
use Session;
use Route;
use Request;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  protected $slug;
  protected $data = array();

  protected $pageTitle = null;
  protected $metaDescription = null;
  protected $metaImage = null;
  protected $metaKeywords = null;
  protected $ogType = 'article';
  protected $ogProduct = null;
  // protected $placeLatLng = null;

  protected $botDisallowed = true;

  protected $param;
  protected $query;
  protected $entity;
  protected $form;
  protected $modelData;
  protected $paginator;
  protected $error;

  public function __construct() { 
    $this->query = request()->query();
    $this->param = Route::current()->parameters();
  }

  protected function setPageTitle($pageTitle = null,$include = true) {
    
    if(empty($pageTitle)) {
      return false;
    }

    if($include) {
      // $pageTitle = $pageTitle.' | Sunday Square';
      $pageTitle = $pageTitle;
    }

    $this->pageTitle = $pageTitle;

  }

  protected function setPageDescription($metaDescription = null) {

    if(empty($metaDescription)) {
      return false;
    }

    $string = new stringHelper;
    $this->metaDescription = $string->truncString($metaDescription,200);
  }

  protected function setMetaKeywords($metaKeywords = null) {
    if(empty($metaKeywords)) {
      return false;
    }

    $this->metaKeywords = $metaKeywords;
  }

  protected function setPageImage($metaImage = null) {
    if(empty($metaImage)) {
      return false;
    }

    $this->metaImage = $metaImage;
  }

  protected function setOgType($ogType = null) {
    if(empty($ogType)) {
      return false;
    }

    $this->ogType = $ogType;
  }

  protected function setOgProduct($ogProduct = null) {
    if(empty($ogProduct)) {
      return false;
    }

    $this->ogProduct = $ogProduct;
  }

  protected function botAllowed() {
    $this->botDisallowed = false;
  }

  protected function setData($index,$value) {
    $this->data[$index] = $value;
  }

  protected function mergeData($data = array()) {
    $this->data = $this->arrayMerge($this->data,$data);
  }

  protected function view($view = null) {

    // if(empty($view)) {
    //   $this->error = array(
    //     'message' => 'ขออภัย หน้านี้ไม่พร้อมใช้งาน'
    //   );
    //   return $this->error();
    // }

    $this->data['_og_type'] = $this->ogType;

    $this->data['_page_title'] = $this->pageTitle;
    $this->data['_page_description'] = $this->metaDescription;
    $this->data['_page_image'] = $this->metaImage;
    $this->data['_meta_keywords'] = $this->metaKeywords;

    // product detail
    $this->data['_og_product'] = $this->ogProduct;

    $this->data['_bot_disallowed'] = $this->botDisallowed;

    $this->data['_page_url'] = Request::fullUrl();
    // Request::fullUrl()
    // Request::url()

  	return view($view,$this->data);
  }

  protected function error() {
    $data = array();

    if(!empty($this->error)) {
      $data['error'] = $this->error;
    }

    return view('errors.error',$data);
  }

  // protected function arrayMerge(array & $array1, array & $array2)
  // {
  //     $merged = $array1;

  //     foreach ($array2 as $key => & $value)
  //     {
  //         if (is_array($value) && isset($merged[$key]) && is_array($merged[$key]))
  //         {
  //             $merged[$key] = $this->arrayMerge($merged[$key], $value);
  //         } else if (is_numeric($key))
  //         {
  //              if (!in_array($value, $merged))
  //                 $merged[] = $value;
  //         } else
  //             $merged[$key] = $value;
  //     }

  //     return $merged;
  // }

}
