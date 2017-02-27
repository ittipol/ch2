<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\message;
use App\library\cache;
use Redirect;
use Session;

class ProductController extends Controller
{
  public function __construct() { 
    parent::__construct();
  }
// ข้อความถึงผู้ขาย:
  // การรับประกัน
  // public function index() {
  //   dd('pd index');
  // }

  public function detail() {
    dd('pd detail');
  }

  public function menu() {

    // ผู้ให้บริการขนส่ง
    // ไปรษณีย์ไทย
    // Nim Express
    // DHL
    // UPS
    // Kerry Express
    // ระบบขนส่งของทางร้าน

    $cache = new Cache;

    $model = Service::loadModel('Product')->find($this->param['id']);

    $image = $model->getModelRelationData('Image',array(
      'first' => true
    ));

    $imageUrl = '/images/common/no-img.png';
    if(!empty($image)) {
      $imageUrl = $cache->getCacheImageUrl($image,'list');
    }

    $this->data = $model->modelData->build();
    $this->setData('imageUrl',$imageUrl);

    $this->setData('productEditUrl',request()->get('shopUrl').'product_edit/'.$model->id);
    $this->setData('productSpecificationEditUrl',request()->get('shopUrl').'product_specification_edit/'.$model->id);
    $this->setData('productCategoryEditUrl',request()->get('shopUrl').'product_category_edit/'.$model->id);

    return $this->view('pages.product.menu');

  }

  public function add() {

    $model = Service::loadModel('Product');

    $this->data = $model->formHelper->build();

    return $this->view('pages.product.form.product_add');
  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Product');

    $request->request->add(['ShopRelateTo' => array('shop_id' => request()->get('shop')->id)]);

    if($model->fill($request->all())->save()) {
      Message::display('ข้อมูลถูกเพิ่มแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/product/'.$model->id);
    }else{
      return Redirect::back();
    }

  }

  public function edit() {

    $model = Service::loadModel('Product')->find($this->param['id']);

    $model->formHelper->loadData(array(
      'json' => array('Image','Tagging')
    ));

    $this->data = $model->formHelper->build();

    return $this->view('pages.product.form.product_edit');

  }

  public function editingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Product')->find($this->param['id']);

    if($model->fill($request->all())->save()) {
      Message::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/product/'.$model->id);
    }else{
      return Redirect::back();
    }
  }

  public function specificationEdit() {

    $model = Service::loadModel('Product')->find($this->param['id']);

    $model->formHelper->loadFieldData('WeightUnit',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'weigthUnits',
      'order' => array(
        array('id','ASC')
      )
    ));

    $model->formHelper->loadFieldData('LengthUnit',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'lengthUnits',
      'order' => array(
        array('id','ASC')
      )
    ));

    $this->data = $model->formHelper->build();

    return $this->view('pages.product.form.specification_edit');

  }

  public function specificationEditingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Product')->find($this->param['id']);

    if($model->fill($request->all())->save()) {
      Message::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/product/'.$model->id);
    }else{
      return Redirect::back();
    }

  }

  public function categoryEdit() {

    $model = Service::loadModel('Product')->find($this->param['id']);

    $productToCategory = Service::loadModel('ProductToCategory')
    ->where('product_id','=',$this->param['id'])
    ->select('category_id')
    ->first();

    $categoryId = null;
    $categoryPaths = array();
    if(!empty($productToCategory)) {

      $categoryId = $productToCategory->category_id;
      
      $paths = Service::loadModel('CategoryPath')->where('category_id','=',$categoryId)->get();

      foreach ($paths as $path) {

        $subCat = $path->path->where('parent_id','=',$path->path->id)->first();

        $hasChild = false;
        if(!empty($subCat)) {
          $hasChild = true;
        }

        $categoryPaths[] = array(
          'id' => $path->path->id,
          'name' => $path->path->name,
          'hasChild' => $hasChild
        );
      }
    }

    $this->data = $model->formHelper->build();
    $this->setData('categoryId',$categoryId);
    $this->setData('categoryPaths',json_encode($categoryPaths));

    return $this->view('pages.product.form.category_edit');

  }

  public function categoryEditingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Product')->find($this->param['id']);

    if($model->fill($request->all())->save()) {
      Message::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/product/'.$model->id);
    }else{
      return Redirect::back();
    }

  }

}
