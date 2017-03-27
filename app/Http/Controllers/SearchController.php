<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\string;
use App\library\filterHelper;

class SearchController extends Controller
{
  public function index() {

    $lookup = Service::loadModel('Lookup');
    $filterHelper = new FilterHelper;

    $criteria = array();
    $conditions = array();

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $q = '';
    if(!empty($this->query['search_query'])) {
      $q = trim($this->query['search_query']);
    }

    $filters = array();
    if(!empty($this->query['fq'])) {
      $filters = $this->query['fq'];
    }

    $sort = 'created_at:desc';
    if(!empty($this->query['sort'])) {
      $sort = $this->query['sort'];
    }

    if(!empty($q)) {

      $filterHelper->setSearchQuery($q);
      $filterHelper->setFilters($filters);
      $filterHelper->setSorting($sort);
      // $criteria = $filterHelper->buildCriteria();

      // $lookup->paginator->criteria($criteria);
      $lookup->paginator->disableGetImage();
      $lookup->paginator->setPage($page);
      $lookup->paginator->setPerPage(20);
      $lookup->paginator->setPagingUrl('search');
      // $lookup->paginator->setQuery('search_query',$q);

      $this->setData('results',$lookup->paginator->search($filterHelper->buildCriteria()));
      $this->setData('_pagination',array(
        'page' => $lookup->paginator->getPage(),
        'paging' => $lookup->paginator->paging(),
        'next' => $lookup->paginator->next(),
        'prev' => $lookup->paginator->prev()
      ));
      $this->setData('count',$lookup->paginator->getCount());

    }

    // Get Sorting Fields
    $sortingFields = $lookup->getSortingFields();
    dd($sortingFields);

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
    // $this->setData('count',0);

    return $this->view('pages.search.result');

  }

}
