<?php

namespace App\library;

class FilterHelper {

  private $searchQuery;
  private $filters;
  private $sort;
  private $criteria = array();

  private $filterOptions = array(
    'model' => array(
      'title' => 'แสดงข้อมูล',
      'options' => array(
        array(
          'name' => 'ร้านค้า',
          'value' => 'model:shop',
        ),
        array(
          'name' => 'สินค้าในร้านค้า',
          'value' => 'model:product',
        ),
        array(
          'name' => 'ประกาศงาน',
          'value' => 'model:job',
        ),
        array(
          'name' => 'โฆษณาจากร้านค้า',
          'value' => 'model:advertising',
        ),
        array(
          'name' => 'ประกาศซื้อ-เช่า-ขายสินค้า',
          'value' => 'model:item',
        ),
        array(
          'name' => 'ประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์',
          'value' => 'model:real_estate',
        )
      )
    )
  );

  public function setSearchQuery($searchQuery) {
    $this->searchQuery = $searchQuery;
  }

  public function setFilters($filters) {
    $this->filters = $filters;
  }

  public function setSorting($sort) {
    $this->sort = $sort;
  }

  public function buildSearchQuery($q = null) {

    if(empty($q) && !empty($this->searchQuery)) {
      $q = $this->searchQuery;
    }elseif(empty($q)) {
      return null;
    }

    $validation = new validation;

    $wordModel = Service::loadModel('word');
    $province = Service::loadModel('Province');
    $district = Service::loadModel('District');
    $subDistrict = Service::loadModel('SubDistrict');

    $or = array();
    $and = array();

    $pattern = '/\s[+\'\'\\\\\/:;()*\-^&!<>\[\]\|]\s/';
    $_q = preg_replace($pattern, ' ', $q);

    $wordGroupPattern = '/([\wก-๙]+|[(\"|\')]{1}[\wก-๙]+[(\+|\s)]{1}[\wก-๙]+[(\"|\')]{1})/';
    preg_match_all($wordGroupPattern, $_q, $words);

    $isAddress = false;

    foreach ($words[0] as $word) {

      $word = str_replace(array('\'','"'), '', $word);
      $word = str_replace('+', ' ', $word);

      if(mb_strlen($word) < 3) {
        array_push($or,array('lookups.keyword_1','like','%'.$word.'%'));
        array_push($or,array('lookups.keyword_2','like','%'.$word.'%'));
        array_push($or,array('lookups.keyword_3','like','%'.$word.'%'));
        array_push($or,array('lookups.keyword_4','like','%'.$word.'%'));
        continue;
      }

      // if($isAddress && preg_match($zipcodePattern, $word, $matches)) {
      if($isAddress && $validation->isZipcode($word)) {
        $isAddress = false;
        array_push($or,array('lookups.address','like','%'.$word.'%'));
        continue;
      }

      if($province->where('name','like',$word)->exists() || $district->where('name','like',$word)->exists() || $subDistrict->where('name','like',$word)->exists()) {
        $isAddress = true;
        array_push($or,array('lookups.address','like','%'.$word.'%'));
        continue;
      }

      if($wordModel->where('word','like',$word)->exists()) {
        array_push($or,array('lookups.name','like','%'.$word.'%'));
        array_push($or,array('lookups.tags','like','%'.$word.'%'));
        continue;
      }else{
        array_push($or,array('lookups.name','like','%'.$word.'%'));
      }

      array_push($or,array('lookups.keyword_1','like','%'.$word.'%'));
      array_push($or,array('lookups.keyword_2','like','%'.$word.'%'));
      array_push($or,array('lookups.keyword_3','like','%'.$word.'%'));
      array_push($or,array('lookups.keyword_4','like','%'.$word.'%'));
      
    }

    $query = array(); 

    if(!empty($and)) {
      $query = $and;
    }

    if(!empty($or)) {
      $query['or'] = $or;
    }

    return $query;

  }

  public function buildFilters($filters = array()) {

    if(empty($filters) && !empty($this->filters)) {
      $filters = $this->filters;
    }elseif(empty($filters)) {
      return null;
    }

    $string = new String;

    $filters = explode(',', $filters);

    if(empty($filters)) {
      return null;
    }

    $_filters = array();
    foreach ($filters as $filter) {

      if(!$this->filterValueValidation($filter)) {
        continue;
      }

      list($alias,$value) = explode(':', $filter);
      
      switch ($alias) {
        case 'model':
            $_filters['lookups.model'][] = $string->generateModelNameCamelCase($value);
          break;
      }

    }

    $filters = array();
    if(!empty($_filters)) {
      foreach ($_filters as $alias => $filter) {

        switch ($alias) {
          case 'lookups.model':
            $filters['in'][] = array($alias,$filter);
            break;
        }

      }
    }

    return $filters;

  }

