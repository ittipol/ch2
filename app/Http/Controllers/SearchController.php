<?php

namespace App\Http\Controllers;

use App\library\service;
// use App\library\url;

class SearchController extends Controller
{
  public function index() {

    $lookup = Service::loadModel('Lookup');

    $q = trim($this->query['search_query']);

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $search = true;
    if(empty($q)) {
      $search = false;
    }

    $this->setData('count',0);

    if($search) {

      $_q = $q;

      $conditions = array();
      $or = array();

      $re = '/[(\"|\')]?\w+[(\+?|\s?)]\w+[(\"|\')]?/';

      preg_match_all($re, $_q, $words);

      foreach ($words[0] as $word) {
        $_q = str_replace($word, '', $_q);
        $word = str_replace(array('\'','"'), '', $word);
        $word = str_replace('+', ' ', $word);

        $word = strtolower($word);
        
        array_push($or,array('name','like','%'.$word.'%'));
        array_push($or,array('keyword_1','like','%'.$word.'%'));
        array_push($or,array('keyword_2','like','%'.$word.'%'));
        array_push($or,array('keyword_3','like','%'.$word.'%'));
        array_push($or,array('keyword_4','like','%'.$word.'%'));
        array_push($or,array('tags','like','%'.$word.'%'));
        array_push($or,array('address','like','%'.$word.'%'));
      }

      if(!empty($_q)) {

        $words = explode(' ', $_q);

        foreach ($words as $word) {

          $word = strtolower($word);

          array_push($or,array('name','like','%'.$word.'%'));
          array_push($or,array('keyword_1','like','%'.$word.'%'));
          array_push($or,array('keyword_2','like','%'.$word.'%'));
          array_push($or,array('keyword_3','like','%'.$word.'%'));
          array_push($or,array('keyword_4','like','%'.$word.'%'));
          array_push($or,array('tags','like','%'.$word.'%'));
          array_push($or,array('address','like','%'.$word.'%'));
        }

      }

      if(!empty($or)) {
        $conditions = array(
          'or' => $or 
        );
      }

      $lookup->paginator->criteria(array(
        'conditions' => $conditions
      ));
      $lookup->paginator->disableGetImage();
      $lookup->paginator->setPage($page);
      $lookup->paginator->setPerPage(20);
      $lookup->paginator->setPagingUrl('search');
      $lookup->paginator->setQuery('search_query',$q);

      $this->setData('results',$lookup->paginator->getPaginationData());
      $this->setData('_pagination',array(
        'page' => $lookup->paginator->getPage(),
        'paging' => $lookup->paginator->paging(),
        'next' => $lookup->paginator->next(),
        'prev' => $lookup->paginator->prev()
      ));
      $this->setData('count',$lookup->paginator->itemCount());

    }

    $this->setData('q',$q);

    return $this->view('pages.search.result');

  }
}
