<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\library\service;
use App\library\url;
use App\library\cache;
use App\library\stringHelper;
use App\library\currency;
use Route;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      view()->composer('pages.shop.layouts.header', function($view){

        $url = new Url;
        $string = new stringHelper;

        $shop = request()->get('shop');

        view()->share('_shop_permission',request()->get('shopPermission'));

        view()->share('_shop_id',$shop->id);
        view()->share('_shop_name',$shop->name);
        view()->share('_shop_short_description',$string->truncString($shop->description,250,true));
        view()->share('_shop_profileImage',$shop->getProfileImageUrl());
        view()->share('_shop_cover',$shop->getCoverUrl());
        view()->share('_shop_open_hours',$shop->getOpenHours());

      });

      // view()->composer('layouts.blackbox.components.global-nav', function($view){

      //   $view->with('_product_count',Service::loadModel('Product')->count());
      //   $view->with('_job_count',Service::loadModel('Job')->count());
      //   $view->with('_advertising_count',Service::loadModel('Advertising')->count());

      // });

      view()->composer('layouts.blackbox.components.global-header', function($view){

        $notificationModel = Service::loadModel('Notification');

        $notificationModel->clearNotify();

        $view->with('_notification_count',$notificationModel->countUnreadNotification());
        $view->with('_product_count',Service::loadModel('Cart')->productCount());

      });

      view()->composer('layouts.blackbox.components.global-cart', function($view){
        $view->with('_products',Service::loadModel('Cart')->getProducts());
      });

      view()->composer('layouts.blackbox.components.global-notification', function($view){
        $view->with('_notifications',Service::loadModel('Notification')->getUnreadNotification());
      });

      view()->composer('layouts.blackbox.components.global-account', function($view){
        
        if(Auth::check()){

          $url = new Url;

          $orderModel = Service::loadModel('Order');
          $slugModel = Service::loadModel('Slug');

          $records = Service::loadModel('PersonToShop')
          ->select(array('shop_id'))
          ->where('created_by','=',session()->get('Person.id'))
          ->get();

          $shops = array();
          foreach ($records as $record) {

            $shop = $record->shop;

            if(empty($shop)) {
              continue;
            }

            $slug = $slugModel->where(array(
              array('model','like','Shop'),
              array('model_id','=',$shop->id)
            ))->first()->slug;

            $shopUrl = $url->url('shop/'.$slug);

            $shops[] = array(
              'name' => $shop->name,
              'totalNewOrder' => $orderModel->where([
                ['shop_id','=',$shop->id],
                ['order_status_id','=',1]
              ])->count(),
              'url' => $shopUrl,
              'orderUrl' => $shopUrl.'order?fq=order_status_id:1&sort=created_at:desc'
            );

          }

          $view->with('_shops',$shops);

        }

      });
      
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