  public function buildSorting($sort = null,$q = null) {

    if(empty($sort) && !empty($this->sort)) {
      $sort = $this->sort;
    }elseif(empty($sort)) {
      return null;
    }

    if(!$this->filterValueValidation($sort)) {
      return null;
    }

    list($sortingField,$order) = explode(':', $sort);

    if(empty($sortingField) || empty($order)) {
      return null;
    }

    if(empty($q) && !empty($this->q)) {
      $q = $this->searchQuery;
    }

    $hasData = false;

    if(!empty($q)) {
      $data = Service::loadModel('Lookup')->select('id')->where('name','like',$q);
      $hasData = $data->exists();
    }

    if($hasData) {
      $ids = array();
      foreach ($data->get() as $value) {
        $ids[] = $value->id;
      }

      $orderBy['orderByRaw'] = 'FIELD(lookups.id, '.implode(' ,', $ids).') desc, lookups.'.$sortingField.' '.strtolower($order);
    }else{
      $orderBy['order'] = array('lookups.'.$sortingField,strtolower($order));
    }

    return $orderBy;

  }

  public function buildCriteria() {

    return array(
      'query' => $this->buildSearchQuery(),
      'filter' => $this->buildFilters(),
      'order' => $this->buildSorting()
    );

  }

  public function filterValueValidation($value) {
    if(preg_match('/^\w+:\w+$/', $value)) {
      return true;
    }
    return false;
  }

  public function setCriteria($model,$criteria) {
    
    foreach ($criteria as $key => $value) {
      
      // switch ($key) {
      //   case 'query':
      //       $model = $this->setCondition($model,$value);
      //     break;
        
      //   case 'filter':
      //       $model = $this->setCondition($model,$value);
      //     break;

      //   case 'order':
      //       $model = $this->setCondition($model,$value);
      //     break;
      // }

      if($key === 'order') {
        continue;
      }

      $model = $this->setCondition($model,$value);

    }

    return $model;

  }

  public function setCondition($model,$conditions) {

    if(count($conditions) == 1) {
      $model = $this->_setCondition($model,$conditions);
    }else{
      $model->where(function ($_query) use($conditions) {
        $_query = $this->_setCondition($_query,$conditions);
      });
    }

    return $model;

  }

  private function _setCondition($model,$conditions,$operator = 'and') {

    if(empty($conditions)) {
      return $model;
    }

    $query = $model;

    foreach ($conditions as $_operator => $value) {

      if($_operator === 'in') {

        if(is_array(current($value))) {
          
          foreach ($value as $_value) {

            if($operator === 'and') {
              $query->whereIn(current($_value),next($_value));
            }else{
              $query->orWhere(function ($_query) use($_value) {
                $_query->whereIn(current($_value),next($_value));
              });
            }

          }

        }else{

          if($operator === 'and') {
            $query->whereIn(current($value),next($value));
          }else{
            $query->orWhere(function ($_query) use($value) {
              $_query->whereIn(current($value),next($value));
            });
          }

        }

      }elseif(!empty(next($value) && !is_array(current($value)))) {

        if($operator === 'and') {
          $query->where(prev($value),next($value),next($value));
        }elseif($operator === 'or') {
          $query->orWhere(prev($value),next($value),next($value));
        }

      }else{

        if(($_operator !== 'and') && ($_operator !== 'or')) {
          $_operator = 'and';
        }

        if($operator === 'and') {
          $query->where(function ($_query) use($value,$_operator) {
            $_query = $this->_setCondition($_query,$value,$_operator);
          });
        }elseif($operator === 'or') {
          $query->orWhere(function ($_query) use($value,$_operator) {
            $_query = $this->_setCondition($_query,$value,$_operator);
          });
        }

      }

    }

    return $query;

  }

  // private function _setCondition($model,$conditions) {

  //   if(empty($conditions)) {
  //     return $model;
  //   }

  //   $query = $model;

  //   foreach ($conditions as $operator => $value) {

  //     if($operator === 'or') {

