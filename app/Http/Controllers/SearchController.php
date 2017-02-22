<?php

namespace App\Http\Controllers;

use App\library\service;
// use App\library\url;

class SearchController extends Controller
{
  public function index() {

    $wordModel = Service::loadModel('word');
    $province = Service::loadModel('Province');
    $district = Service::loadModel('District');
    $subDistrict = Service::loadModel('SubDistrict');

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
      // $_q = str_replace(array(' + ',' : '), ' ', $_q);

      $pattern = '/\s[+\'\'\\\\\/:;()*-^&!<>\[\]\|]\s/';
      $_q = preg_replace($pattern, ' ', $_q);

      $conditions = array();
      $or = array();
      $and = array();

      $reZipcode = '/^[0-9]{5}$/';
      $reWordGroup = '/([\wก-๙]+|[(\"|\')]{1}[\wก-๙]+[(\+|\s)]{1}[\wก-๙]+[(\"|\')]{1})/';

      preg_match_all($reWordGroup, $_q, $words);

      $isAddress = false;
      $taggingWord = '';

      foreach ($words[0] as $word) {

        $word = str_replace(array('\'','"'), '', $word);
        $word = str_replace('+', ' ', $word);

        if(mb_strlen($word) < 3) {
          array_push($and,array('lookups.name','like','%'.$word.'%'));
          continue;
        }

        if($isAddress && preg_match($reZipcode, $word, $matches)) {
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
          array_push($or,array('lookups.lookup.name','like','%'.$word.'%'));
          array_push($or,array('lookups.tags','like','%'.$word.'%'));
          continue;
        }else{
          array_push($or,array('lookups.name','like','%'.$word.'%'));
          // array_push($or,array('keyword_1','like','%'.$word.'%'));
          // array_push($or,array('keyword_2','like','%'.$word.'%'));
          // array_push($or,array('keyword_3','like','%'.$word.'%'));
          // array_push($or,array('keyword_4','like','%'.$word.'%'));
        }

        array_push($or,array('lookups.keyword_1','like','%'.$word.'%'));
        
      }

      if(!empty($and)) {
        $conditions = $and;
      }

      if(!empty($or)) {
        $conditions = array_merge($conditions,array('or'=>$or));
      }

      $conditions = array_merge($conditions,array(
        array('lookups.active','=',1)
      ));

      if(!empty($conditions)) {

        $lookup->paginator->criteria(array(
          'conditions' => $conditions,
          'order' => array(
            array('created_at','DESC')
          )
        ));
        $lookup->paginator->disableGetImage();
        $lookup->paginator->setPage($page);
        $lookup->paginator->setPerPage(20);
        $lookup->paginator->setPagingUrl('search');
        $lookup->paginator->setQuery('search_query',$q);

        dd($lookup->paginator->getLookupPaginationData());

        $this->setData('results',$lookup->paginator->getPaginationData());
        $this->setData('_pagination',array(
          'page' => $lookup->paginator->getPage(),
          'paging' => $lookup->paginator->paging(),
          'next' => $lookup->paginator->next(),
          'prev' => $lookup->paginator->prev()
        ));
        $this->setData('count',$lookup->paginator->itemCount());

      }

    }

    $this->setData('q',$q);

    return $this->view('pages.search.result');

  }
}
