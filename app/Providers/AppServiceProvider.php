<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\library\service;
use App\library\url;
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
          // dd($view);
// dd(Auth::check());
                    // dd(session()->all());
          if(!empty(Route::current()->parameter('shopSlug'))) {

            $shopId = Service::loadModel('Slug')
            ->where('slug','like',Route::current()->parameter('shopSlug'))
            ->first()
            ->model_id;

            $shop = Service::loadModel('Shop')
            ->select('profile_image_id','cover_image_id')
            ->find($shopId);

            // get desc
            // get brand story
            // get open hours
            // get address

            view()->share('_shop_profileImage',$shop->getProfileImageUrl());
            view()->share('_shop_cover',$shop->getCoverUrl());

          }

          if(Auth::check()){

            // $person = Service::loadModel('Person')->find(Auth::user()->id);

            $personToShop = Service::loadModel('PersonToShop')
            ->select(array('shop_id'))
            ->where('person_id','=',session()->get('Person.id'));

            // if($personToShop->exists()) {

              $url = new Url;
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