  //       if(!empty(next($value) && !is_array(current($value)))) {
  //         $query->orWhere(
  //           prev($value),
  //           next($value),
  //           next($value)
  //         );
  //       }else{
  //         $query->orWhere(function ($_query) use($value) {
  //           $_query = $this->_setCondition($_query,$value);
  //         });
  //       }

  //     }elseif($operator === 'in') {

  //       if(is_array(current($value))) {
          
  //         foreach ($value as $_value) {
  //           $model = $model->whereIn(current($_value),next($_value));
  //         }

  //       }else{
  //         $query->whereIn(current($value),next($value));
  //       }

  //     }else { // AND

  //       if(!empty(next($value) && !is_array(current($value)))) {
  //         $query->where(
  //           prev($value),
  //           next($value),
  //           next($value)
  //         );
  //       }else{
  //         $query->where(function ($_query) use($value) {
  //           $_query = $this->_setCondition($_query,$value);
  //         });
  //       }
  //     }

  //   }

  //   return $query;

  // }

  public function setOrder($model,$criteria) {

    if(empty($criteria['order'])) {
      return $model;
    }

    $key = key($criteria['order']);
    $value = $criteria['order'];

    if($key === 'order') {

      if(is_array(current($value))) {

        foreach ($value as $value) {
          $model->orderBy(current($value),next($value));
        }

      }else{
        $model->orderBy(current($value),next($value));
      }

    }elseif($key === 'orderByRaw') {

      $model->orderByRaw($value);

    }

    return $model;  

  }

  public function getFilterOptions($filters) {

    if(!empty($filters)) {
      $filters = explode(',', $filters);
    }else {
      $filters = null;
    }

    $_filterOptions = array();
    foreach ($this->filterOptions as $key => $filter) {

      $_filterOptions[$key]['title'] = $filter['title'];

      foreach ($filter['options'] as $option) {

        $select = true;
        if(!empty($filters)) {
          $select = in_array($option['value'], $filters);
        }

        $_filterOptions[$key]['options'][] = array(
          'name' => $option['name'],
          'value' => $option['value'],
          'select' => $select
        ); 
      }

    }

    return $_filterOptions;

  }

  public function getSortingOptions($fields,$sort = null) {

    $orders = array(
      'asc',
      'desc'
    );

    $sortingOptions = array();
    foreach ($fields as $field) {

      foreach ($orders as $order) {

        $value = $field.':'.$order;

        $select = false;
        if($sort == $value){
          $select = true;
        }
   
        $sortingOptions[] = array(
          'name' => $this->getsortiongOptionName($field,$order),
          'value' => $value,
          'select' => $select
        );

      } 

    }

    return $sortingOptions;

  }

  private function getsortiongOptionName($field, $order) {

    $name = '';

    switch ($field) {
      case 'name':
        
          if($order == 'asc') {
            $name = 'ตัวอักษร A - Z ก - ฮ';
          }else{
            $name = 'ตัวอักษร Z - A ฮ - ก';
          }

        break;
      
      case 'created_at':
        
          if($order == 'asc') {
            $name = 'วันที่เก่าที่สุดไปหาใหม่ที่สุด';
          }else{
            $name = 'วันที่ใหม่ที่สุดไปหาเก่าที่สุด';
          }

        break;
    }

    return $name;

  }

  public function getDisplayingFilterOptions($filters) {

    if(!empty($filters)) {
      $filters = explode(',', $filters);
    }else {
      $filters = null;
    }

    $_displayingfilterOptions = array();
    foreach ($this->filterOptions as $key => $filter) {

      $_displayingfilterOptions[$key]['title'] = $filter['title'];

      foreach ($filter['options'] as $option) {

        $select = true;
        if(!empty($filters)) {
          $select = in_array($option['value'], $filters);
        }

        if($select) {
          $_displayingfilterOptions[$key]['display'][] = $option['name'];
        }

      }

    }

    return $_displayingfilterOptions;

  }

  public function getDisplayingSorting($fields,$sort = null) {

    $orders = array(
      'asc',
      'desc'
    );

    $displayingSorting = '';
    foreach ($fields as $field) {

      foreach ($orders as $order) {

        $value = $field.':'.$order;

        if($sort == $value){
          $displayingSorting = $this->getsortiongOptionName($field,$order);
          break;
        }

      }

      if(!empty($displayingSorting)) {
        break;
      }

    }

    return $displayingSorting;

  }

}

?>