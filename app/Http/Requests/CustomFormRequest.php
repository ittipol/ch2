<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\library\service;
use Request;
use Route;
// use Session;

class CustomFormRequest extends FormRequest
{
  private $model;
  private $validation;
  private $hasError = false;

  private $pages = array(
    'shop.product.add' => array(
      'modelName' => 'Product'
    ),
    'shop.product.edit' => array(
      'modelName' => 'Product'
    ),
    'shop.product_specification.edit' => array(
      'modelName' => 'Product'
    ),
    'shop.product_category.edit' => array(
      'modelName' => 'Product'
    ),
    'person_experience.internship.add' => array(
      'modelName' => 'PersonInternship'
    ),
    'person_experience.internship.edit' => array(
      'modelName' => 'PersonInternship'
    )
  );

  public function __construct() {

    $name = Route::currentRouteName();

    if(empty($name) || empty($this->pages[$name])) {
      $this->hasError = true;
    }

    // $model = service::loadModel($this->pages[$name]['modelName']);
    // $this->validation = $model->getValidation();

    // $this->model = service::loadModel(Request::get('_model'));
    // $this->validation = $this->model->getValidation();
  }

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return !$this->hasError;
    // return Auth::check();
  }

  public function messages()
  {
    if(!empty($this->validation['messages'])) {
      return $this->validation['messages'];
    }

    return [];
  }

  public function rules()
  {
    if($this->hasError) {
      return array();
    }

    $name = Route::currentRouteName();

    $model = service::loadModel($this->pages[$name]['modelName']);
    $this->validation = $model->getValidation();

    $rules = array();
    if(!empty($this->validation['rules'])){
      foreach ($this->validation['rules'] as $key => $value) {

        if(!empty($this->validation['excepts'][$name]) && in_array($key, $this->validation['excepts'][$name])) {

          if(!empty(Request::get($key))) {
            $this->hasError = true;
          }

          if($this->hasError) {
            break;
          }

          continue;
        }
        
        $rules[$key] = $value;

      }
    }
 
    return $rules;
  }
}
