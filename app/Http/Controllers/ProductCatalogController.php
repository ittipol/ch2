<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
use App\library\filterHelper;
use App\library\url;
// use App\library\cache;
use Redirect;
use Session;

class ProductCatalogController extends Controller
{
  public function listView() {

    // $productCatalogs = ProductCatalog::join('shop_relate_to', 'shop_relate_to.model_id', '=', 'product_catalogs.id')
    // ->where('shop_relate_to.model','like','ProductCatalog')
    // ->where('shop_relate_to.shop_id','=',request()->get('shopId'))
    // ->orderBy('product_catalogs.created_at','desc');

    $model = Service::loadModel('ProductCatalog');
    $filterHelper = new FilterHelper($model);

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $filters = '';
    if(!empty($this->query['fq'])) {
      $filters = $this->query['fq'];
    }

    $sort = '';
    if(!empty($this->query['sort'])) {
      $sort = $this->query['sort'];
    }

    $filterHelper->setFilters($filters);
    $filterHelper->setSorting($sort);

    $conditions = $filterHelper->buildFilters();
    $order = $filterHelper->buildSorting();

    $conditions[] = array(
      array('shop_relate_to.model','like','ProductCatalog'),
      array('shop_relate_to.shop_id','=',request()->get('shopId'))
    );

    $criteria = array();

    $criteria = array_merge($criteria,array(
      'joins' => array('shop_relate_to', 'shop_relate_to.model_id', '=', 'product_catalogs.id')
    ));

    $criteria = array_merge($criteria,array(
      'conditions' => $conditions
    ));

    if(!empty($order)) {
      $criteria = array_merge($criteria,$order);
    }

    $model->paginator->criteria($criteria);
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('shop/'.request()->shopSlug.'/product');
    $model->paginator->setUrl('shop/'.request()->shopSlug.'/product/{id}','detailUrl');
    $model->paginator->setQuery('sort',$sort);
    $model->paginator->setQuery('fq',$filters);

    $searchOptions = array(
      'filters' => $filterHelper->getFilterOptions(),
      'sort' => $filterHelper->getSortingOptions()
    );

    // $displayingFilters = array(
    //   'filters' => $filterHelper->getDisplayingFilterOptions(),
    //   'sort' => $filterHelper->getDisplayingSorting()
    // );

    $this->data = $model->paginator->build();
    $this->setData('searchOptions',$searchOptions);
    // $this->setData('displayingFilters',$displayingFilters);
    
    return $this->view('pages.product_catalog.list');

  }

  public function productListView() {

    $productCatalog = Service::loadModel('ProductCatalog')->find($this->param['id']);

    $model = Service::loadModel('Product');
    $filterHelper = new FilterHelper($model);

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $filters = '';
    if(!empty($this->query['fq'])) {
      $filters = $this->query['fq'];
    }

    $sort = '';
    if(!empty($this->query['sort'])) {
      $sort = $this->query['sort'];
    }

    $filterHelper->setFilters($filters);
    $filterHelper->setSorting($sort);

    $conditions = $filterHelper->buildFilters();
    $order = $filterHelper->buildSorting();

    $conditions[] = array(
      array('product_to_product_catalogs.product_catalog_id','=',$productCatalog->id),
    );

    $criteria = array();

    $criteria = array_merge($criteria,array(
      'joins' => array('product_to_product_catalogs', 'product_to_product_catalogs.product_id', '=', 'products.id')
    ));

    $criteria = array_merge($criteria,array(
      'conditions' => $conditions
    ));

    if(!empty($order)) {
      $criteria = array_merge($criteria,$order);
    }

    $model->paginator->criteria($criteria);
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('shop/'.request()->shopSlug.'/product');
    $model->paginator->setUrl('shop/'.request()->shopSlug.'/product/{id}','detailUrl');
    $model->paginator->setQuery('sort',$sort);
    $model->paginator->setQuery('fq',$filters);

    $searchOptions = array(
      'filters' => $filterHelper->getFilterOptions(),
      'sort' => $filterHelper->getSortingOptions()
    );

    // $displayingFilters = array(
    //   'filters' => $filterHelper->getDisplayingFilterOptions(),
    //   'sort' => $filterHelper->getDisplayingSorting()
    // );

    $this->data = $model->paginator->build();
    $this->setData('productCatalog',$productCatalog->buildModelData());
    $this->setData('searchOptions',$searchOptions);
    // $this->setData('displayingFilters',$displayingFilters);
    $this->setData('imageUrl',$this->getImage());
    
    return $this->view('pages.product_catalog.product_list');

  }

