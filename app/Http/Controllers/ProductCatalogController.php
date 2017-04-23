<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
// use App\library\filterHelper;
// use App\library\url;
// use App\library\cache;
use Redirect;
use Session;

class ProductCatalogController extends Controller
{
  public function menu() {
    
    $model = Service::loadModel('ProductCatalog')->find($this->param['id']);

    // $image = $model->getRelatedData('Image',array(
    //   'first' => true
    // ));

    $this->data = $model->modelData->build();

    return $this->view('pages.product_catalog.menu');

  }

  public function add() {
    $model = Service::loadModel('ProductCatalog');

    $products = Service::loadModel('Product')
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', 'products.id')
    ->where('shop_relate_to.model','like','Product')
    ->where('shop_relate_to.shop_id','=',request()->get('shopId'))
    ->select('products.id','name')
    ->orderBy('products.name','asc')
    ->get();

    $_products = array();
    foreach ($products as $products) {
      $_products[$products['id']] = $products['name'];
    }

    $model->formHelper->setData('products',$_products);

    $this->data = $model->formHelper->build();

    return $this->view('pages.product_catalog.form.product_catalog_add');
  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('ProductCatalog');

    $request->request->add(['ShopRelateTo' => array('shop_id' => request()->get('shopId'))]);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ข้อมูลถูกเพิ่มแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/manage/product_catalog');
    }else{
      return Redirect::back();
    }

  }

  public function edit() {
    
    $model = Service::loadModel('ProductCatalog')->find($this->param['id']);

    $model->formHelper->loadData(array(
      'json' => array('Image','Tagging')
    ));

    // $products = Service::loadModel('Product')
    // ->join('shop_relate_to', 'shop_relate_to.model_id', '=', 'products.id')
    // ->where('shop_relate_to.model','like','Product')
    // ->where('shop_relate_to.shop_id','=',request()->get('shopId'))
    // ->select('products.id','name')
    // ->orderBy('products.name','asc')
    // ->get();

    // $_products = array();
    // foreach ($products as $products) {
    //   $_products[$products['id']] = $products['name'];
    // }

    // $productsInCatalog = $model->getRelatedData('ProductToProductCatalog',array(
    //   'fields' => array('product_id')
    // ));

    // $ProductToProductCatalog = array();
    // if(!empty($productsInCatalog)) {
    //   foreach ($productsInCatalog as $value) {
    //     $ProductToProductCatalog['product_id'][] = $value->product->id;
    //   }
    // }

    // $model->formHelper->setFormData('ProductToProductCatalog',$ProductToProductCatalog);

    // $model->formHelper->setData('products',$_products);

    $this->data = $model->formHelper->build();

    return $this->view('pages.product_catalog.form.product_catalog_edit');

  }

  public function editingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('ProductCatalog')->find($this->param['id']);

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/manage/product_catalog');
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
    ->select('products.id','name')
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

    return $this->view('pages.product_catalog.form.product_in_catalog_edit');

  }

  public function productCatalogEditingSubmit() {

    $model = Service::loadModel('ProductCatalog')->find($this->param['id']);

    $options = array(
      'value' => request()->get('ProductToProductCatalog')
    );

    if(Service::loadModel('ProductToProductCatalog')->__saveRelatedData($model,$options)) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/manage/product_catalog');
    }else{
      return Redirect::back();
    }

  }

}
