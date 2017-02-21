<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\library\service;
use App\library\url;
use App\library\string;
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
      view()->composer('*', function($view){

          $string = new String;
          $url = new Url;
          
          $slug = Route::current()->parameter('shopSlug');

          if(!empty($slug)) {

            $shopId = Service::loadModel('Slug')
            ->where('slug','like',$slug)
            ->first()
            ->model_id;

            $shop = Service::loadModel('Shop')
            ->select('name','description','profile_image_id','cover_image_id')
            ->find($shopId);

            // get desc
            // get brand story
            // get open hours
            // get address

            view()->share('_shop_id',$shopId);
            view()->share('_shop_name',$shop->name);
            view()->share('_shop_short_description',$string->subString($shop->description,250,true));
            view()->share('_shop_profileImage',$shop->getProfileImageUrl());
            view()->share('_shop_cover',$shop->getCoverUrl());

            $personToShop = Service::loadModel('PersonToShop');
            $person = $personToShop->getData(array(
              'conditions' => array(
                ['person_id','=',session()->get('Person.id')],
                ['shop_id','=',$shopId],
              ),
              'fields' => array('role_id'),
              'first' => true
            ));

            view()->share('_shop_permission',$person->role->getPermission());

            view()->share('_shop_setting_url',$url->url('shop/'.$slug.'/setting'));
            view()->share('_shop_product_url',$url->url('shop/'.$slug.'/product'));
            view()->share('_shop_job_url',$url->url('shop/'.$slug.'/job'));
            view()->share('_shop_advertising_url',$url->url('shop/'.$slug.'/advertising'));

          }

          if(Auth::check()){

            // $person = Service::loadModel('Person')->find(Auth::user()->id);

            $personToShop = Service::loadModel('PersonToShop')
            ->select(array('shop_id'))
            ->where('person_id','=',session()->get('Person.id'));

            // if($personToShop->exists()) {

              $slugModel = Service::loadModel('Slug');
          
              $records = $personToShop->get();

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

            // }

            view()->share('_shops',$shops);

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
