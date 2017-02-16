<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\library\token;
use App\library\service;
use App\library\entity;
use Session;
use Route;
// use Request;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  protected $slug;
  protected $data = array();
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

  protected function error() {
    $data = array();

    if(!empty($this->error)) {
      $data['error'] = $this->error;
    }

    return view('errors.error',$data);
  }

  protected function setData($index,$value) {
    $this->data[$index] = $value;
  }

  protected function mergeData($data = array()) {
    $this->data = $this->arrayMerge($this->data,$data);
  }

  protected function view($view = null) {

    if(empty($view)) {
      $this->error = array(
        'message' => 'ขออภัย หน้านี้ไม่พร้อมใช้งาน'
      );
      return $this->error();
    }

  	return view($view,$this->data);
  }

  protected function arrayMerge(array & $array1, array & $array2)
  {
      $merged = $array1;

      foreach ($array2 as $key => & $value)
      {
          if (is_array($value) && isset($merged[$key]) && is_array($merged[$key]))
          {
              $merged[$key] = $this->arrayMerge($merged[$key], $value);
          } else if (is_numeric($key))
          {
               if (!in_array($value, $merged))
                  $merged[] = $value;
          } else
              $merged[$key] = $value;
      }

      return $merged;
  }

}
