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

        if(!empty(Route::current()->parameter('shopSlug'))) {

          // $string = new String;
          $url = new Url;

          if(!empty(Route::current()->parameter('shopSlug'))) {

            $slug = Route::current()->parameter('shopSlug');

            // $shopId = Service::loadModel('Slug')
            // ->where('slug','like',$slug)
            // ->first()
            // ->model_id;

            // $shop = Service::loadModel('Shop')
            // ->select('name','description','profile_image_id','cover_image_id')
            // ->find($shopId);

            // view()->share('_shop_id',$shopId);
            // view()->share('_shop_name',$shop->name);
            // view()->share('_shop_short_description',$string->subString($shop->description,250,true));
            // view()->share('_shop_profileImage',$shop->getProfileImageUrl());
            // view()->share('_shop_cover',$shop->getCoverUrl());

            // $personToShop = Service::loadModel('PersonToShop');
            // $person = $personToShop->getData(array(
            //   'conditions' => array(
            //     ['person_id','=',session()->get('Person.id')],
            //     ['shop_id','=',$shopId],
            //   ),
            //   'fields' => array('role_id'),
            //   'first' => true
            // ));

            // view()->share('_shop_permission',$person->role->getPermission());

            view()->share('_shop_setting_url',$url->url('shop/'.$slug.'/setting'));
            view()->share('_shop_product_url',$url->url('shop/'.$slug.'/product'));
            view()->share('_shop_job_url',$url->url('shop/'.$slug.'/job'));
            view()->share('_shop_advertising_url',$url->url('shop/'.$slug.'/advertising'));

          }

        }

      });

      view()->composer('pages.shop.layouts.header', function($view){

        if(!empty(Route::current()->parameter('shopSlug'))) {

          $string = new String;
          $url = new Url;

          if(!empty(Route::current()->parameter('shopSlug'))) {

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

            // $personToShop = Service::loadModel('PersonToShop');
            // $person = $personToShop->getData(array(
            //   'conditions' => array(
            //     ['person_id','=',session()->get('Person.id')],
            //     ['shop_id','=',$shopId],
            //   ),
            //   'fields' => array('role_id'),
            //   'first' => true
            // ));

            // view()->share('_shop_permission',$person->role->getPermission());

            // view()->share('_shop_setting_url',$url->url('shop/'.$slug.'/setting'));
            // view()->share('_shop_product_url',$url->url('shop/'.$slug.'/product'));
            // view()->share('_shop_job_url',$url->url('shop/'.$slug.'/job'));
            // view()->share('_shop_advertising_url',$url->url('shop/'.$slug.'/advertising'));

          }

        }

      });

      view()->composer('layouts.blackbox.components.global-nav', function($view){

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

      view()->composer('layouts.blackbox.components.global-header', function($view){
        $view->with('_product_total',Service::loadModel('Cart')->productCount());
      });

      view()->composer('layouts.blackbox.components.global-cart', function($view){
        $view->with('_products',Service::loadModel('Cart')->getProducts());
      });

      // view()->composer('components.search_filter', function($view){
      //   dd($view);
      // });

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
