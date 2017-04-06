<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\library\service;
use App\library\url;
use App\library\cache;
use App\library\string;
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
      view()->composer('pages.shop.layouts.top_nav', function($view){

        // if(!empty(Route::current()->parameter('shopSlug'))) {

          $url = new Url;

          $slug = Route::current()->parameter('shopSlug');

          view()->share('_shop_setting_url',$url->url('shop/'.$slug.'/setting'));
          view()->share('_shop_product_url',$url->url('shop/'.$slug.'/product'));
          view()->share('_shop_job_url',$url->url('shop/'.$slug.'/job'));
          view()->share('_shop_advertising_url',$url->url('shop/'.$slug.'/advertising'));
          view()->share('_shop_branch_url',$url->url('shop/'.$slug.'/branch/manage'));

        // }

      });

      view()->composer('pages.product.layouts.top_nav', function($view){

        $url = new Url;

        $slug = Route::current()->parameter('shopSlug');

        view()->share('_shop_product_menu',$url->url('shop/'.$slug.'/product/'.Route::current()->parameter('id')));
        view()->share('_shop_product_url',$url->url('shop/'.$slug.'/product'));
        view()->share('_shop_job_url',$url->url('shop/'.$slug.'/job'));
        view()->share('_shop_advertising_url',$url->url('shop/'.$slug.'/advertising'));

      });

      view()->composer('pages.shipping_method.layouts.top_nav', function($view){

        $url = new Url;

        $slug = Route::current()->parameter('shopSlug');

        view()->share('_shop_shipping_method',$url->url('shop/'.$slug.'/shipping_method'));
        view()->share('_shop_product_url',$url->url('shop/'.$slug.'/product'));
        view()->share('_shop_job_url',$url->url('shop/'.$slug.'/job'));
        view()->share('_shop_advertising_url',$url->url('shop/'.$slug.'/advertising'));

      });

      view()->composer('pages.payment_method.layouts.top_nav', function($view){

        $url = new Url;

        $slug = Route::current()->parameter('shopSlug');

        view()->share('_shop_payment_method',$url->url('shop/'.$slug.'/payment_method'));
        view()->share('_shop_product_url',$url->url('shop/'.$slug.'/product'));
        view()->share('_shop_job_url',$url->url('shop/'.$slug.'/job'));
        view()->share('_shop_advertising_url',$url->url('shop/'.$slug.'/advertising'));

      });

      // view()->composer('layouts.blackbox.components.global-nav', function($view){

      //   if(Auth::check()){


      //     $url = new Url;

      //     $records = Service::loadModel('PersonToShop')
      //     ->select(array('shop_id'))
      //     ->where('person_id','=',session()->get('Person.id'))
      //     ->get();

      //     $slugModel = Service::loadModel('Slug');

      //     $shops = array();
      //     foreach ($records as $record) {

      //       $shop = $record->shop;

      //       $slug = $slugModel->where(array(
      //         array('model','like','Shop'),
      //         array('model_id','=',$shop->id)
      //       ))->first()->slug;

      //       $shops[] = array(
      //         'name' => $shop->name,
      //         'url' => $url->url('shop/'.$slug)
      //       );

      //     }

      //     $view->with('_shops',$shops);

      //   }

      // });
      
      view()->composer('pages.shop.layouts.header', function($view){

        // if(!empty(Route::current()->parameter('shopSlug'))) {

          $string = new String;
          $url = new Url;

          $slug = Route::current()->parameter('shopSlug');

          $shopId = Service::loadModel('Slug')
          ->where('slug','like',$slug)
          ->first()
          ->model_id;

          $shop = Service::loadModel('Shop')
          ->select('name','description','profile_image_id','cover_image_id')
          ->find($shopId);

          view()->share('_shop_id',$shopId);
          view()->share('_shop_name',$shop->name);
          view()->share('_shop_short_description',$string->subString($shop->description,250,true));
          view()->share('_shop_profileImage',$shop->getProfileImageUrl());
          view()->share('_shop_cover',$shop->getCoverUrl());

        // }

      });

      view()->composer('layouts.blackbox.components.global-header', function($view){

        $NotificationModel = Service::loadModel('Notification');

        $NotificationModel->clearNotify();

        $view->with('_notification_count',Service::loadModel('Notification')->countUnreadNotification());
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

          $records = Service::loadModel('PersonToShop')
          ->select(array('shop_id'))
          ->where('person_id','=',session()->get('Person.id'))
          ->get();

          $slugModel = Service::loadModel('Slug');

          $shops = array();
          foreach ($records as $record) {

            $shop = $record->shop;

            $slug = $slugModel->where(array(
              array('model','like','Shop'),
              array('model_id','=',$shop->id)
            ))->first()->slug;

            $shops[] = array(
              'name' => $shop->name,
              'url' => $url->url('shop/'.$slug)
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
