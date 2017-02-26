<?php

namespace App\Models;

use App\library\service;
use App\library\cache;

class Lookup extends Model
{
  protected $table = 'lookups';
  protected $fillable = ['model','model_id','name','description','keyword_1','keyword_2','keyword_3','keyword_4','address','tags','active'];

  public $paginator = true;

  //  Lookup Special Format
  // 'keyword' => '{{Department.name|Company.id=>CompanyHasDepartment.company_id,CompanyHasDepartment.department_id=>Department.id}}',
  // 'keyword' => array(
  //   'get' => 'Department.name',
  //   'key' => array(
  //     'Company.id' => 'CompanyHasDepartment.company_id',
  //     'CompanyHasDepartment.department_id' => 'Department.id'
  //   )
  // ),
  // 
  // Call Method
  // 'address' => '{{__getAddress}}'

  public static function boot() {

    parent::boot();

    Lookup::saving(function($lookup){

      if(!$lookup->exists && empty($lookup->active)){
        $lookup->active = 1;
      }

    });

  }

  public function __saveRelatedData($model,$options = array()) {

    $behavior = $model->getBehavior('Lookup');

    if(empty($behavior['format'])) {
      return false;
    }

    $value = array();

    $data = $model->getAttributes();
    if(!empty($options['data'])){
      $data = array_merge($data,$options['data']);
    }

    $taggings = $model->getModelRelationData('Tagging',array(
      'fields' => array('word_id')
    ));

    $_words = array();
    if(!empty($taggings)){
      foreach ($taggings as $tagging) {
        $_words[] = $tagging->word->word;
      }
    }

    if(!empty($_words)){
      $value['tags'] = implode(' ',$_words);
    }

    $_addresses = $this->__getAddress($model);
    if(!empty($_addresses)) {
      $value['address'] = $_addresses;
    }

    $options = array(
      'data' => $data,
      'format' => $behavior['format']
    );

    // Parser
    $result = $this->parser($model,$options);

    if(!empty($result)){
      foreach ($result as $key => $_value){
        $value[$key] = $_value;
      }
    }

    // Get releated lookup data
    $lookup = $model->getModelRelationData($this->modelName,
      array(
        'first' => true
      )
    );

    // $value['active'] =  1;
    if(isset($behavior['active'])) {
      $value['active'] =  $behavior['active'];
    }

    if(($model->state == 'update') && !empty($lookup)){
      return $lookup
      ->fill($value)
      ->save();
    }else{
      return $this->fill($model->includeModelAndModelId($value))->save();
    }

  }

  private function parser($model,$options = array()) {

    if(empty($options['format']) || empty($options['data'])){
      return false;
    }

    $parseFormat = '/{{[\w\d|._,=>@]+}}/';
    $parseValue = '/[\w\d|._,=>@]+/';

    $result = array();

    foreach ($options['format'] as $key => $format){

      if(is_array($format)){
        $records = $this->_parser($format['get'],$model,array(
          'lookupArrayFormat' => $format['key'],
          'id' => $model->id
        ));

        list($class,$field) = explode('.', $format['get']);

        $_value = array();

        if(!empty($records[$class])){
          foreach ($records[$class] as $record) {
            $_value[] = $record[$field];
          }
        }
      
        $_value = implode(' ', $_value);

        $result[$key] = $this->_clean($_value);

      }else{
        preg_match_all($parseFormat, $format, $matches);

        if(!empty($matches[0])){

          $result[$key] = $format;

          foreach ($matches[0] as $value) {

            preg_match($parseValue, $value, $_matches);

            if(!empty($_matches[0])){

              if(substr($_matches[0],0,2) == '__'){

                if(strpos($_matches[0], '|')) {
                  
                  list($_model,$fx) = explode('|', $_matches[0]);

                  $_value = Service::loadModel(substr($_model,2))->{$fx}($model);

                }else{
                  $_value = $model->{substr($_matches[0],2)}();
                }
              
              }elseif(array_key_exists($_matches[0],$options['data'])) {
                $_value = $options['data'][$_matches[0]];
              }else{
                $parts = explode('|', $_matches[0]);

                if(!empty($parts[1])){

                  $records = $this->_parser($parts[0],$model,array(
                    'lookupStringFormat' => $parts[1]
                  ));

                  list($class,$field) = explode('.', $parts[0]);

                  $_value = array();

                  if(!empty($records[$class])){
                    foreach ($records[$class] as $record) {
                      $_value[] = $record[$field];
                    }
                  }
                
                  $_value = implode(' ', $_value);

                }
              }

              $result[$key] = $this->_replace($_value,$value,$result[$key]);

            }
          }

          $result[$key] = trim($result[$key]);

        }
      }

    }

    return $result;

  }
  
