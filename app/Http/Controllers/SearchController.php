<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\string;

class SearchController extends Controller
{
  public function index() {

    $string = new String;

    $lookup = Service::loadModel('Lookup');
    $wordModel = Service::loadModel('word');
    $province = Service::loadModel('Province');
    $district = Service::loadModel('District');
    $subDistrict = Service::loadModel('SubDistrict');

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $q = '';
    if(!empty($this->query['search_query'])) {
      $q = trim($this->query['search_query']);
    }

    $_filters = array();
    if(!empty($this->query['fq'])) {
      $filters = explode(',', $this->query['fq']);
     
      foreach ($filters as $filter) {
        list($alias,$value) = explode(':', $filter);
        
        switch ($alias) {
          case 'model':
              $_filters['lookups.model'][] = $string->generateModelNameWithoutUnderScore($value);
            break;
        }

      }

    }

    $sort = array('created_at','DESC');
    if(!empty($this->query['sort'])) {
      list($alias,$value) = explode(':', $this->query['sort']);
      $sort = array($alias,strtoupper($value));
    }

    $this->setData('count',0);

    if(!empty($q)) {

      $conditions = array();

      $_q = $q;

      $or = array();
      $and = array();

      $pattern = '/\s[+\'\'\\\\\/:;()*\-^&!<>\[\]\|]\s/';
      $_q = preg_replace($pattern, ' ', $_q);

      $zipcodePattern = '/^[0-9]{5}$/';
      $wordGroupPattern = '/([\wก-๙]+|[(\"|\')]{1}[\wก-๙]+[(\+|\s)]{1}[\wก-๙]+[(\"|\')]{1})/';

      preg_match_all($wordGroupPattern, $_q, $words);

      $isAddress = false;
      $taggingWord = '';

      foreach ($words[0] as $word) {

        $word = str_replace(array('\'','"'), '', $word);
        $word = str_replace('+', ' ', $word);

        if(mb_strlen($word) < 3) {
          array_push($or,array('keyword_1','like','%'.$word.'%'));
          array_push($or,array('keyword_2','like','%'.$word.'%'));
          array_push($or,array('keyword_3','like','%'.$word.'%'));
          array_push($or,array('keyword_4','like','%'.$word.'%'));
          continue;
        }

        if($isAddress && preg_match($zipcodePattern, $word, $matches)) {
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

        array_push($or,array('keyword_1','like','%'.$word.'%'));
        array_push($or,array('keyword_2','like','%'.$word.'%'));
        array_push($or,array('keyword_3','like','%'.$word.'%'));
        array_push($or,array('keyword_4','like','%'.$word.'%'));
        
      }

      if(!empty($and)) {
        $conditions = $and;
      }

      if(!empty($or)) {
        $conditions = array_merge($conditions,array(
          'or' => $or
        ));
      }

      if(!empty($_filters)) {

        foreach ($_filters as $alias => $filter) {
          $conditions['in'][] = array($alias,$filter);
        }

      }

      $conditions = array_merge($conditions,array(
        array('lookups.active','=',1)
      ));

      if(!empty($conditions)) {

        $criteria = array();

        $criteria['conditions'] = $conditions;
        $criteria['fields'] = array('lookups.*');

        $data = $lookup->select('id')->where('name','like',$_q);
        if($data->exists()) {

          $ids = array();
          foreach ($data->get() as $value) {
            $ids[] = $value->id;
          }

          $criteria['orderByRaw'] = 'FIELD(lookups.id, '.implode(' ,', $ids).') DESC, created_at DESC';
        }else{
          $criteria['order'] = array('created_at','DESC');
        }

        $lookup->paginator->criteria($criteria);
        $lookup->paginator->disableGetImage();
        $lookup->paginator->setPage($page);
        $lookup->paginator->setPerPage(20);
        $lookup->paginator->setPagingUrl('search');
        // $lookup->paginator->setQuery('search_query',$q);

        $this->setData('results',$lookup->paginator->getLookupPaginationData());
        $this->setData('_pagination',array(
          'page' => $lookup->paginator->getPage(),
          'paging' => $lookup->paginator->paging(),
          'next' => $lookup->paginator->next(),
          'prev' => $lookup->paginator->prev()
        ));
        $this->setData('count',$lookup->paginator->getCount());

      }

    }

    // public $sortingFields = array('name','created_at');
    // library filter helper
    // if model = search mean all model
    // then model = Item mean select Item automatic

    // Get Filter Data
    $filterOptions = array(
      'model' => array(
        array(
          'name' => 'ร้านค้า',
          'value' => 'shop',
          'select' => true
        ),
        array(
          'name' => 'สินค้าในร้านค้า',
          'value' => 'product',
          'select' => true
        ),
        array(
          'name' => 'ประกาศงาน',
          'value' => 'job',
          'select' => true
        ),
        array(
          'name' => 'โฆษณาจากร้านค้า',
          'value' => 'advertising',
          'select' => true
        ),
        array(
          'name' => 'ประกาศซื้อ-เช่า-ขายสินค้า',
          'value' => 'item',
          'select' => true
        ),
        array(
          'name' => 'ประกาศซื้อ-เช่า-ขายอสังหาริมทรัพย์',
          'value' => 'real_estate',
          'select' => true
        )
      ),
      'sort' => array(
        array(
          'name' => 'ตัวอักษร A - Z ก - ฮ',
          'value' => 'name:asc',
          'select' => true
        ),
        array(
          'name' => 'ตัวอักษร Z - A ฮ - ก',
          'value' => 'name:desc',
          'select' => false
        ),
        array(
          'name' => 'วันที่เก่าที่สุดไปหาใหม่ที่สุด',
          'value' => 'created_at:asc',
          'select' => false
        ),
        array(
          'name' => 'วันที่ใหม่ที่สุดไปหาเก่าที่สุด',
          'value' => 'created_at:desc',
          'select' => false
        )
      )
    );

    foreach ($filterOptions['sort'] as $sort) {
      # code...
    }

    $this->setData('q',$q);
    $this->setData('filters',$filterOptions);

    return $this->view('pages.search.result');

  }
}
