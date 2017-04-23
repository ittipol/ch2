<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
use App\library\filterHelper;
use App\library\url;
use App\library\cache;
use Redirect;
use Session;

use App\library\notificationHelper;

class ProductController extends Controller
{
  public function __construct() { 
    parent::__construct();
  }

  private function _save($model,$attributes = array()) {

    if($model->fill($attributes)->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/manage/product/'.$model->id);
    }else{
      return Redirect::back();
    }

  }

  public function shelf() {

    $url = new Url;
    $cache = new Cache;

    $model = Service::loadModel('Product');
    $categoryModel = Service::loadModel('Category');

    $categories = $categoryModel->getSubCategories(null,false);

    $shelfs = array();
    foreach ($categories as $category) {

      $subCategories = $categoryModel->select('id','name')->where('parent_id','=',$category->id)->get();

      $_subCategories = array();
      foreach ($subCategories as $subCategory) {
        $_subCategories[] = array(
          'name' => $subCategory->name
        );
      }

      $categoryPaths = Service::loadModel('CategoryPath')
      ->where('path_id','=',$category->id)
      ->get();

      $ids = array();
      foreach ($categoryPaths as $categoryPath) {
        $ids[] = $categoryPath->category_id;
      }

      $products = $model
      ->join('product_to_categories', 'product_to_categories.product_id', '=', 'products.id')
      ->whereIn('product_to_categories.category_id',$ids);

      $total = $products->count('products.id');

      $products = $products
      ->select('products.*')
      ->orderBy('products.created_at','desc')
      ->take(3)
      ->get();

      $_products = array();
      foreach ($products as $product) {

        $image = $product->getRelatedData('Image',array(
          'first' => true
        ));

        $imageUrl = '/images/common/no-img.png';
        if(!empty($image)) {
          $imageUrl = $cache->getCacheImageUrl($image,'list');
        }

        $_products['items'][] = array_merge($product->buildPaginationData(),array(
          '_imageUrl' => $imageUrl,
          'detailUrl' => $url->setAndParseUrl('product/detail/{id}',array('id'=>$product->id))
        ));
        
      }

      if($total > 3) {
        $_products['all'] = array(
          'title' => '+'.($total-3)
        );
      }

      $shelfs[] = array(
        'categoryName' => $category->name,
        'subCategories' => $_subCategories,
        'products' => $_products,
        'total' => $total,
        'productShelfUrl' => $url->setAndParseUrl('product/shelf/{category_id}',array('category_id'=>$category->id)),
        'categoryUrl' => $url->setAndParseUrl('product/category/{category_id}',array('category_id'=>$category->id))
      );

    }

    $this->setData('shelfs',$shelfs);

    return $this->view('pages.product.shelf');

  }

  public function category() {

    $url = new Url;

    $model = Service::loadModel('Product');
    $categoryModel = Service::loadModel('Category');

    $categoryId = null;
    if(!empty($this->param['category_id'])) {
      $categoryId = $this->param['category_id'];
    }

    $categories = $categoryModel->getSubCategories($categoryId,false);

    if(empty($categories)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบหมวดสินค้า'
      );
      return $this->error();
    }

    $_categories = array();
    foreach ($categories as $category) {

      $subCategories = $categoryModel->select('id','name')->where('parent_id','=',$category->id)->get();

      $_subCategories = array();
      foreach ($subCategories as $subCategory) {
        $_subCategories[] = array(
          'name' => $subCategory->name,
          'url' =>  $url->setAndParseUrl('product/category/{category_id}',array('category_id'=>$subCategory->id))
        );
      }

      $_categories[] = array(
        'categoryName' => $category->name,
        'subCategories' => $_subCategories,
        'productShelfUrl' => $url->setAndParseUrl('product/shelf/{category_id}',array('category_id'=>$category->id)),
      );

    }

    $this->setData('categoryName',$categoryModel->getCategoryName($categoryId));
    $this->setData('categories',$_categories);
    $this->setData('productShelfUrl',$url->setAndParseUrl('product/shelf/{category_id}',array('category_id'=>$categoryId)));