  private function _parser($fields,$class,$options = array()) {

    $data = array();

    if(empty($class)){
      return false;
    }

    if(!empty($options['lookupStringFormat'])) {

      $lookup = new Lookup;

      $formats = explode(',', $options['lookupStringFormat']);

      $records = array();
      foreach ($formats as $format) {
        list($key1,$key2) = explode('=>', $format);
        $records = $this->__lookupFormatParser($class,$key1,$key2,$records);
      }

      $fields = explode('.', $fields);

      $data = array();
      foreach ($records as $key => $record) {
        $data[$fields[0]][$key][$fields[1]] = $record[$fields[1]];
      }

    }elseif(!empty($options['lookupArrayFormat'])) {

      $lookup = new Lookup;

      $formats = $options['lookupArrayFormat'];

      $records = array();
      foreach ($formats as $key1 => $key2) {
        $records = $this->__lookupFormatParser($class,$key1,$key2,$records);
      }

      $fields = explode('.', $fields);

      $data = array();
      foreach ($records as $key => $record) {
        $data[$fields[0]][$key][$fields[1]] = $record[$fields[1]];
      }
    }

    return $data;

  }

  private function __lookupFormatParser($class,$key1,$key2,$records = array()) {

    $temp = array();

    if(substr($key1, 0, 1) == '@') {
      $func = substr($key1, 1);
      $records = $class->{$func}($key2);
      
      foreach ($records as $key => $_record) {
        $temp[] = $_record;
      }

      return $temp;
    }

    list($class1,$field1) = explode('.', $key1);
    list($class2,$field2) = explode('.', $key2);

    $class1 = Service::loadModel($class1);
    $class2 = Service::loadModel($class2);

    if(($class->modelName == $class1->modelName) && empty($records)){
      $records = $class->getAttributes();
    }

    if(array_key_exists($field1,$records)) {

      $_records = $class2->where($field2,'=',$records[$field1])->get();

      foreach ($_records as $key => $_record) {
        $temp[] = $_record;
      }

      $records = $temp;

    }else{

      foreach ($records as $key => $record) {

        if(empty($record[$field1])) {
          continue;
        }

        $_records = $class2->where($field2,'=',$record[$field1])->get();

        foreach ($_records as $key => $_record) {
          $temp[] = $_record->getAttributes();
        }
        
      }

      $records = $temp;

    }

    return $records;

  }

  private function __getAddress($model) {

    // more than 2 address
    // $addresses = $model->getModelRelationData('Address',array(
    //   'fields' => array(
    //     'address','district_id','sub_district_id'
    //   ),
    // ));

    // if(empty($addresses)) {
    //   return null;
    // }

    // $_address = array();
    // if(!empty($addresses)){
    //   foreach ($addresses as $address) {
    //     $_address[] = trim($address->district->name.' '.$address->subDistrict->name.' '.$address->address);
    //   }

    //   $_address = implode(' ', $_address);
    // }

    $address = $model->getModelRelationData('Address',array(
      'fields' => array(
        'address','province_id','district_id','sub_district_id'
      ),
      'first' => true
    ));

    if(empty($address)) {
      return null;
    }


    // village
    // $village = new Village;
    // $villages = $village->getData(array(
    //   'conditions' => array(
    //     ['district_id','=',$address->district->id],
    //     ['sub_district_id','=',$address->subDistrict->id]
    //   ),
    //   'fields' => array('name') 
    // ));

    $_address = '';

    if(!empty($address->address)) {
      $_address .= $address->address;
    }

    if(!empty($address->subDistrict)) {
      $_address .= ' '.$address->subDistrict->name;
    }

    if(!empty($address->district)) {
      $_address .= ' '.$address->district->name.' '.$address->district->zip_code;
    }

    if(!empty($address->province)) {
      $_address .= ' '.$address->province->name;
    }

    $address = trim($_address);

    // $address = trim($address->address.' '.$address->subDistrict->name.' '.$address->district->name.' '.$address->district->zip_code);

    // if(!empty($villages)) {
    //   foreach ($villages as $village) {
    //     $address .= ' '.$village->name;
    //   }
    // }

    return $this->_clean($address);

  }

  private function _replace($value,$key1,$key2) {
    $value = $this->_clean($value);
    return str_replace($key1, $value, $key2);
  }

  private function _clean($value) {
    $value = strip_tags($value);
    $value = trim(preg_replace('/\s\s+/', ' ', $value));
    return $value;
  }

  public function buildPaginationData() {
    return Service::loadModel($this->model)->find($this->model_id)->buildLookupData();
  }

}
