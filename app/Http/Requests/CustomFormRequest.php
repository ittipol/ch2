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

  // Need Chenge
  // Get Page name from DB

  private $pages = array(
    'account.profile.edit' => array(
      'modelName' => 'Person'
    ),
    'item.post' => array(
      'modelName' => 'Item'
    ),
    'real_estate.post' => array(
      'modelName' => 'RealEstate'
    ),
    'shop.create' => array(
      'modelName' => 'Shop'
    ),
    'shop.job.add' => array(
      'modelName' => 'Job'
    ),
    'shop.product.add' => array(
      'modelName' => 'Product'
    ),
    'shop.product.edit' => array(
      'modelName' => 'Product'
    ),
    'shop.product_status.edit' => array(
      'modelName' => 'Product'
    ),
    'shop.product_specification.edit' => array(
      'modelName' => 'Product'
    ),
    'shop.product_category.edit' => array(
      'modelName' => 'Product'
    ),
    'shop.product_stock.edit' => array(
      'modelName' => 'Product'
    ),
    'shop.product_minimum.edit' => array(
      'modelName' => 'Product'
    ),
    'shop.product_price.edit' => array(
      'modelName' => 'Product'
    ),
    'shop.product_notification.edit' => array(
      'modelName' => 'Product'
    ),
    'shop.product_shipping.edit' => array(
      'modelName' => 'ProductShipping'
    ),
    'person_experience.internship.add' => array(
      'modelName' => 'PersonInternship'
    ),
    'person_experience.internship.edit' => array(
      'modelName' => 'PersonInternship'
    ),
    'shop.product_discount.add' => array(
      'modelName' => 'ProductDiscount'
    ),
    'shop.product_discount.edit' => array(
      'modelName' => 'ProductDiscount'
    )
  );

  public function __construct() {

    $name = Route::currentRouteName();

    $this->hasError = true;
    if(!empty($name) && !empty($this->pages[$name])) {
      $this->hasError = false;
      $model = service::loadModel($this->pages[$name]['modelName']);
      $this->validation = $model->getValidation();
    }

    // if(empty($name) || empty($this->pages[$name])) {
    //   $this->hasError = true;
    // }else{
    //   $model = service::loadModel($this->pages[$name]['modelName']);
    //   $this->validation = $model->getValidation();
    // }

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
    $data = request()->all();

    // $rules = $this->validation['rules'];

    // if(!empty($this->validation['action'][$this->method()])) {
    //   $rules = $this->validation['action'][$this->method()];
    // }

    // $model = service::loadModel($this->pages[$name]['modelName']);
    // $this->validation = $model->getValidation();

    $rules = array();
    if(!empty($this->validation['rules'])){
      foreach ($this->validation['rules'] as $key => $value) {

        if(!empty($this->validation['excepts'][$name]) && in_array($key, $this->validation['excepts'][$name])) {

          // if(!empty(Request::get($key))) {
          //   $this->hasError = true;
          // }

          // if($this->hasError) {
          //   break;
          // }

          continue;
        }

        if(!empty($this->validation['conditions'][$key]) && (!isset($data[$this->validation['conditions'][$key]['field']]) || !($data[$this->validation['conditions'][$key]['field']] == $this->validation['conditions'][$key]['value']))) {
          continue;
        }
        
        $rules[$key] = $value;

      }
    }
 
    return $rules;
  }
}