    return $this->view('pages.product.category');

  }

  public function listView() {

    $model = Service::loadModel('Product');
    $categoryModel = Service::loadModel('Category');
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

    $categoryId = null;
    if(!empty($this->param['category_id'])) {
      $categoryId = $this->param['category_id'];
    }

    $categoryPaths = Service::loadModel('CategoryPath')->where('path_id','=',$categoryId)->get();

    $ids = array();
    foreach ($categoryPaths as $categoryPath) {
      $ids[] = $categoryPath->category_id;
    }

    $filterHelper->setFilters($filters);
    $filterHelper->setSorting($sort);

    $conditions = $filterHelper->buildFilters();
    $order = $filterHelper->buildSorting();

    if(!empty($ids)) {
      $conditions['in'][] = array('product_to_categories.category_id',$ids);
    }

    $criteria = array();

    $criteria = array_merge($criteria,array(
      'joins' => array('product_to_categories', 'product_to_categories.product_id', '=', 'products.id')
    ));

    $criteria = array_merge($criteria,array(
      'conditions' => $conditions
    ));

    if(!empty($order)) {
      $criteria = array_merge($criteria,$order);
    }

    $model->paginator->criteria($criteria);
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('product/shelf/'.$categoryId);
    $model->paginator->setUrl('product/detail/{id}','detailUrl');
    $model->paginator->setQuery('sort',$sort);
    $model->paginator->setQuery('fq',$filters);

    $categoryName = Service::loadModel('Category')->getCategoryName($categoryId);

    $title = '';
    if(!empty($categoryName)) {
      $title = $categoryName;
    }

    $searchOptions = array(
      'filters' => $filterHelper->getFilterOptions(),
      'sort' => $filterHelper->getSortingOptions()
    );

    $displayingFilters = array(
      'filters' => $filterHelper->getDisplayingFilterOptions(),
      'sort' => $filterHelper->getDisplayingSorting()
    );

    $parent = $categoryModel->getParentCategory($categoryId);
    
    $this->data = $model->paginator->build();
    $this->setData('title',$title);
    $this->setData('categories',$categoryModel->getCategoriesWithSubCategories($categoryId));
    $this->setData('searchOptions',$searchOptions);
    $this->setData('displayingFilters',$displayingFilters);
    $this->setData('categoryPaths',$categoryModel->getCategoryPaths($categoryId));
    $this->setData('parentCategoryName',$parent['name']);

    return $this->view('pages.product.list');

  }

  public function shopProductlistView() {

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
      array('shop_relate_to.model','like','Product'),
      array('shop_relate_to.shop_id','=',request()->get('shopId'))
    );

    $criteria = array();

    $criteria = array_merge($criteria,array(
      'joins' => array('shop_relate_to', 'shop_relate_to.model_id', '=', 'products.id')
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

    $displayingFilters = array(
      'filters' => $filterHelper->getDisplayingFilterOptions(),
      'sort' => $filterHelper->getDisplayingSorting()
    );

    $this->data = $model->paginator->build();
    $this->setData('searchOptions',$searchOptions);
    $this->setData('displayingFilters',$displayingFilters);

    return $this->view('pages.product.shop_product_list');

  }

  public function detail() {

    $url = new Url;

    $categoryModel = Service::loadModel('Category');

    $model = Service::loadModel('Product')->find($this->param['id']);

    if(empty($model)) {
      $this->error = array(
        'message' => 'ขออภัย ไม่พบประกาศนี้ หรือข้อมูลนี้อาจถูกลบแล้ว'
      );
      return $this->error();
    }

    $model->modelData->loadData(array(
      'json' => array('Image')
    ));

    // Get Shop Data
    $shop = $model->getRelatedData('ShopRelateTo',array(
      'first' => true,
      'fields' => array('shop_id')
    ))->shop;

    // Get Slug
    $slug = $shop->getRelatedData('Slug',array(
      'first' => true,
      'fields' => array('slug')
    ))->slug;

    $branchIds = $model->getRelatedData('RelateToBranch',array(
      'list' => 'branch_id',
      'fields' => array('branch_id'),
    ));

    $branches = array();
    if(!empty($branchIds)){
      $branches = Service::loadModel('Branch')
      ->select(array('id','name'))
      ->whereIn('id',$branchIds)
      ->get();
    }

    $branchLocations = array();
    $hasBranchLocation = false;
    foreach ($branches as $branch) {

      $address = $branch->modelData->loadAddress();

      if(!empty($address)){

        $hasBranchLocation = true;

        $graphics = json_decode($address['_geographic'],true);
        
        $branchLocations[] = array(
          'id' => $branch->id,
          'address' => $branch->name,
          'latitude' => $graphics['latitude'],
          'longitude' => $graphics['longitude'],
          'detailUrl' => $url->setAndParseUrl('shop/{shopSlug}/branch/{id}',array(
            'shopSlug' => $slug,
            'id' => $branch->id
          ))
        );
      }
    }

    $this->data = $model->modelData->build();

    $this->setData('shop',$shop->modelData->build(true));
    $this->setData('shopImageUrl',$shop->getProfileImageUrl());
    $this->setData('shopCoverUrl',$shop->getCoverUrl());
    $this->setData('shopUrl','shop/'.$slug);
    $this->setData('categoryPaths',$model->getCategoryPaths());

    $this->setData('branchLocations',json_encode($branchLocations));
    $this->setData('hasBranchLocation',$hasBranchLocation);

    return $this->view('pages.product.detail');

  }

  public function shopProductDetail() {

    $url = new Url;

    $model = Service::loadModel('Product')->find($this->param['id']);

    $model->modelData->loadData(array(
      'json' => array('Image')
    ));

    $branchIds = $model->getRelatedData('RelateToBranch',array(
      'list' => 'branch_id',
      'fields' => array('branch_id'),
    ));

    $branches = array();
    if(!empty($branchIds)){
      $branches = Service::loadModel('Branch')
      ->select(array('id','name'))
      ->whereIn('id',$branchIds)
      ->get();
    }

    $branchLocations = array();
    $hasBranchLocation = false;
    foreach ($branches as $branch) {

      $address = $branch->modelData->loadAddress();

      if(!empty($address)){

        $hasBranchLocation = true;

        $graphics = json_decode($address['_geographic'],true);
        
        $branchLocations[] = array(
          'id' => $branch->id,
          'address' => $branch->name,
          'latitude' => $graphics['latitude'],
          'longitude' => $graphics['longitude'],
          'detailUrl' => $url->setAndParseUrl('shop/{shopSlug}/branch/{id}',array(
            'shopSlug' => request()->shopSlug,
            'id' => $branch->id
          ))
        );
      }
    }

    $this->data = $model->modelData->build();

    // $this->setData('shop',$shop->modelData->build(true));
    // $this->setData('shopImageUrl',$shop->getProfileImageUrl());
    // $this->setData('shopCoverUrl',$shop->getCoverUrl());
    // $this->setData('shopUrl',request()->get('shopUrl'));

    $this->setData('categoryPaths',$model->getCategoryPaths());

    $this->setData('branchLocations',json_encode($branchLocations));
    $this->setData('hasBranchLocation',$hasBranchLocation);

    return $this->view('pages.product.shop_product_detail');

  }

  public function menu() {

    $cache = new Cache;

    $model = Service::loadModel('Product')->find($this->param['id']);

    $image = $model->getRelatedData('Image',array(
      'first' => true
    ));

    $imageUrl = '/images/common/no-img.png';
    if(!empty($image)) {
      $imageUrl = $cache->getCacheImageUrl($image,'list');
    }

    $this->data = $model->modelData->build();

    $this->setData('imageUrl',$imageUrl);

    // $this->setData('productDetailUrl','product/detail/'.$model->id);
    // $this->setData('productEditUrl',request()->get('shopUrl').'product/edit/'.$model->id);
    // $this->setData('productStatusUrl',request()->get('shopUrl').'product/status/edit/'.$model->id);
    // $this->setData('productSpecificationEditUrl',request()->get('shopUrl').'product/specification/edit/'.$model->id);
    // $this->setData('productCategoryEditUrl',request()->get('shopUrl').'product/category/edit/'.$model->id);
    // $this->setData('productMinimumEditUrl',request()->get('shopUrl').'product/minimum/edit/'.$model->id);
    // $this->setData('productStockEditUrl',request()->get('shopUrl').'product/stock/edit/'.$model->id);
    // $this->setData('productPriceEditUrl',request()->get('shopUrl').'product/price/edit/'.$model->id);
    // $this->setData('productSalePromotionUrl',request()->get('shopUrl').'product/sale_promotion/'.$model->id);
    // $this->setData('productShippingUrl',request()->get('shopUrl').'product/shipping/edit/'.$model->id);
    // $this->setData('productNotificationEditUrl',request()->get('shopUrl').'product/notification/edit/'.$model->id);
    // $this->setData('productBranchUrl',request()->get('shopUrl').'product/branch/'.$model->id);

    $this->setData('categoryPaths',$model->getCategoryPaths());

    return $this->view('pages.product.menu');

  }

  public function add() {
    $model = Service::loadModel('Product');
    $this->data = $model->formHelper->build();
    return $this->view('pages.product.form.product_add');
  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Product');

    $request->request->add(['ShopRelateTo' => array('shop_id' => request()->get('shopId'))]);

    if($model->fill($request->all())->save()) {

      session()->flash('product_added', true);

      MessageHelper::display('ข้อมูลถูกเพิ่มแล้ว','success');
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
    return $this->_save(Service::loadModel('Product')->find($this->param['id']),$request->all());
  }

  public function statusEdit() {

    $model = Service::loadModel('Product')->find($this->param['id']);

    $this->setData('_formModel',array(
      'modelName' => $model->modelName,
    ));

    $this->setData('_formData',array(
      'active' => $model->active
    ));

    return $this->view('pages.product.form.status_edit');

  }

  public function statusEditingSubmit(CustomFormRequest $request) {
    return $this->_save(Service::loadModel('Product')->find($this->param['id']),$request->all());
  }

  public function specificationEdit() {

    $model = Service::loadModel('Product')->find($this->param['id']);

    $model->formHelper->loadFieldData('WeightUnit',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'weightUnits',
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
    return $this->_save(Service::loadModel('Product')->find($this->param['id']),$request->all());
  }

  public function categoryEdit() {

    $model = Service::loadModel('Product')->find($this->param['id']);

    $productToCategory = Service::loadModel('ProductToCategory')
    ->where('product_id','=',$this->param['id'])
    ->select('category_id')
    ->first();

    $categoryId = null;
    if(!empty($productToCategory)) {
      $categoryId = $productToCategory->category_id;
    }

    $this->setData('_formData',array());

    $this->setData('_formModel',array(
      'modelName' => $model->modelName,
    ));

    $this->setData('categoryId',$categoryId);
    $this->setData('categoryPaths',json_encode($model->getCategoryPaths()));

    return $this->view('pages.product.form.category_edit');

  }

  public function categoryEditingSubmit(CustomFormRequest $request) {
    return $this->_save(Service::loadModel('Product')->find($this->param['id']),$request->all());
  }

  public function minimumEdit() {
    $model = Service::loadModel('Product')->find($this->param['id']);

    $this->setData('_formModel',array(
      'modelName' => $model->modelName,
    ));

    $this->setData('_formData',array(
      'minimum' => $model->minimum,
    ));

    return $this->view('pages.product.form.minimum_edit');
  }

  public function minimumEditingSubmit(CustomFormRequest $request) {
    return $this->_save(Service::loadModel('Product')->find($this->param['id']),$request->all());
  }

  public function stockEdit() {

    $model = Service::loadModel('Product')->find($this->param['id']);

    $this->setData('_formModel',array(
      'modelName' => $model->modelName,
    ));

    $this->setData('_formData',array(
      'quantity' => $model->quantity,
      'product_unit' => $model->product_unit
    ));

    return $this->view('pages.product.form.stock_edit');

  }

  public function stockEditingSubmit(CustomFormRequest $request) {
    return $this->_save(Service::loadModel('Product')->find($this->param['id']),$request->all());
  }

  public function priceEdit() {
    
    $model = Service::loadModel('Product')->find($this->param['id']);

    $this->setData('_formModel',array(
      'modelName' => $model->modelName,
    ));

    $this->setData('_formData',array(
      'price' => $model->price
    ));

    return $this->view('pages.product.form.price_edit');

  }

  public function priceEditingSubmit(CustomFormRequest $request) {
    return $this->_save(Service::loadModel('Product')->find($this->param['id']),$request->all());
  }

  public function shippingEdit() {

    $product = Service::loadModel('Product')->find($this->param['id']);

    $model = $product->getRelatedData('ProductShipping',array(
      'first' => true
    ));

    if(empty($model)) {
      $model = Service::loadModel('ProductShipping');
    }

    $this->setData('_formModel',array(
      'modelName' => $model->modelName,
    ));

    $this->setData('_formData', array_merge($model->formHelper->getFormData(),array(
      'shipping_calculate_from' => $product->shipping_calculate_from
    )));

    $model->formHelper->loadFieldData('ShippingCostCalCulateType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'ShippingCalTypes'
    ));

    $model->formHelper->loadFieldData('ProductShippingAmountType',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'productShippingAmountTypes'
    ));

    $model->formHelper->setData('operatorSigns',Service::loadModel('ProductShipping')->operatorSigns);

    $this->setData('_fieldData',$model->formHelper->getFieldData());

    return $this->view('pages.product.form.shipping_edit');

  }

  public function shippingEditingSubmit(CustomFormRequest $request) {

    $product = Service::loadModel('Product')->find($this->param['id']);

    $model = $product->getRelatedData('ProductShipping',array(
      'first' => true
    ));

    if(empty($model)) {
      $model = Service::loadModel('ProductShipping');
      $model->product_id = $product->id;
    }

    if($model->fill($request->all())->save()) {
      MessageHelper::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/product/'.$product->id);
    }else{
      return Redirect::back();
    }

  }

  public function notificationEdit() {
    
    $model = Service::loadModel('Product')->find($this->param['id']);

    $this->setData('_formModel',array(
      'modelName' => $model->modelName,
    ));

    $this->setData('_formData',array(
      'message_out_of_order' => $model->message_out_of_order
    ));

    return $this->view('pages.product.form.notification_edit');

  }
 
  public function notificationEditingSubmit(CustomFormRequest $request) {
    return $this->_save(Service::loadModel('Product')->find($this->param['id']),$request->all());
  }

  public function salePromotion() {

    $url = new Url;
    
    $model = Service::loadModel('Product')->find($this->param['id']);

    $promotion = $model->getPromotion();

    $_salePromotions = array();
    if(!empty($promotion)) {
      $_salePromotions[] = array(
        'active' => true,
        'data' => $promotion
      );
    }

    $salePromotions = $model->getRelatedData('ProductSalePromotion',array(
      'conditions' => array(
        array('date_start','>',date('Y-m-d H:i:s')),
      ),
      'order' => array('date_start','ASC'),
    ));

    if(!empty($salePromotions)) {
      foreach ($salePromotions as $salePromotion) {
        $_salePromotions[] = array(
          'active' => false,
          'remainingDays' => $salePromotion->calRemainingDays(),
          'data' => array_merge($salePromotion->buildModelData(),$salePromotion->{lcfirst($salePromotion->model)}->buildModelData()),
          'editUrl' => $url->setAndParseUrl(request()->get('shopUrl').'product_discount/edit/{id}/product_id:{product_id}',array(
            'id' => $salePromotion->model_id,
            'product_id' => $model->id
          )),
          'deleteUrl' => $url->setAndParseUrl(request()->get('shopUrl').'product_discount/delete/{id}/product_id:{product_id}',array(
            'id' => $salePromotion->model_id,
            'product_id' => $model->id
          )),
        );
      }
    }

    $this->setData('salePromotions',$_salePromotions);
    $this->setData('originalPrice',$model->getOriginalPrice(true));
    $this->setData('productDiscountAdd',request()->get('shopUrl').'product_discount/add/product_id:'.$model->id);

    return $this->view('pages.product.sale_promotion');

  }

  public function branchEdit() {

    $model = Service::loadModel('Product')->find($this->param['id']);

    $relateToBranch = $model->getRelatedData('RelateToBranch',array(
      'fields' => array('branch_id')
    ));

    $branches = array();
    if(!empty($relateToBranch)) {
      foreach ($relateToBranch as $value) {
        $branches['branch_id'][] = $value->branch->id;
      }
    }

    // Get Selected Branch
    $model->formHelper->setFormData('RelateToBranch',$branches);

    $model->formHelper->setData('branches',request()->get('shop')->getRelatedShopData('Branch'));

    $this->data = $model->formHelper->build();

    return $this->view('pages.product.form.branch_edit');

  }

  public function branchEditingSubmit(CustomFormRequest $request) {
    return $this->_save(Service::loadModel('Product')->find($this->param['id']),$request->all());
  }

}
