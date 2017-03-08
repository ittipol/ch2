<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use App\Models\Slug;
use App\Models\PersonToShop;
use App\library\message;
use App\library\service;
use Closure;
use Route;

class CheckForPersonHasShopPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $name = Route::currentRouteName();

      $pages = array(
        'shop.manage' => array(
          'permission' => true
        ),
        'shop.setting' => array(
          'permission' => true
        ),
        'shop.edit.description' => array(
          'permission' => 'edit'
        ),
        'shop.edit.address' => array(
          'permission' => 'edit'
        ),
        'shop.edit.contact' => array(
          'permission' => 'edit'
        ),
        'shop.edit.opening_hours' => array(
          'permission' => 'edit',
          'modelName' => false
        ),
        'shop.job' => array(
          'permission' => true
        ),
        'shop.job.add' => array(
          'permission' => 'add'
        ),
        'shop.job.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Job'
        ),
        'shop.job.apply_list' => array(
          'permission' => true
        ),
        'shop.job.apply_detail' => array(
          'permission' => true
        ),
        'shop.branch' => array(
          'permission' => true
        ),
        'shop.branch.add' => array(
          'permission' => 'add'
        ),
        'shop.branch.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Branch'
        ),
        'shop.product.menu' => array(
          'permission' => 'edit',
          'modelName' => 'Product'
        ),
        'shop.advertising' => array(
          'permission' => true
        ),
        'shop.advertising.add' => array(
          'permission' => 'add'
        ),
        'shop.advertising.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Advertising'
        ),
        'shop.product' => array(
          'permission' => true
        ),
        'shop.product.add' => array(
          'permission' => 'add'
        ),
        'shop.product.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Product'
        ),
        'shop.product_status.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Product'
        ),
        'shop.product_specification.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Product'
        ),
        'shop.product_category.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Product'
        ),
        'shop.product_stock.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Product'
        ),
        'shop.product_price.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Product'
        ),
        'shop.product_notification.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Product'
        ),
        'shop.product_shipping.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Product'
        ),
        'shop.product_sale_promotion' => array(
          'permission' => true
        ),
        'shop.product_discount.add' => array(
          'permission' => 'add',
          'check' => array(
            'modelName' => 'Product',
            'param' => 'product_id'
          )
        ),
      );

      if(empty($name) || empty($pages[$name])) {
        return $this->errorPage('ไม่อนุญาตให้เข้าถึงหน้านี้ได้');
      }

      $personToShop = new PersonToShop;
      $person = $personToShop->getData(array(
        'conditions' => array(
          ['person_id','=',session()->get('Person.id')],
          ['shop_id','=',Slug::where('slug','like',$request->shopSlug)->select('model_id')->first()->model_id],
        ),
        'fields' => array('role_id'),
        'first' => true
      ));

      if(empty($person)) {
        return $this->errorPage('ไม่อนุญาตให้แก้ไขร้านค้านี้ได้');
      }

      $permissions = $person->role->getPermission();

      if(!$pages[$name]['permission'] && empty($permissions[$pages[$name]['permission']])) {
        return $this->errorPage('ไม่อนุญาตให้แก้ไขร้านค้านี้ได้');
      }

      if((!empty($pages[$name]['modelName'])) && ($pages[$name]['permission'] == 'edit')) {
   
        if(empty($request->id)) {
          return redirect('home');
        }

        $relatedData = Service::loadModel('ShopRelateTo')
        ->select('shop_id')
        ->where([
          ['model','like',$pages[$name]['modelName']],
          ['model_id','=',$request->id]
        ])->exists();

        if(empty($relatedData)) {
          return $this->errorPage('ไม่พบข้อมูลนี้ในร้านค้า');
        }

        $model = Service::loadModel($pages[$name]['modelName'])->select('id')->find($request->id);

        if(empty($model)) {
          return $this->errorPage('ขออภัย ไม่สามารถแก้ไขข้อมูลนี้ได้ หรือข้อมูลนี้อาจถูกลบแล้ว');
        }

      }

      // page level
      // who can access in this page?
      // admin = 1, can access all page
      // if(level <= accessLevel)
      // have 4 levels
      // just concept

      return $next($request);
    }

    private function errorPage($message) {
      return response(view('errors.error',array(
        'error' => array(
          'message' => $message
        ))
      ));
    }

}
