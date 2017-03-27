<?php

namespace App\library;

class FilterHelper {

  private $searchQuery;
  private $filters;
  private $sort;
  private $criteria = array();

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

    $query = array_merge($query,array(
      array('lookups.active','=',1)
    ));

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
            $_filters['lookups.model'][] = $string->generateModelNameWithoutUnderScore($value);
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

      $orderBy['orderByRaw'] = 'FIELD(lookups.id, '.implode(' ,', $ids).') DESC, lookups.'.$sortingField.' '.strtoupper($order);
    }else{
      $orderBy['order'] = array('lookups.'.$sortingField,strtoupper($order));
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

  public function setCriteria($model,$criteria) {
    
    foreach ($criteria as $alias => $value) {
      
      // switch ($alias) {
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

      $model = $this->setCondition($model,$value);

    }

    return $model;

  }

  public function setCondition($model,$conditions) {
    return $this->_setCondition($model,$conditions);
  }

  private function _setCondition($model,$conditions) {

    $query = $model;

    foreach ($conditions as $operator => $value) {

      if($operator === 'or') {

        if(!empty(next($value) && !is_array(current($value)))) {
          $query->orWhere(
            prev($value),
            next($value),
            next($value)
          );
        }else{
          $query->orWhere(function ($_query) use($value) {
            $_query = $this->setCondition($_query,$value);
          });
        }

      }elseif($operator === 'in') {

        if(is_array(current($value))) {
          
          foreach ($value as $_value) {
            $model = $model->whereIn(current($_value),next($_value));
          }

        }else{
          $query->whereIn(current($value),next($value));
        }

      }elseif($operator === 'order') {

        if(is_array(current($value))) {

          foreach ($value as $value) {
            $model->orderBy(current($value),next($value));
          }

        }else{
          $model->orderBy(current($value),next($value));
        }

      }elseif($operator === 'orderByRaw') {

        $model->orderByRaw($value);

      }else { // AND

        if(!empty(next($value) && !is_array(current($value)))) {
          $query->where(
            prev($value),
            next($value),
            next($value)
          );
        }else{
          $query->where(function ($_query) use($value) {
            $_query = $this->setCondition($_query,$value);
          });
        }
      }

    }

    return $query;

  }

  public function filterValueValidation($value) {
    if(preg_match('/^\w+:\w+$/', $value)) {
      return true;
    }
    return false;
  }

  public function getSortingFields($fields) {

    foreach ($fields as $field) {
      // ASC

      // DESC
    }

  }

}

?>