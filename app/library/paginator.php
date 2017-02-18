<?php 

namespace App\library;

use App\library\cache;
use Session;

class Paginator {

  private $total;
  private $page = 1;
  private $lastPage;
  private $perPage = 24;
  private $pagingUrl;
  private $urls = array();
  private $url;
  private $getImage = true;
  private $model;

  public function __construct($model = null) {
    $this->model = $model;
    $this->total = $model->count();
    $this->url = new Url;
  }

  public function setPerPage($perPage) {
    $this->perPage = (int)$perPage;
  }

  public function setPage($page) {
    $this->page = (int)$page;
  }

  public function setPagingUrl($url) {
    $this->pagingUrl = url($url);
  }

  public function setUrl($url,$index) {
    $this->url->setUrl($url,$index);
  }

  public function parseUrl($record) {
    return $this->url->parseUrl($record);
  }

  public function disableGetImage() {
    $this->getImage = false;
  }

  public function criteria($options = array()) {

    if(!empty($options['conditions']['in'])) {

      foreach ($options['conditions']['in'] as $condition) {

        if(empty($condition[1])) {
          continue;
        }

        $this->model = $this->model->whereIn($condition[0],$condition[1]);
      }

      unset($options['conditions']['in']);

    }

    if(!empty($options['conditions']['or'])) {

      $arrLen = count($options['conditions']['or']);
      for ($i=0; $i < $arrLen; $i++) {
        $this->model = $this->model->orWhere(
          $options['conditions']['or'][$i][0],
          $options['conditions']['or'][$i][1],
          $options['conditions']['or'][$i][2]
        );
      }

      unset($options['conditions']['or']);

    }

    if(!empty($options['conditions'])){
      $this->model = $this->model->where($options['conditions']);
    }

    if(!empty($options['fields'])){
      $model = $model->select($options['fields']);
    }

    if(!empty($options['order'])){

      if(is_array(current($options['order']))) {

        foreach ($options['order'] as $value) {
          $this->model = $this->model->orderBy($value[0],$value[1]);
        }

      }else{
        $this->model = $this->model->orderBy(current($options['order']),next($options['order']));
      }
      
    }

  }

  public function myData() {
    $this->model = $this->model->where('person_id','=',Session::get('Person.id'));
  }

  public function getModelData() {

    $offset = ($this->page - 1)  * $this->perPage;

    // $start = $offset + 1;
    // $end = min(($offset + $this->perPage), $this->total);

    $records = $this->model
    ->take($this->perPage)
    ->skip($offset)
    ->get();

    $cache = new Cache;

    $data = array();
    foreach ($records as $record) {

      $_data = array();
      if($this->getImage) {

        $image = $record->getModelRelationData('Image',array(
          'first' => true
        ));

        $_data['_imageUrl'] = '/images/common/no-img.png';
        if(!empty($image)) {
          $_data['_imageUrl'] = $cache->getCacheImageUrl($image,'list');
        }

      }

      $data[] = array_merge(
        $_data,
        $record->buildPaginationData(),
        $this->parseUrl($record->getRecordForParseUrl())
      );

    }

    return $data;

  }

  public function next() {

    $pagingUrl = $this->pagingUrl.'?page={n}';
    
    $next['url'] = str_replace('{n}', $this->page+1, $pagingUrl);
    if(($this->page + 1) > $this->lastPage) {
      $next['url'] = null;
    }

    return $next;
  }

  public function prev() {

    $pagingUrl = $this->pagingUrl.'?page={n}';

    $prev['url'] = str_replace('{n}', $this->page-1, $pagingUrl);
    if(($this->page - 1) < 1) {
      $prev['url'] = null;
    }

    return $prev;
  }

  public function paging() {

    $paging = array();
    $pagingUrl = $this->pagingUrl.'?page={n}';

    $skip = true;
    if(($this->page - 4) < 1){

      for ($i=1; $i < 6; $i++) { 

        if($i > $this->lastPage) {
          $skip = false;
          break;
        }
        
        $paging[] = array(
          'pageNumber' => $i,
          'url' => str_replace('{n}', $i, $pagingUrl)
        );

      }

      if($skip) {

        if(($this->lastPage - 5) > 2) {
          $paging[] = array(
            'pageNumber' => '...',
            'url' => null
          );
        }
        
        $paging[] = array(
          'pageNumber' => $this->lastPage,
          'url' => str_replace('{n}', $this->lastPage, $pagingUrl)
        );

      }

      
    }elseif(($this->page + 4) > $this->lastPage) {

      $paging[] = array(
        'pageNumber' => 1,
        'url' => str_replace('{n}', 1, $pagingUrl)
      );

      if(($this->lastPage-5) > 2) {
        $paging[] = array(
          'pageNumber' => '...',
          'url' => null
        );
      }

      for ($i=4; $i >= 0; $i--) { 

        $paging[] = array(
          'pageNumber' => $this->lastPage-$i,
          'url' => str_replace('{n}', $this->lastPage-$i, $pagingUrl)
        );

      }

    }else{

      $paging[] = array(
        'pageNumber' => 1,
        'url' => str_replace('{n}', 1, $pagingUrl)
      );

      $paging[] = array(
        'pageNumber' => '...',
        'url' => null
      );

      $start = $this->page - 2;

      for($i=1; $i < 4; $i++) {
        $paging[] = array(
          'pageNumber' => $start+$i,
          'url' => str_replace('{n}', $start+$i, $pagingUrl)
        );
      }

      $paging[] = array(
        'pageNumber' => '...',
        'url' => null
      );

      $paging[] = array(
        'pageNumber' => $this->lastPage,
        'url' => str_replace('{n}', $this->lastPage, $pagingUrl)
      );

    }

    return $paging;

  }

  public function build() {

    if(empty($this->model)) {
      return false;
    }

    $this->lastPage = (int)ceil($this->total / $this->perPage);

    return array(
      '_pagination' => array(
        'page' => $this->page,
        'lastPage' => $this->lastPage,
        'total' => $this->total,
        'paging' => $this->paging(),
        'next' => $this->next(),
        'prev' => $this->prev(),
        'data' => $this->getModelData()
      )
    );

  }

}

?>