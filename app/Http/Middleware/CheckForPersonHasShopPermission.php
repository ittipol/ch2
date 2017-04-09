<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use App\library\messageHelper;
use App\library\service;
use Closure;
use Route;
use Schema;

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

      // Need Chenge
      // Get Page name from DB

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
          'permission' => 'edit'
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
        'shop.job.applying_list' => array(
          'permission' => true
        ),
        'shop.job.applying_detail' => array(
          'permission' => true,
          'modelName' => 'PersonApplyJob'
        ),
        'shop.job.applying_message.add' => array(
          'permission' => true,
          'modelName' => 'PersonApplyJob'
        ),
        'shop.branch.manage' => array(
          'permission' => true
        ),
        'shop.branch.list' => true,
        'shop.branch.detail' => true,
        'shop.branch.add' => array(
          'permission' => 'add'
        ),
        'shop.branch.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Branch'
        ),
        'shop.branch.delete' => array(
          'permission' => 'delete',
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
        'shop.order' => array(
          'permission' => true
        ),
        'shop.order.detail' => array(
          'permission' => true,
          'modelName' => 'Order'
        ),
        'shop.order.confirm' => array(
          'permission' => true,
          'modelName' => 'Order'
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
        'shop.product_minimum.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Product'
        ),
        'shop.product_price.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Product'
        ),
        'shop.product_branch.edit' => array(
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
          'permission' => true,
          'modelName' => 'Product'
        ),
        'shop.product_discount.add' => array(
          'permission' => 'add',
          'parent' => array(
            'modelName' => 'Product',
            'param' => 'product_id'
          )
        ),
        'shop.product_discount.edit' => array(
          'permission' => 'edit',
          'modelName' => 'ProductDiscount',
          'parent' => array(
            'modelName' => 'Product',
            'param' => 'product_id'
          )
        ),
        'shop.payment_method' => array(
          'permission' => true
        ),
        'shop.payment_method.detail' => true,
        'shop.payment_method.add' => array(
          'permission' => 'add',
          'modelName' => 'PaymentMethod'
        ),
        'shop.payment_method.edit' => array(
          'permission' => 'edit',
          'modelName' => 'PaymentMethod'
        ),
        'shop.payment_method.delete' => array(
          'permission' => 'delete',
          'modelName' => 'PaymentMethod'
        ),
        'shop.shipping_method' => array(
          'permission' => true
        ),
        'shop.shipping_method.add' => array(
          'permission' => 'add',
          'modelName' => 'ShippingMethod'
        ),
        'shop.shipping_method.edit' => array(
          'permission' => 'edit',
          'modelName' => 'ShippingMethod'
        ),
        'shop.shipping_method.delete' => array(
          'permission' => 'delete',
          'modelName' => 'ShippingMethod'
        ),
        'shop.shipping_method.pickingup_item' => array(
          'permission' => 'add',
          'modelName' => 'ShippingMethod'
        ),
        'shop.order.payment.confirm' => array(
          'permission' => 'edit',
          'modelName' => 'Order'
        ),
        'shop.order.payment.detail' => array(
          'permission' => true,
          'modelName' => 'Order'
        ),
        'shop.order.status.update' => array(
          'permission' => 'edit',
          'modelName' => 'Order'
        )
      );

      if(empty($name) || !isset($pages[$name])) {
        return $this->errorPage('ไม่อนุญาตให้เข้าถึงหน้านี้ได้');
      }

      if(!empty($pages[$name]['permission'])) {

        $shopId = Service::loadModel('Slug')
        ->where('slug','like',$request->shopSlug)
        ->select('model_id')
        ->first()
        ->model_id;

        $person = Service::loadModel('PersonToShop')->getData(array(
          'conditions' => array(
            ['person_id','=',session()->get('Person.id')],
            ['shop_id','=',$shopId],
          ),
          'fields' => array('role_id'),
          'first' => true
        ));

        if(empty($person)) {
          return $this->errorPage('ไม่อนุญาตให้แก้ไขร้านค้านี้ได้');
        }

        $permissions = $person->role->getPermission();

        // permission check
        if(($pages[$name]['permission'] !== true) && empty($permissions[$pages[$name]['permission']])) {
          return $this->errorPage('ไม่อนุญาตให้แก้ไขร้านค้านี้ได้');
        }

        // data check
        if(!empty($request->id)) {

          $_model = Service::loadModel($pages[$name]['modelName']);

          if(empty($_model)) {
            return $this->errorPage('ไม่พบข้อมูลนี้');
          }

          $exists = true;
          if(Schema::hasColumn($_model->getTable(), 'shop_id')) {
            $exists = $_model->where([
              ['id','=',$request->id],
              ['shop_id','=',$shopId]
            ])->exists();
          }else{
            // check by ShopRelateTo model

            // has parent data
            // like ProductDiscouint has Product is parent
            // maybe need list of parent data and check here
            if(!empty($pages[$name]['parent'])) {
              $exists = Service::loadModel('ShopRelateTo')
              ->select('shop_id')
              ->where([
                ['model','like',$pages[$name]['parent']['modelName']],
                ['model_id','=',$request->{$pages[$name]['parent']['param']}],
                ['shop_id','=',$shopId],
              ])->exists();
            }else{
              $exists = Service::loadModel('ShopRelateTo')
              ->select('shop_id')
              ->where([
                ['model','like',$pages[$name]['modelName']],
                ['model_id','=',$request->id],
                ['shop_id','=',$shopId],
              ])->exists();
            }

          }

          if(!$exists) {
            return $this->errorPage('ไม่พบข้อมูลนี้');
          }

        }

      }

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
