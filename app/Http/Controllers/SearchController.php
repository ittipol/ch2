<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\stringHelper;
use App\library\filterHelper;

class SearchController extends Controller
{
  public function index() {

    $lookup = Service::loadModel('Lookup');
    $filterHelper = new FilterHelper($lookup);

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

    $filters = '';
    if(!empty($this->query['fq'])) {
      $filters = $this->query['fq'];
    }

    $sort = '';
    if(!empty($this->query['sort'])) {
      $sort = $this->query['sort'];
    }

    if(!empty($q)) {

      $filterHelper->setSearchQuery($q);
      $filterHelper->setFilters($filters);
      $filterHelper->setSorting($sort);

      $lookup->paginator->disableGetImage();
      $lookup->paginator->setPage($page);
      $lookup->paginator->setPerPage(20);
      $lookup->paginator->setPagingUrl('search');
      $lookup->paginator->setQuery('search_query',$q);
      $lookup->paginator->setQuery('sort',$sort);
      $lookup->paginator->setQuery('fq',$filters);

      $this->setData('results',$lookup->paginator->search($filterHelper->buildCriteria()));
      $this->setData('_pagination',array(
        'page' => $lookup->paginator->getPage(),
        'paging' => $lookup->paginator->paging(),
        'next' => $lookup->paginator->next(),
        'prev' => $lookup->paginator->prev()
      ));
      
      $count = $lookup->paginator->getCount();

    }

    $searchOptions = array(
      'filters' => $filterHelper->getFilterOptions(),
      'sort' => $filterHelper->getSortingOptions()
    );

    // $displayingFilters = array(
    //   'filters' => $filterHelper->getDisplayingFilterOptions(),
    //   'sort' => $filterHelper->getDisplayingSorting()
    // );

    $this->setData('q',$q);
    $this->setData('count',$lookup->paginator->getCount());
    $this->setData('searchOptions',$searchOptions);
    // $this->setData('displayingFilters',$displayingFilters);

    $this->setPageTitle($q);
    $this->setPageDescription('ค้นหาสินค้า ร้านค้า งาน และอื่นๆ');
    $this->setMetaKeywords(implode(',', explode(' ', $q)));

    $this->botAllowed();
 
    return $this->view('pages.search.result');

  }

}
