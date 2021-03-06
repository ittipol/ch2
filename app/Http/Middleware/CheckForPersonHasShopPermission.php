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
      $role = null;
      $permissions = null;

      $pages = array(
        'shop.index' => true,
        'shop.about' => true,
        'shop.overview' => array(
          'permission' => true,
        ),
        'shop.setting' => array(
          'permission' => true
        ),
        'shop.delete' => array(
          'permission' => true,
          'role' => 'admin'
        ),
        'shop.edit.shop_name' => array(
          'permission' => 'edit'
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
        'shop.job.list' => true,
        'shop.job.detail' => array(
          'modelName' => 'Job'
        ),
        'shop.job.manage' => array(
          'permission' => true
        ),
        'shop.job.add' => array(
          'permission' => 'add'
        ),
        'shop.job.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Job'
        ),
        'shop.job.delete' => array(
          'permission' => 'delete',
          'modelName' => 'Job'
        ),
        'shop.job.applying_list' => array(
          'permission' => true
        ),
        'shop.job.applying_detail' => array(
          'permission' => true,
          'modelName' => 'PersonApplyJob'
        ),
        'shop.job.applying.accept' => array(
          'permission' => true,
          'modelName' => 'PersonApplyJob'
        ),
        'shop.job.applying.passed' => array(
          'permission' => true,
          'modelName' => 'PersonApplyJob'
        ),
        'shop.job.applying.not_pass' => array(
          'permission' => true,
          'modelName' => 'PersonApplyJob'
        ),
        'shop.job.applying.canceled' => array(
          'permission' => true,
          'modelName' => 'PersonApplyJob'
        ),
        'shop.job.applying.new_message' => array(
          'permission' => true,
          'modelName' => 'PersonApplyJob'
        ),
        'shop.job.applying.message_reply' => array(
          'permission' => true,
          'modelName' => 'Message'
        ),
        // 'shop.branch.manage' => array(
        //   'permission' => true
        // ),
        // 'shop.branch.list' => true,
        // 'shop.branch.detail' => array(
        //   'modelName' => 'Branch'
        // ),
        // 'shop.branch.add' => array(
        //   'permission' => 'add'
        // ),
        // 'shop.branch.edit' => array(
        //   'permission' => 'edit',
        //   'modelName' => 'Branch'
        // ),
        // 'shop.branch.delete' => array(
        //   'permission' => 'delete',
        //   'modelName' => 'Branch'
        // ),
        'shop.product.manage.menu' => array(
          'permission' => 'edit',
          'modelName' => 'Product'
        ),
        'shop.advertising.list' => true,
        'shop.advertising.detail' => array(
          'modelName' => 'Advertising'
        ),
        'shop.advertising.manage' => array(
          'permission' => true
        ),
        'shop.advertising.add' => array(
          'permission' => 'add'
        ),
        'shop.advertising.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Advertising'
        ),
        'shop.advertising.delete' => array(
          'permission' => 'delete',
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
        'shop.order.cancel' => array(
          'permission' => true,
          'modelName' => 'Order'
        ),
        'shop.order.delete' => array(
          'permission' => 'delete',
          'modelName' => 'Order'
        ),
        'shop.product.list' => true,
        'shop.product.detail' => array(
          'modelName' => 'Product'
        ),
        'shop.product.manage' => array(
          'permission' => true
        ),
        'shop.product.add' => array(
          'permission' => 'add'
        ),
        'shop.product.edit' => array(
          'permission' => 'edit',
          'modelName' => 'Product'
        ),
        'shop.product.delete' => array(
          'permission' => 'delete',
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
        'shop.product_discount.delete' => array(
          'permission' => 'delete',
          'modelName' => 'ProductDiscount',
          'parent' => array(
            'modelName' => 'Product',
            'param' => 'product_id'
          )
        ),
        'shop.payment_method.list' => true,
        'shop.payment_method.manage' => array(
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
        'shop.shipping_method.manage' => array(
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
        'shop.order.payment.seller_confirm' => array(
          'permission' => 'edit',
          'modelName' => 'Order'
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
        ),
        'shop.product_catalog.manage' => array(
          'permission' => true
        ),
        'shop.product_catalog.menu' => array(
          'permission' => 'edit',
          'modelName' => 'ProductCatalog'
        ),
        'shop.product_catalog' => true,
        'shop.product_catalog.list' => array(
          'modelName' => 'ProductCatalog'
        ),
        'shop.product_catalog.add' => array(
          'permission' => 'add',
          'modelName' => 'ProductCatalog'
        ),
        'shop.product_catalog.edit' => array(
          'permission' => 'edit',
          'modelName' => 'ProductCatalog'
        ),
        'shop.product_catalog.product_list.edit' => array(
          'permission' => 'edit',
          'modelName' => 'ProductCatalog'
        ),
        'shop.product_catalog.delete' => array(
          'permission' => 'delete',
          'modelName' => 'ProductCatalog'
        ),
        'shop.product_catalog.delete.product' => array(
          'permission' => 'delete',
          'modelName' => 'ProductCatalog'
        ),
        'shop.timeline.post' => array(
          'permission' => 'add',
        ),
        'shop.timeline.pinned.cancel' => array(
          'permission' => 'edit',
          'modelName' => 'Timeline'
        ),
        'shop.timeline.delete' => array(
          'permission' => 'delete',
          'modelName' => 'Timeline'
        ),
        'shop.product_option' => array(
          'permission' => true,
          'modelName' => 'Product'
        ),
        'shop.product_option.add' => array(
          'permission' => 'add',
          'parent' => array(
            'modelName' => 'Product',
            'param' => 'product_id'
          )
        ),
        'shop.product_option.edit' => array(
          'permission' => 'edit',
          'modelName' => 'ProductOption',
          'parent' => array(
            'modelName' => 'Product',
            'param' => 'product_id'
          )
        ),
        'shop.product_option.delete' => array(
          'permission' => 'delete',
          'modelName' => 'ProductOption',
          'parent' => array(
            'modelName' => 'Product',
            'param' => 'product_id'
          )
        ),
        'shop.product_option.value.add' => array(
          'permission' => 'add',
          'check' => array(
            'modelName' => 'ProductOption',
            'field' => 'product_id',
            'param' => 'product_id',
            'param_id' => 'product_option_id'
          ),
          'parent' => array(
            'modelName' => 'Product',
            'param' => 'product_id'
          )
        ),
        'shop.product_option.value.edit' => array(
          'permission' => 'edit',
          'modelName' => 'ProductOptionValue',
          'check' => array(
            'modelName' => 'ProductOption',
            'field' => 'product_id',
            'param' => 'product_id',
            'param_id' => 'product_option_id'
          ),
          'parent' => array(
            'modelName' => 'Product',
            'param' => 'product_id'
          )
        ),
        'shop.product_option.value.delete' => array(
          'permission' => 'delete',
          'modelName' => 'ProductOptionValue',
          'check' => array(
            'modelName' => 'ProductOption',
            'field' => 'product_id',
            'param' => 'product_id',
            'param_id' => 'product_option_id'
          ),
          'parent' => array(
            'modelName' => 'Product',
            'param' => 'product_id'
          )
        )
      );

      if(empty($name) || !isset($pages[$name])) {
        return $this->errorPage('ไม่อนุญาตให้เข้าถึงหน้านี้ได้');
      }

      $shopId = Service::loadModel('Slug')
      ->where('slug','like',$request->shopSlug)
      ->select('model_id')
      ->first()
      ->model_id;

      $person = Service::loadModel('PersonToShop')->getData(array(
        'conditions' => array(
          ['created_by','=',session()->get('Person.id')],
          ['shop_id','=',$shopId],
        ),
        'fields' => array('role_id'),
        'first' => true
      ));

      if(!empty($person)) {
        $permissions = $person->role->getPermission();
        $role = $person->role->name;
      }

      if(!is_array($pages[$name]) && ($pages[$name] == true)) {
        $request->attributes->add([
          // 'shopRole' => $role,
          'shopPermission' => $permissions,
        ]);

        return $next($request);
      }

      //
      $passed = false;

      // check permission
      if(!empty($pages[$name]['permission'])) {

        if(($pages[$name]['permission'] === true) && empty($permissions)) {
          return $this->errorPage('ไม่อนุญาตให้แก้ไขร้านค้านี้ได้');
        }elseif(($pages[$name]['permission'] !== true) && empty($permissions[$pages[$name]['permission']])) {
          return $this->errorPage('ไม่อนุญาตให้แก้ไขร้านค้านี้ได้');
        }

        if(!empty($pages[$name]['role']) && ($pages[$name]['role'] != $role)) {
          return $this->errorPage('ไม่อนุญาตให้แก้ไขร้านค้านี้ได้');
        }

        $passed = true;

      }

      // check data owner
      if(!empty($request->id)) {

        $_model = Service::loadModel($pages[$name]['modelName'])->find($request->id);

        if(empty($_model)) {
          return $this->errorPage('ไม่พบข้อมูลนี้ในร้านค้า');
        }

        if(Schema::hasColumn($_model->getTable(), 'shop_id') && ($_model->shop_id == $shopId)) {
          $passed = true;
        }elseif(Schema::hasColumn($_model->getTable(), 'model') && Schema::hasColumn($_model->getTable(), 'model_id')) {

          $__model = Service::loadModel($_model->model);

          if(($_model->model == 'Shop') && ($_model->model_id == $shopId)) {
            $passed = true;
          }
          // elseif(Schema::hasColumn($__model->getTable(), 'shop_id') && ($__model->select('shop_id')->find($_model->model_id)->shop_id == $shopId)) {
          //   $passed = true;
          //   dd('Check::debug');
          // }
          else{
            $passed = Service::loadModel('ShopRelateTo')
            ->select('shop_id')
            ->where([
              ['model','like',$_model->model],
              ['model_id','=',$_model->model_id],
              ['shop_id','=',$shopId],
            ])->exists();
          }

        }else{
          $passed = Service::loadModel('ShopRelateTo')
          ->select('shop_id')
          ->where([
            ['model','like',$pages[$name]['modelName']],
            ['model_id','=',$request->id],
            ['shop_id','=',$shopId],
          ])->exists();
        }

      }

      if(!empty($pages[$name]['parent'])) {
        $passed = Service::loadModel('ShopRelateTo')
        ->select('shop_id')
        ->where([
          ['model','like',$pages[$name]['parent']['modelName']],
          ['model_id','=',$request->{$pages[$name]['parent']['param']}],
          ['shop_id','=',$shopId],
        ])->exists(); 
      }

      if(!empty($pages[$name]['check'])) {
        $passed = Service::loadModel($pages[$name]['check']['modelName'])
        ->select('id')
        ->where([
          ['id','=',$request->{$pages[$name]['check']['param_id']}],
          [$pages[$name]['check']['field'],'=',$request->{$pages[$name]['check']['param']}],
        ])->exists();
      }

      if(!$passed) {
        return $this->errorPage('ไม่พบข้อมูลนี้ในร้านค้า');
      }

      $request->attributes->add([
        // 'shopRole' => $role,
        'shopPermission' => $permissions,
      ]);

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
