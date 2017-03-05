<?php 

namespace App\library;

use App\library\cache;
use Session;
use Auth;

class Paginator {

  private $model;
  private $total;
  private $page = 1;
  private $lastPage;
  private $perPage = 24;
  private $count;
  private $pagingUrl;
  private $urls = array();
  private $url;
  private $onlyMyData = false;
  private $getImage = true;
  private $queries = array();
  private $criteriaData = array();

  public function __construct($model = null) {
    $this->model = $model;
    $this->url = new Url;
  }

  public function setQuery($name,$value) {
    $this->queries[$name] = $value;
  }

  public function parseQuery() {

    $url = $this->pagingUrl;

    $queries = array();
    foreach ($this->queries as $key => $value) {
      $queries[] = $key.'='.$value;
    }

    if(!empty($queries)) {
      $url .= '?'.implode('&', $queries);
    }

    return $url;

  }

  public function setPerPage($perPage) {
    $this->perPage = (int)$perPage;
  }

  public function setPage($page) {
    $this->page = (int)$page;
  }

  public function getPage() {
    return $this->page;
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

  public function getTotal() {
    return $this->model->count();
  }

  public function getLastPage() {
    return (int)ceil($this->getTotal() / $this->perPage);
  }

  public function criteria($options = array()) {
    $this->criteriaData = $options;
  }

  private function condition($model) {

    $criteria = $this->criteriaData;

    if(!empty($criteria['conditions']['in'])) {

      foreach ($criteria['conditions']['in'] as $condition) {

        if(empty($condition[1])) {
          continue;
        }

        $model = $model->whereIn($condition[0],$condition[1]);
      }

      unset($criteria['conditions']['in']);

    }

    if(!empty($criteria['conditions']['or'])) {

      $conditions = $criteria['conditions']['or'];

      $model = $model->where(function($query) use($conditions) {

        foreach ($conditions as $condition) {
          $query->orWhere(
            $condition[0],
            $condition[1],
            $condition[2]
          );
        }

      });

      unset($criteria['conditions']['or']);

    }

    if(!empty($criteria['conditions'])){
      $model = $model->where($criteria['conditions']);
    }

    if($this->onlyMyData) {
      $model = $model->where('person_id','=',Session::get('Person.id'));
    }

    if(!empty($criteria['fields'])){
      $model = $model->select($criteria['fields']);
    }

    return $model;

  }

  public function order($model) {

    if(!empty($this->criteriaData['order'])){

      if(is_array(current($this->criteriaData['order']))) {

        foreach ($this->criteriaData['order'] as $value) {
          $model = $model->orderBy($value[0],$value[1]);
        }

      }else{
        $model = $model->orderBy(current($this->criteriaData['order']),next($this->criteriaData['order']));
      }
      
    }

    return $model;

  }

  public function myData() {
    $this->onlyMyData = true;
  }

  // public function itemCount() {

  //   $offset = ($this->page - 1)  * $this->perPage;

  //   return $this->condition($this->model->newInstance())->count();
  // }

  public function getCount() {
    return $this->count;
  }

  public function getPermissionPaginationData() {

    $cache = new Cache;

    $offset = ($this->page - 1)  * $this->perPage;

    $model = $this->condition($this->model->newInstance());
    $model = $this->order($model);

    $records = $model
    ->join('data_access_permissions', 'data_access_permissions.model_id', '=', $this->model->getTable().'.id')
    ->join('access_levels', 'access_levels.id', '=', 'data_access_permissions.page_level_id')
    ->where('data_access_permissions.model','like',$this->model->modelName)
    ->Where(function ($query) {
      $query = $this->getAccessPermision($query);
    })    
    // ->select('data_access_permissions.*')
    ->take($this->perPage)
    ->skip($offset)
    ->get();

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

  public function getLookupPaginationData() {

    $cache = new Cache;

    $offset = ($this->page - 1)  * $this->perPage;

    $model = $this->model->newInstance()
    ->join('data_access_permissions',function($join) {
      $join->on('data_access_permissions.model_id', '=', $this->model->getTable().'.model_id')
           ->on('data_access_permissions.model', '=',$this->model->getTable().'.model');
    })
    ->join('access_levels', 'access_levels.id', '=', 'data_access_permissions.page_level_id')
    ->where(function ($query) {
      $query = $this->getAccessPermision($query);
    });

    $model = $this->condition($model);

    $this->count = $model->count();

    $model = $this->order($model);

    $records = $model
    ->take($this->perPage)
    ->skip($offset)
    ->get();

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

  public function getAccessPermision($query) {

    $table = $this->model->getTable();

    // All person can access
    $query->orWhere('access_levels.level','=',99);

    if(Auth::check()) {
      // All member can access
      $query->orWhere('access_levels.level','=',9);
    }

    // Only person, you follow
    // <-- Code here -->
    // $query->orWhere(function($query) use($table,$personIds) {
    //   $query->where('access_levels.level', '=', 5)
    //         ->whereIn($table.'.person_id', $personIds);
    // })

    // only me can access
    $query->orWhere(function($query) {
      $query->where('access_levels.level', '=', 1)
            ->where('data_access_permissions.owner', 'like', 'Person')
            ->where('data_access_permissions.owner_id', '=', session()->get('Person.id'));
    });

    // $query->orWhere('access_levels.level','=',99)
    //       ->orWhere('access_levels.level','=',5)
    //       ->orWhere(function($query) use($table,$personIds) {
    //         $query->where('access_levels.level', '=', 2)
    //               ->whereIn($table.'.person_id', $personIds);
    //       })
    //       ->orWhere(function($query) use($table) {
    //         $query->where('access_levels.level', '=', 1)
    //               ->where($table.'.person_id', '=', session()->get('Person.id'));
    //       });

    return $query;

  }

  public function getPaginationData() {

    $cache = new Cache;

    $offset = ($this->page - 1)  * $this->perPage;

    $model = $this->condition($this->model->newInstance());
    $model = $this->order($this->model);

    $records = $model
    ->take($this->perPage)
    ->skip($offset)
    ->get();

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

    $this->setQuery('page','{n}');
    $pagingUrl = $this->parseQuery();
    
    $next['url'] = str_replace('{n}', $this->page+1, $pagingUrl);
    if(($this->page + 1) > $this->lastPage) {
      $next['url'] = null;
    }

    return $next;
  }

  public function prev() {

    $this->setQuery('page','{n}');
    $pagingUrl = $this->parseQuery();

    $prev['url'] = str_replace('{n}', $this->page-1, $pagingUrl);
    if(($this->page - 1) < 1) {
      $prev['url'] = null;
    }

    return $prev;
  }

  public function paging() {

    $paging = array();
    $this->setQuery('page','{n}');
    $pagingUrl = $this->parseQuery();

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

  public function build($onlyData = false) {

    $data = array(
      'page' => $this->page,
      'lastPage' => $this->getLastPage(),
      'total' => $this->getTotal(),
      'paging' => $this->paging(),
      'next' => $this->next(),
      'prev' => $this->prev(),
      'data' => $this->getPaginationData()
    );

    if($onlyData) {
      return $data;
    }

    return array(
      '_pagination' => $data
    );

  }

  public function buildPermissionData($onlyData = false) {
    
    $data = array(
      'page' => $this->page,
      'lastPage' => $this->getLastPage(),
      'total' => $this->getTotal(),
      'paging' => $this->paging(),
      'next' => $this->next(),
      'prev' => $this->prev(),
      'data' => $this->getPermissionPaginationData()
    );

    if($onlyData) {
      return $data;
    }

    return array(
      '_pagination' => $data
    );


  }

}

?>