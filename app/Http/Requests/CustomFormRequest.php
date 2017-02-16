<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\library\service;
use Request;
use Session;

class CustomFormRequest extends FormRequest
{
  private $model;
  private $validation;

  public function __construct() {
    $data = Request::all();
    $this->model = service::loadModel($data['_model']);
    $this->validation = $this->model->getValidation();
  }

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
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
    $data = Request::all();

    $rules = array();
    if(!empty($this->validation['rules'])){
      foreach ($this->validation['rules'] as $key => $value) {

        if(!empty($this->validation['except'][$key])) {

          $skip = false;
          foreach ($this->validation['except'][$key] as $_key => $_value) {
            if(!empty($data[$_key]) && ($data[$_key] == $_value)) {
              $skip = true;
            }
          }

          if($skip) {
            continue;
          }
        }
        
        $rules[$key] = $value;
      }
    }
    
    return $rules;
  }
}