  public function menu() {

    $url = new Url;
    
    $model = Service::loadModel('ProductCatalog')->find($this->param['id']);

    $products = Service::loadModel('Product')
    ->join('product_to_product_catalogs', 'product_to_product_catalogs.product_id', '=', 'products.id')
    ->where('product_to_product_catalogs.product_catalog_id','=',$model->id)
    ->select('products.id','products.name')
    ->orderBy('products.name','asc');

    $_products = array();
    if($products->exists()) {
      foreach ($products->get() as $product) {
        $_products[] = array(
          'name' => $product->name,
          'detailUrl' => $url->url('shop/'.$this->param['shopSlug'].'/product/'.$product->id),
          'deleteUrl' => $url->url('shop/'.$this->param['shopSlug'].'/product_catalog/delete/product/'.$model->id.'/product_id:'.$product->id),
        );
      }
    }

    $this->data = $model->modelData->build();
    $this->setData('totalProduct',$model->getTotalProductInCatalog());
    $this->setData('products',$_products);

    return $this->view('pages.product_catalog.menu');

  }

  public function add() {
    $model = Service::loadModel('ProductCatalog');
    $this->data = $model->formHelper->build();
    return $this->view('pages.product_catalog.form.product_catalog_add');
  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('ProductCatalog');

    $request->request->add(['ShopRelateTo' => array('shop_id' => request()->get('shopId'))]);

    if($model->fill($request->all())->save()) {

      session()->flash('product_catalog_added', true);

      MessageHelper::display('ข้อมูลถูกเพิ่มแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/product_catalog/product_list/edit/'.$model->id);
      // return Redirect::to('shop/'.request()->shopSlug.'/manage/product_catalog');
    }else{
      return Redirect::back();
    }

  }

  public function edit() {
    
    $model = Service::loadModel('ProductCatalog')->find($this->param['id']);

    $model->formHelper->loadData(array(
      'json' => array('Image','Tagging')
    ));

    $this->data = $model->formHelper->build();

    return $this->view('pages.product_catalog.form.product_catalog_edit');

  }

  public function editingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('ProductCatalog')->find($this->param['id']);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/manage/product_catalog/'.$this->param['id']);
    }else{
      return Redirect::back();
    }

  }

  public function productCatalogEdit() {

    $model = Service::loadModel('ProductCatalog')->find($this->param['id']);

    $products = Service::loadModel('Product')
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', 'products.id')
    ->where('shop_relate_to.model','like','Product')
    ->where('shop_relate_to.shop_id','=',request()->get('shopId'))
    ->select('products.id','products.name')
    ->orderBy('products.name','asc')
    ->get();

    $_products = array();
    foreach ($products as $products) {
      $_products[$products['id']] = $products['name'];
    }

    $productsInCatalog = $model->getRelatedData('ProductToProductCatalog',array(
      'fields' => array('product_id')
    ));

    $ProductToProductCatalog = array();
    if(!empty($productsInCatalog)) {
      foreach ($productsInCatalog as $value) {
        $ProductToProductCatalog['product_id'][] = $value->product->id;
      }
    }

    $model->formHelper->setFormData('ProductToProductCatalog',$ProductToProductCatalog);
    $model->formHelper->setData('products',$_products);

    $this->data = $model->formHelper->build();

    return $this->view('pages.product_catalog.form.product_list_edit');

  }

  public function productCatalogEditingSubmit() {

    $model = Service::loadModel('ProductCatalog')->find($this->param['id']);

    $options = array(
      'value' => request()->get('ProductToProductCatalog')
    );

    if(Service::loadModel('ProductToProductCatalog')->__saveRelatedData($model,$options)) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/manage/product_catalog/'.$this->param['id']);
    }else{
      return Redirect::back();
    }

  }

  public function delete() {

    $model = Service::loadModel('ProductCatalog')->find($this->param['id']);

    if($model->delete()) {
      MessageHelper::display('ข้อมูลถูกลบแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถลบข้อมูลนี้ได้','error');
    }

    return Redirect::to('shop/'.request()->shopSlug.'/manage/product_catalog/');
  }

  public function deleteProduct() {
    
    $model = Service::loadModel('ProductToProductCatalog')->where([
      ['product_catalog_id','=',$this->param['id']],
      ['product_id','=',$this->param['product_id']]
    ]);

    if(!$model->exists()) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศนี้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    if($model->delete()) {
      MessageHelper::display('ข้อมูลถูกลบแล้ว','success');
    }else{
      MessageHelper::display('ไม่สามารถลบข้อมูลนี้ได้','error');
    }

    return Redirect::to('shop/'.request()->shopSlug.'/manage/product_catalog/'.$this->param['id']);
  }

}
