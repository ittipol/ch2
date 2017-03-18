<?php

namespace App\library;

use Input;

class FormHelper {
  
  private $model;
  private $data = array();
  private $formData = array();

  public function __construct($model = null) {
    $this->model = $model;
  }
  
  public function loadData($options = array()) {

    if(empty($this->model)) {
      return false;
    }

    if(empty($options['json'])) {
      $options['json'] = array();
    }

    if(empty($options['models'])) {
      $options['models'] = array();
    }

    $modeldNames = $this->model->getModelRelations();

    foreach ($modeldNames as $key => $modelName) {

      if($this->model->modelName == $modelName) {
        continue;
      }

      if(!empty($options['models']) && !in_array($modelName, $options['models'])) {
        continue;
      }

      $json = in_array($modelName, $options['json']);

      $this->_getRelatedData($modelName,$json);

    }

  }

  private function _getRelatedData($modelName,$json = false) {

    $data = array();
    switch ($modelName) {
      case 'Address':
        $data = $this->loadAddress();
        break;

      case 'Image':
        $data = $this->loadImage();
        break;

      case 'Tagging':
        $data = $this->loadTagging();
        break;

      case 'Contact':
        $data = $this->loadContact();
        break;
    }

    if($json) {
      $data = json_encode($data);
    }

    $this->formData[$modelName] = $data; 

  }

  public function loadAddress() {

    $address = $this->model->getRelatedData('Address',
      array(
        'first' => true,
        'fields' => array('address','province_id','district_id','sub_district_id','description','latitude','longitude'),
        'order' => array('id','DESC')
      )
    );

    if(empty($address)){
      return array();
    }

    return $address->buildFormData();

  }

  public function loadImage() {

    $images = $this->model->getRelatedData('Image',array(
      'fields' => array('id','model','model_id','filename','description','image_type_id')
    ));

    if(empty($images)){
      return array();
    }

    $_images = array();
    foreach ($images as $image) {
      $_images[] = $image->buildFormData();
    }

    return $_images;

  }

  public function loadTagging() {
    $taggings = $this->model->getRelatedData('Tagging',
      array(
        'fields' => array('word_id')
      )
    );

    if(empty($taggings)) {
      return array();
    }

    $words = array();
    foreach ($taggings as $tagging) {
      $words[] = $tagging->buildFormData();
    }

    return $words;

  }

  public function loadContact() {
    $contact = $this->model->getRelatedData('Contact',array(
      'first' => true,
      'fields' => array('phone_number','email','line')
    ));

    if(empty($contact)) {
      return array();
    }

    return $contact->getAttributes();

  }

  public function loadFieldData($modelName,$options = array()) {
    $model = Service::loadModel($modelName);

    if(!empty($options['conditions'])){
      $model = $model->where($options['conditions']);
    }

    if(!empty($options['order'])){
      foreach ($options['order'] as $order) {
        $model = $model->orderBy($order[0],$order[1]);
      }
    }

    $records = $model->get();

    $data = array();
    foreach ($records as $record) {
      $data[$record->{$options['key']}] = $record->{$options['field']};
    }

    if(!empty($options['json'])) {
      $data = json_encode($data);
    }

    $this->data[$options['index']] = $data;
  }

  public function setData($index,$value) {
    $this->data[$index] = $value;
  }

  public function getFieldData() {
    return $this->data;
  }

  public function getFieldDataByIndex($index) {
    return $this->data[$index];
  }

  public function setFormData($index,$value) {
    $this->formData[$index] = $value;
  }

  public function getFormModel() {
    return array(
      'id' => $this->model->id,
      'modelName' => $this->model->modelName
    );
  }

  public function getFormData() {
    return array_merge(
      $this->model->buildFormData(),
      $this->formData
    );
  }

  public function getOldInput() {

    $oldInput =  array();

    if(!empty(Input::old('Tagging'))) {
      $oldInput['Tagging'] = json_encode(Input::old('Tagging'));
    }

    if(!empty(Input::old('Contact'))) {
      
      foreach (Input::old('Contact') as $key => $value) {

        $data = array();
        if(is_array($value)) {

          foreach ($value as $_value) {
            if(empty($_value['value'])) {
              continue;
            }
            $data[] = $_value['value'];
          }

          if(!empty($data)) {
            $oldInput['Contact'][$key] = json_encode($data);
          }
          
        }

      }

    }

    if(!empty(Input::old('Image'))) {

      foreach (Input::old('Image') as $token => $value) {
        $temporaryFile = Service::loadModel('TemporaryFile');
        $temporaryFile->deleteTemporaryDirectory(Input::old('model').'_'.$token);
      }

    }

    return $oldInput;
  }

  public function build() {

    return array(
      '_formModel' => $this->getFormModel(),
      '_fieldData' => $this->getFieldData(),
      '_formData' => $this->getFormData(),
      '_oldInput' => $this->getOldInput()
    );

  }

}

?>