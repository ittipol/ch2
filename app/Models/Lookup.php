<?php

namespace App\Models;

use App\library\service;
use App\library\cache;

class Lookup extends Model
{
  protected $table = 'lookups';
  protected $fillable = ['model','model_id','shop_id','shop_name','name','description','keyword_1','keyword_2','keyword_3','keyword_4','address','target_area','tags','active'];

  public $paginator = true;

  protected $filterOptions = array(
    'model' => array(
      'input' => 'checkbox',
      'title' => 'แสดงข้อมูล',
      'options' => array(
        array(
          'name' => 'บริษัทและร้านค้า',
          'value' => 'model:Shop'
        ),
        array(
          'name' => 'สินค้า',
          'value' => 'model:Product'
        ),
        array(
          'name' => 'แคตตาล็อกสินค้า',
          'value' => 'model:ProductCatalog'
        ),
        array(
          'name' => 'งาน',
          'value' => 'model:Job'
        ),
        array(
          'name' => 'โฆษณา',
          'value' => 'model:Advertising'
        ),
        // array(
        //   'name' => 'ประกาศซื้อ-เช่า-ขายสินค้า',
        //   'value' => 'model:Item'
        // ),
        // array(
        //   'name' => 'ประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์',
        //   'value' => 'model:RealEstate'
        // ),
        // array(
        //   'name' => 'งานฟรีแลนซ์',
        //   'value' => 'model:Freelance'
        // )
      )
    )
  );

  protected $sortingFields = array(
    'title' => 'จัดเรียงตาม',
    'options' => array(
      array(
        'name' => 'ตัวอักษร A - Z ก - ฮ',
        'value' => 'name:asc'
      ),
      array(
        'name' => 'ตัวอักษร Z - A ฮ - ก',
        'value' => 'name:desc'
      ),
      array(
        'name' => 'วันที่เก่าที่สุดไปหาใหม่ที่สุด',
        'value' => 'created_at:asc'
      ),
      array(
        'name' => 'วันที่ใหม่ที่สุดไปหาเก่าที่สุด',
        'value' => 'created_at:desc'
      ),
    )
  );

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

  // public static function boot() {

  //   parent::boot();

  //   Lookup::saving(function($lookup){

  //     if(!$lookup->exists && empty($lookup->active)){
  //       $lookup->active = 1;
  //     }

  //   });

  // }

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

    $taggings = $model->getRelatedData('Tagging',array(
      'fields' => array('word_id')
    ));

    $_words = array();
    if(!empty($taggings)){
      foreach ($taggings as $tagging) {

        if(empty($tagging->word)) {
          continue;
        }

        $_words[] = $tagging->word->word;
      }
    }

    if(!empty($_words)){
      $value['tags'] = implode(' ',$_words);
    }

    $value['target_area'] = $this->__getTargetArea($model);
    $value['address'] = $this->__getAddress($model,true);

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
    // $lookup = $this->getRelatedData($model->modelName,
    //   array(
    //     'first' => true
    //   )
    // );

    $lookup = $this->where([
      ['model','like',$model->modelName],
      ['model_id','=',$model->id]
    ])->first();

    if(isset($behavior['active'])) {
      $value['active'] =  $behavior['active'];
    }

    if(($model->state == 'update') && !empty($lookup)){
      return $lookup
      ->fill($value)
      ->save();
    }else{

      $shop = $model->getRelatedShop();

      if(!empty($shop)) {
        $value['shop_id'] = $shop->id;
        $value['shop_name'] = $shop->name;
      }

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

      // $lookup = new Lookup;

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

  public function __getAddress($model,$short = false) {

    // more than 2 address
    // $addresses = $model->getRelatedData('Address',array(
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

    $shop = $model->getRelatedShop();

    if(empty($shop)) {
      return null;
    }

    $address = $shop->getRelatedData('Address',array(
      'fields' => array(
        'address','province_id','district_id','sub_district_id'
      ),
      'first' => true
    ));

    if(empty($address)) {
      return null;
    }

    return $address->getAddress($short);
  }

  public function __getTargetArea($model) {

    $areas = $model->getRelatedData('TargetArea',array(
      'fields' => array('province_id'/*,'district_area','sub_district_area'*/)
    ));

    if(empty($areas)) {
      return null;
    }

    $targetAreas = null; 
    foreach ($areas as $area) {
      $targetAreas[] = Province::select('name')->find($area->province_id)->name;
    }

    if(!empty($targetAreas)) {
      $targetAreas = implode(' ', $targetAreas);
    }

    return $targetAreas;
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

    $data = Service::loadModel($this->model)->find($this->model_id);

    if(empty($data)) {
      return null;
    }

    return $data->buildLookupData();
  }

}
