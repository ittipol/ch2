<?php

namespace App\library;

use App\library\cache;

class ModelData {

	private $model;
	private $data = array();

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

    if(!empty($modeldNames)){

      foreach ($modeldNames as $key => $modelName) {

        if($this->model->modelName == $modelName) {
          continue;
        }

        if(!empty($options['models']) && !in_array($modelName, $options['models'])) {
          continue;
        }

        $json = in_array($modelName, $options['json']);

        $this->_getRelatedModelData($modelName,$json);

      }

    }

  }

  private function _getRelatedModelData($modelName,$json = false) {

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

      // case 'OfficeHour':

      //   $officeHour = $this->model->getRelatedModelData('OfficeHour',array(
      //     'first' => true,
      //     'fields' => array('same_time','time')
      //   ));

      //   if(empty($officeHour)){
      //     $this->data['officeHour'] = array();
      //     break;
      //   }

      //   $this->data['sameTime'] = $officeHour->same_time;

      //   $time = json_decode($officeHour->time,true);
      //   $officeHour = array();
      //   foreach ($time as $day => $value) {

      //     $startTime = explode(':', $value['start_time']);
      //     $endTime = explode(':', $value['end_time']);

      //     $officeHour[$day] = array(
      //       'open' => $value['open'],
      //       'start_time' => array(
      //         'hour' => (int)$startTime[0],
      //         'min' => (int)$startTime[1]
      //       ),
      //       'end_time' => array(
      //         'hour' => (int)$endTime[0],
      //         'min' => (int)$endTime[1]
      //       )
      //     );
      //   }

      //   $this->data['officeHour'] = json_encode($officeHour);

      //   break;

      case 'Contact':
        $data = $this->loadContact();
        break;

    }

    if($json) {
      $data = json_encode($data);
    }

    $this->data[$modelName] = $data; 

  }

  public function loadAddress() {

    $address = $this->model->getRelatedModelData('Address',
      array(
        'first' => true,
        'fields' => array('address','province_id','district_id','sub_district_id','description','latitude','longitude'),
        'order' => array('id','DESC')
      )
    );

    if(empty($address)){
      return array();
    }

    return $address->buildModelData();

  }

  public function loadImage() {

    $cache = new cache;

    $images = $this->model->getRelatedModelData('Image',array(
      'fields' => array('id','model','model_id','filename','description','image_type_id')
    ));

    if(empty($images)){
      return array();
    }

    $_images = array();
    foreach ($images as $image) {
      $_images[] = array_merge($image->buildModelData(),array(
        '_xs_url' => $cache->getCacheImageUrl($image,'xs')
      ));
    } 

    return $_images;

  }

  public function loadTagging() {
    $taggings = $this->model->getRelatedModelData('Tagging',
      array(
        'fields' => array('word_id')
      )
    );

    if(empty($taggings)) {
      return array();
    }

    $words = array();
    foreach ($taggings as $tagging) {
      $words[] = $tagging->buildModelData();
    }

    return $words;

  }

  public function loadContact() {
    $contact = $this->model->getRelatedModelData('Contact',array(
      'first' => true,
      'fields' => array('phone_number','email','line')
    ));

    if(empty($contact)) {
      return array();
    }

    return $contact->buildModelData();

  }

  public function set($index,$value) {
    $this->data[$index] = $value;
  }

  public function getModelData() {
    return array_merge(
      $this->model->buildModelData(),
      $this->data
    );
  }

  public function build($onlyData = false) {
    
    if($onlyData) {
      return $this->getModelData();
    }

    return array(
      '_modelData' => $this->getModelData()
    );

  }

}