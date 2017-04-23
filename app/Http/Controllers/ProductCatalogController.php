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
    dd('mm');
  }

  public function add() {
    $model = Service::loadModel('ProductCatalog');

    // Get Products
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
      return Redirect::to('shop/'.request()->shopSlug.'/manage/product/catalog');
    }else{
      return Redirect::back();
    }

  }

  public function edit() {
    dd('mm');
  }

}
